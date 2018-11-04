<?php
/*
 * 
 * Админка
 * 
 */
?>

<h1>Список ответов</h1>

<?php
$exampleListTable = new Pfm_Reply_List_Table();
$exampleListTable->prepare_items();
$exampleListTable->views();
?>
<div class="wrap">
    <div id="icon-users" class="icon32"></div>
    <form id="events-filter" method="get">
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
        <?php
        $exampleListTable->display();
        ?>
    </form>
</div>
<?php
// WP_List_Table is not loaded automatically so we need to load it in our application
if (!class_exists('WP_List_Table')) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * Create a new table class that will extend the WP_List_Table
 */
class Pfm_Reply_List_Table extends WP_List_Table {

    /**
     * Prepare the items for the table to process
     *
     * @return Void
     */
    public function prepare_items() {

        $this->process_bulk_action();

        $columns = $this->get_columns();

        $hidden = $this->get_hidden_columns();

        $sortable = $this->get_sortable_columns();

        //Получим все темы
        global $wpdb;

        $perPage = 20;

        $order = filter_input(INPUT_GET, 'order', FILTER_SANITIZE_SPECIAL_CHARS);
        $orderby = filter_input(INPUT_GET, 'orderby', FILTER_SANITIZE_SPECIAL_CHARS);
        $filter = filter_input(INPUT_GET, 'filter', FILTER_SANITIZE_SPECIAL_CHARS);

        if (!$order) {
            $order = 'DESC';
        }

        if (!$orderby) {
            $orderby = 'post_id';
        }

        if (!$filter) {

            $filter = false;
        } elseif ($filter == 'all') {

            $filter = false;
        } elseif ($filter == 'need_approval') {

            $filter = " WHERE " . RCL_PREF . "pforum_posts.post_status = 'need_approval' ";
        }

        $currentPage = $this->get_pagenum();

        $offset = ($currentPage - 1) * $perPage;

        $data = $wpdb->get_results("SELECT " . RCL_PREF . "pforum_posts.*, " . RCL_PREF . "pforum_topics.topic_name as topic_name, " . RCL_PREF . "pforum_topics.forum_id as forum_id  FROM " . RCL_PREF . "pforum_posts  LEFT JOIN " . RCL_PREF . "pforum_topics ON " . RCL_PREF . "pforum_posts.topic_id = " . RCL_PREF . "pforum_topics.topic_id $filter ORDER BY $orderby $order LIMIT 20 OFFSET $offset ", ARRAY_A);

        $totalItems = $wpdb->get_var("SELECT COUNT(*) FROM " . RCL_PREF . "pforum_posts $filter");

        usort($data, array(&$this, 'sort_data'));


        $this->set_pagination_args(array(
            'total_items' => $totalItems,
            'per_page' => $perPage
        ));

        $this->_column_headers = array($columns, $hidden, $sortable);

        $this->items = $data;
    }

    public function single_row($item) {
        $need_approve = false;
        if ($item['post_status'] == 'need_approval') {
            $need_approve = ' class="need-approve"';
        }
        echo '<tr' . $need_approve . '>';
        $this->single_row_columns($item);
        echo '</tr>';
    }

    /**
     * Override the parent columns method. Defines the columns to use in your listing table
     *
     * @return Array
     */
    public function get_columns() {
        $columns = array(
            'cb' => '<input type="checkbox" />',
            'user_id' => 'Автор',
            'post_content' => 'Содержание',
            'post_status' => 'Статус',
            'topic_name' => 'В теме',
            'forum_id' => 'Форум',
            'post_date' => 'Дата'
        );
        return $columns;
    }

    /**
     * Define which columns are hidden
     *
     * @return Array
     */
    public function get_hidden_columns() {
        return array();
    }

    /**
     * Define the sortable columns
     *
     * @return Array
     */
    public function get_sortable_columns() {
        return array(
            'post_id' => array('post_id', true),
            'topic_id' => array('topic_id', false),
            'user_id' => array('user_id', false),
            'forum_id' => array('forum_id', false),
            'post_date' => array('post_date', false)
        );
    }

    /**
     * Get the table data
     *
     * @return Array
     */

