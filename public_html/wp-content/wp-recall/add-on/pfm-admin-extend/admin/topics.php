<?php
/*
 * 
 * Админка
 * 
 */
?>
<h1>Список тем</h1>
<?php
$exampleListTable = new Pfm_Topics_List_Table();
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
class Pfm_Topics_List_Table extends WP_List_Table {

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
            $orderby = 'topic_id';
        }

        if (!$filter) {

            $filter = false;
            $totalItems = $wpdb->get_var("SELECT COUNT(*) FROM " . RCL_PREF . "pforum_topics");
        } elseif ($filter == 'all') {

            $filter = false;
            $totalItems = $wpdb->get_var("SELECT COUNT(*) FROM " . RCL_PREF . "pforum_topics");
        } elseif ($filter == 'need_approval') {

            $filter = " WHERE " . RCL_PREF . "pforum_posts.post_status = 'need_approval' ";
        }

        $currentPage = $this->get_pagenum();

        $offset = ($currentPage - 1) * $perPage;

        $data = $wpdb->get_results("SELECT " . RCL_PREF . "pforum_topics.*, " . RCL_PREF . "pforum_posts.post_status as post_status , " . RCL_PREF . "pforum_posts.post_date as topic_date FROM " . RCL_PREF . "pforum_topics LEFT JOIN " . RCL_PREF . "pforum_posts ON " . RCL_PREF . "pforum_topics.topic_id = " . RCL_PREF . "pforum_posts.topic_id AND " . RCL_PREF . "pforum_posts.post_index = 1 $filter ORDER BY $orderby $order LIMIT 20 OFFSET $offset ", ARRAY_A);



        //$data = $this->table_data();

        usort($data, array(&$this, 'sort_data'));





        //$totalItems = count($data);

        $this->set_pagination_args(array(
            'total_items' => $totalItems,
            'per_page' => $perPage
        ));

        //$data = array_slice($data, (($currentPage - 1) * $perPage), $perPage);

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
            //'topic_id' => 'ID',
            'topic_name' => 'Заголовок',
            'user_id' => 'Автор',
            'forum_id' => 'Форум',
            'topic_status' => 'Статус',
            'post_count' => 'Сообщений',
            'topic_date' => 'Дата'
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
            'topic_id' => array('topic_id', true),
            'topic_name' => array('topic_name', false),
            'user_id' => array('user_id', false),
            'forum_id' => array('forum_id', false),
            'post_count' => array('post_count', false),
            'topic_date' => array('topic_date', false)
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
            $user_info = get_user_by('id', $item[$column_name]);
            $user_url = get_author_posts_url($item[$column_name]);
            $user_link = '<a href="' . $user_url . '" rel="noindex nofollow">' . $user_info->display_name . '</a>';
            return $user_link;
        }
        if ($column_name == 'topic_date') {
            return 'Опубликовано<br>' . $item[$column_name];
        }
        if ($column_name == 'topic_status') {
            if ($item['post_status'] == 'need_approval') {
                $status = 'На утверждении';
            } elseif ($item['topic_closed'] == 1) {
                $status = 'Закрыта';
            } else {
                $status = 'Открыта';
            }
            return $status;
        }
        if ($column_name == 'forum_id') {
            $forum = pfm_get_forum($item[$column_name]);
            return '<a href="' . pfm_get_forum_permalink($item[$column_name]) . '" target="_blank">' . $forum->forum_name . '</a>';
        }
        if ($column_name == 'topic_name') {

            $value = '<strong><a href="' . pfm_get_topic_permalink($item['topic_id']) . '" target="_blank">' . $item[$column_name] . '</a></strong>';

            $nonce = wp_create_nonce('bulk-' . $this->_args['plural']);

            if ($item['topic_closed'] == 1) {
                $topic_status_action = 'pfm_open_topic';
                $topic_status = 'Открыть';
                $topic_status_color = '#006505';
            } else {
                $topic_status_action = 'pfm_close_topic';
                $topic_status = 'Закрыть';
                $topic_status_color = '#d98500';
            }

            if ($item['post_status'] == 'need_approval') {
                $post_status_action = 'pfm_approve_topic';
                $post_status = 'Одобрить';
                $post_status_color = '#006505';
            } else {
                $post_status_action = 'pfm_unapprove_topic';
                $post_status = 'Отклонить';
                $post_status_color = '#d98500';
            }

            $filter = filter_input(INPUT_GET, 'filter', FILTER_SANITIZE_SPECIAL_CHARS);
            if ($filter) {
                $filter = '&filter=' . $filter;
            } else {
                $filter = false;
            }

            $actions = array(
                $topic_status_action => sprintf('<a href="?page=%s' . $filter . '&action=%s&topic_id=%s&_wpnonce=%s" style="color:' . $topic_status_color . ';">%s</a>', $_REQUEST['page'], $topic_status_action, $item['topic_id'], $nonce, $topic_status),
                $post_status_action => sprintf('<a href="?page=%s' . $filter . '&action=%s&topic_id=%s&_wpnonce=%s" style="color:' . $post_status_color . ';">%s</a>', $_REQUEST['page'], $post_status_action, $item['topic_id'], $nonce, $post_status),
                'pfm_delete_topic' => sprintf('<a href="?page=%s' . $filter . '&action=%s&topic_id=%s&_wpnonce=%s" style="color:#a00;">Удалить</a>', $_REQUEST['page'], 'pfm_delete_topic', $item['topic_id'], $nonce),
                'pfm_to_topic' => sprintf('<a href="%s" target="_blank">Перейти</a>', pfm_get_topic_permalink($item['topic_id'])),
            );

            return sprintf('%1$s %2$s', $value, $this->row_actions($actions));
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
        $orderby = 'topic_id';
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
            'pfm_delete_topic' => 'Удалить',
            'pfm_open_topic' => 'Открыть',
            'pfm_close_topic' => 'Закрыть',
            'pfm_approve_topic' => 'Одобрить',
            'pfm_unapprove_topic' => 'Отклонить'
        );
        return $actions;
    }

    function column_cb($item) {
        return sprintf(
                '<input type="checkbox" name="topic_id[]" value="%s" />', $item['topic_id']
        );
    }

    function get_views() {
        global $wpdb;
        $totalItems = $wpdb->get_var("SELECT COUNT(*) FROM " . RCL_PREF . "pforum_topics");
        $need_approval = $wpdb->get_var("SELECT COUNT(*) FROM " . RCL_PREF . "pforum_topics LEFT JOIN " . RCL_PREF . "pforum_posts ON " . RCL_PREF . "pforum_topics.topic_id = " . RCL_PREF . "pforum_posts.topic_id WHERE " . RCL_PREF . "pforum_posts.post_status = 'need_approval' AND " . RCL_PREF . "pforum_posts.post_index = '1'");

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
            "all" => "<a href='?page=pfm-topics&filter=all' " . $all_css . ">Все</a>(" . $totalItems . ")",
            "need_approval" => "<a href='?page=pfm-topics&filter=need_approval' " . $need_approval_css . ">На утверждении</a>(" . $need_approval . ")"
        );
        return $status_links;
    }

    function process_bulk_action() {

        if ('pfm_delete_topic' === $this->current_action()) {
            if (!wp_verify_nonce(filter_input(INPUT_GET, '_wpnonce', FILTER_SANITIZE_SPECIAL_CHARS), 'bulk-' . $this->_args['plural'])) {
                wp_die('nonce error');
            }

            if (is_array($_GET['topic_id'])) {
                pfm_extend_delete_topic_bulk($_GET['topic_id']);
                wp_redirect($_SERVER['HTTP_REFERER']);
                exit();
            } else {
                // var_dump($_GET['topic_id']);
                pfm_extend_delete_topic_single(filter_input(INPUT_GET, 'topic_id', FILTER_SANITIZE_SPECIAL_CHARS));
            }
            wp_redirect(remove_query_arg(array('_wpnonce', 'action', '_wp_http_referer', 'topic_id', 'action2'), wp_unslash($_SERVER['REQUEST_URI'])));
            exit();
        }

        if ('pfm_close_topic' === $this->current_action()) {
            if (!wp_verify_nonce(filter_input(INPUT_GET, '_wpnonce', FILTER_SANITIZE_SPECIAL_CHARS), 'bulk-' . $this->_args['plural'])) {
                wp_die('nonce error');
            }
            if (is_array($_GET['topic_id'])) {
                pfm_extend_close_topic_bulk($_GET['topic_id']);
                wp_redirect($_SERVER['HTTP_REFERER']);
                exit();
            } else {
                pfm_extend_close_topic_single(filter_input(INPUT_GET, 'topic_id', FILTER_SANITIZE_SPECIAL_CHARS));
            }
            wp_redirect(remove_query_arg(array('_wpnonce', 'action', '_wp_http_referer', 'topic_id', 'action2'), wp_unslash($_SERVER['REQUEST_URI'])));
            exit();
        }

        if ('pfm_open_topic' === $this->current_action()) {
            if (!wp_verify_nonce(filter_input(INPUT_GET, '_wpnonce', FILTER_SANITIZE_SPECIAL_CHARS), 'bulk-' . $this->_args['plural'])) {
                wp_die('nonce error');
            }
            if (is_array($_GET['topic_id'])) {
                pfm_extend_open_topic_bulk($_GET['topic_id']);
                wp_redirect($_SERVER['HTTP_REFERER']);
                exit();
            } else {
                // var_dump($_GET['topic_id']);
                pfm_extend_open_topic_single(filter_input(INPUT_GET, 'topic_id', FILTER_SANITIZE_SPECIAL_CHARS));
            }
            wp_redirect(remove_query_arg(array('_wpnonce', 'action', '_wp_http_referer', 'topic_id', 'action2'), wp_unslash($_SERVER['REQUEST_URI'])));
            exit();
        }

        if ('pfm_approve_topic' === $this->current_action()) {
            if (!wp_verify_nonce(filter_input(INPUT_GET, '_wpnonce', FILTER_SANITIZE_SPECIAL_CHARS), 'bulk-' . $this->_args['plural'])) {
                wp_die('nonce error');
            }
            if (is_array($_GET['topic_id'])) {
                pfm_extend_approve_topic_bulk($_GET['topic_id']);
                wp_redirect($_SERVER['HTTP_REFERER']);
                exit();
            } else {
                // var_dump($_GET['topic_id']);
                pfm_extend_approve_topic_single(filter_input(INPUT_GET, 'topic_id', FILTER_SANITIZE_SPECIAL_CHARS));
            }
            wp_redirect(remove_query_arg(array('_wpnonce', 'action', '_wp_http_referer', 'topic_id', 'action2'), wp_unslash($_SERVER['REQUEST_URI'])));
            exit();
        }

        if ('pfm_unapprove_topic' === $this->current_action()) {
            if (!wp_verify_nonce(filter_input(INPUT_GET, '_wpnonce', FILTER_SANITIZE_SPECIAL_CHARS), 'bulk-' . $this->_args['plural'])) {
                wp_die('nonce error');
            }
            if (is_array($_GET['topic_id'])) {
                pfm_extend_unapprove_topic_bulk($_GET['topic_id']);
                wp_redirect($_SERVER['HTTP_REFERER']);
                exit();
            } else {
                // var_dump($_GET['topic_id']);
                pfm_extend_unapprove_topic_single(filter_input(INPUT_GET, 'topic_id', FILTER_SANITIZE_SPECIAL_CHARS));
            }
            wp_redirect(remove_query_arg(array('_wpnonce', 'action', '_wp_http_referer', 'topic_id', 'action2'), wp_unslash($_SERVER['REQUEST_URI'])));
            exit();
        }
    }

}