    /**
     * Define what data to show on each column of the table
     *
     * @param  Array $item        Data
     * @param  String $column_name - Current column name
     *
     * @return Mixed
     */
    public function column_default($item, $column_name) {

        if ($column_name == 'user_id') {
            if ($item[$column_name]) {
                $user_info = get_user_by('id', $item[$column_name]);
                $user_url = get_author_posts_url($item[$column_name]);
                $user_link = ''
                        . get_avatar($item[$column_name], 32)
                        . '<a href="' . $user_url . '" rel="noindex nofollow">' . $user_info->display_name . '</a><br>'
                        . '<a href="mailto:' . $user_info->user_email . '">' . $user_info->user_email . '</a>';
            } else {
                $user_link = ''
                        . get_avatar($item['guest_email'], 32)
                        . '<b>' . $item['guest_name'] . '</b><br>'
                        . '<a href="mailto:' . $item['guest_email'] . '">' . $item['guest_email'] . '</a>';
            }

            return $user_link;
        }

        if ($column_name == 'post_date') {
            return 'Опубликовано<br>' . $item[$column_name];
        }

        if ($column_name == 'topic_name') {
            if (!$item[$column_name])
                return '<small><b style="color:red;">Тема не найдена.</b><br> Вероятно это сообщение по какой-то причине не было удалено при удалении темы.</small>';
            $link = pfm_get_topic_permalink($item['topic_id']);
            return '<b><a href="' . $link . '" target="_blank">' . $item[$column_name] . '</a></b><br><a href="' . $link . '" target="_blank"><small>Посмотреть тему</small></a>';
        }

        if ($column_name == 'forum_id') {
            if (!$item[$column_name])
                return '<small><b style="color:red;">Форум не найден.</b></small>';
            $forum = pfm_get_forum($item[$column_name]);
            $link = pfm_get_forum_permalink($item[$column_name]);
            return '<b><a href="' . $link . '" target="_blank">' . $forum->forum_name . '</a></b><br><a href="' . $link . '" target="_blank"><small>Посмотреть форум</small></a>';
        }

        if ($column_name == 'post_content') {
            if (!$item[$column_name])
                return '<small><b style="color:red;">Тема не найдена.</b><br> Вероятно это сообщение по какой-то причине не было удалено при удалении темы.</small>';

            $value = $item[$column_name];
            $value = preg_replace('@<blockquote[^>]*?>.*?</blockquote>@si', '<small style="display:block;color:gray;">цитата удалена</small>', $value);
            $value = strip_tags($value, '<small>');
            $value = mb_substr($value, 0, 500);

            $nonce = wp_create_nonce('bulk-' . $this->_args['plural']);

            if ($item['post_status'] == 'open') {
                $post_status_action = 'pfm_unapprove_post';
                $post_status = 'Отклонить';
                $post_status_color = '#d98500';
            } else {
                $post_status_action = 'pfm_approve_post';
                $post_status = 'Одобрить';
                $post_status_color = '#006505';
            }

            $filter = filter_input(INPUT_GET, 'filter', FILTER_SANITIZE_SPECIAL_CHARS);
            if ($filter) {
                $filter = '&filter=' . $filter;
            } else {
                $filter = false;
            }

            $actions = array(
                $post_status_action => sprintf('<a href="?page=%s' . $filter . '&action=%s&post_id=%s&_wpnonce=%s" style="color:' . $post_status_color . ';">' . $post_status . '</a>', $_REQUEST['page'], $post_status_action, $item['post_id'], $nonce),
                'pfm_delete_post' => sprintf('<a href="?page=%s' . $filter . '&action=%s&post_id=%s&_wpnonce=%s" style="color:#a00;">Удалить</a>', $_REQUEST['page'], 'pfm_delete_post', $item['post_id'], $nonce),
                'pfm_to_post' => sprintf('<a href="%s" target="_blank">Перейти</a>', pfm_get_post_permalink($item['post_id'])),
            );

            return sprintf('%1$s %2$s', $value, $this->row_actions($actions));
        }

        if ($column_name == 'post_status') {
            if ($item[$column_name] == 'open') {
                return 'Опубликовано';
            } elseif ($item[$column_name] == 'need_approval') {
                return 'На утверждении';
            } else {
                return $item[$column_name];
            }
        }


        return $item[$column_name];
    }

    /**
     * Allows you to sort the data by the variables set in the $_GET
     *
     * @return Mixed
     */
    private function sort_data($a, $b) {
// Set defaults
        $orderby = 'post_id';
        $order = 'DESC';
// If orderby is set, use this as the sort column
        if (!empty($_GET['orderby'])) {
            $orderby = $_GET['orderby'];
        }
// If order is set use this as the order
        if (!empty($_GET['order'])) {
            $order = $_GET['order'];
        }
        $result = strnatcmp($a[$orderby], $b[$orderby]);
        if ($order === 'asc') {
            return $result;
        }
        return -$result;
    }

    function get_bulk_actions() {
        $actions = array(
            'pfm_delete_post' => 'Удалить',
            'pfm_approve_post' => 'Одобрить',
            'pfm_unapprove_post' => 'Отклонить'
        );
        return $actions;
    }

    function column_cb($item) {
        return sprintf(
                '<input type="checkbox" name="post_id[]" value="%s" />', $item['post_id']
        );
    }

    function get_views() {
        global $wpdb;
        $totalItems = $wpdb->get_var("SELECT COUNT(*) FROM " . RCL_PREF . "pforum_posts");
        $need_approval = $wpdb->get_var("SELECT COUNT(*) FROM " . RCL_PREF . "pforum_posts WHERE post_status = 'need_approval'");

        $curr_filter = filter_input(INPUT_GET, 'filter', FILTER_SANITIZE_SPECIAL_CHARS);

        if (!$curr_filter || $curr_filter == 'all') {
            $all_css = ' class="current" ';
            $need_approval_css = false;
        } elseif ($curr_filter == 'need_approval') {
            $all_css = false;
            $need_approval_css = ' class="current" ';
        } else {
            $all_link = false;
            $need_approval_link = false;
        }

        $status_links = array(
            "all" => "<a href='?page=pfm-reply&filter=all' " . $all_css . ">Все</a>(" . $totalItems . ")",
            "need_approval" => "<a href='?page=pfm-reply&filter=need_approval' " . $need_approval_css . ">На утверждении</a>(" . $need_approval . ")"
        );
        return $status_links;
    }

    function process_bulk_action() {
        if ('pfm_delete_post' === $this->current_action()) {

            if (!wp_verify_nonce(filter_input(INPUT_GET, '_wpnonce', FILTER_SANITIZE_SPECIAL_CHARS), 'bulk-' . $this->_args['plural'])) {
                wp_die('nonce error');
            }
            if (is_array($_GET['post_id'])) {
                pfm_extend_delete_post_bulk($_GET['post_id']);

                wp_redirect($_SERVER['HTTP_REFERER']);
                exit();
            } else {

                pfm_extend_delete_post_single(filter_input(INPUT_GET, 'post_id', FILTER_SANITIZE_SPECIAL_CHARS));
            }
            wp_redirect(remove_query_arg(array('_wpnonce', 'action', '_wp_http_referer', 'post_id', 'action2'), wp_unslash($_SERVER['REQUEST_URI'])));
            exit();
        }

        if ('pfm_approve_post' === $this->current_action()) {

            if (!wp_verify_nonce(filter_input(INPUT_GET, '_wpnonce', FILTER_SANITIZE_SPECIAL_CHARS), 'bulk-' . $this->_args['plural'])) {
                wp_die('nonce error');
            }
            if (is_array($_GET['post_id'])) {
                pfm_extend_approve_post_bulk($_GET['post_id']);
                wp_redirect($_SERVER['HTTP_REFERER']);
                exit();
            } else {

                pfm_extend_approve_post_single(filter_input(INPUT_GET, 'post_id', FILTER_SANITIZE_SPECIAL_CHARS));
            }
            wp_redirect(remove_query_arg(array('_wpnonce', 'action', '_wp_http_referer', 'post_id', 'action2'), wp_unslash($_SERVER['REQUEST_URI'])));
            exit();
        }

        if ('pfm_unapprove_post' === $this->current_action()) {

            if (!wp_verify_nonce(filter_input(INPUT_GET, '_wpnonce', FILTER_SANITIZE_SPECIAL_CHARS), 'bulk-' . $this->_args['plural'])) {
                wp_die('nonce error');
            }
            if (is_array($_GET['post_id'])) {
                pfm_extend_unapprove_post_bulk($_GET['post_id']);
                wp_redirect($_SERVER['HTTP_REFERER']);
                exit();
            } else {

                pfm_extend_unapprove_post_single(filter_input(INPUT_GET, 'post_id', FILTER_SANITIZE_SPECIAL_CHARS));
            }
            wp_redirect(remove_query_arg(array('_wpnonce', 'action', '_wp_http_referer', 'post_id', 'action2'), wp_unslash($_SERVER['REQUEST_URI'])));
            exit();
        }
    }

}
?>