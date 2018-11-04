<?php

//Добавим опцию для роли 'требуется модерация'
add_filter('pfm_capabilities', 'pfm_capabilities_extend');

function pfm_capabilities_extend($capabilities) {
    $capabilities['post_need_approval'] = false;
    $capabilities['approve_posts'] = false;
    return $capabilities;
}

add_filter('prm_capability_names', 'pfm_capabilities_name_extend');
function pfm_capabilities_name_extend($capabilities) {
    
    $capabilities['post_need_approval'] = 'Посты и темы требуют утверждения';
    $capabilities['approve_posts'] = 'Может утверждать посты и темы';
    return $capabilities;
}


//Поставим пост на модерацию перед сохранением
add_filter('pfm_pre_save_postdata', 'pfm_extend_before_add_post');

function pfm_extend_before_add_post($args) {

    if (pfm_is_user_can($args['user_id'], 'post_need_approval')) {
        $args['post_status'] = 'need_approval';
    }
    return $args;
}

//Фильтруем стандартные классы добавляя свой
add_filter('pfm_topic_classes', 'pfm_topic_approval_class', 999);

function pfm_topic_approval_class($classes) {

    global $user_ID, $PrimeTopic, $PrimeQuery, $TopicListApprovalID;

    if(!pfm_add_global_var_approval_count()) {
        return $classes;
    }

    if (pfm_is_user_can($user_ID, 'approve_posts')) {
        $cat_approve = 'can-approve ';
    } else {
        $cat_approve = false;
    }

    if ($TopicListApprovalID[$PrimeTopic->topic_id]) {
        $classes[] = $cat_approve . 'have-ua '
                . 'ua-count-' . $TopicListApprovalID[$PrimeTopic->topic_id]['count'] . ' '
                . 'min-ua-post-index-' . $TopicListApprovalID[$PrimeTopic->topic_id]['min_post_index'];
    }


    return $classes;
}

function pfm_add_global_var_approval_count() {
    global $user_ID, $PrimeQuery, $TopicListApprovalID, $wpdb;

    $TopicListApprovalID = array();

    if ($PrimeQuery->topics) {
        foreach ($PrimeQuery->topics as $topic) {
            $TopicListApprovalID[] = $topic->topic_id;
        }
    } else {
        return false;
    }

    $lists = implode(',', $TopicListApprovalID);

    $get_approval_count = $wpdb->get_results("SELECT topic_id, MIN(post_index) as min_post_index, COUNT(*) as count FROM " . RCL_PREF . "pforum_posts WHERE topic_id IN($lists) AND post_status = 'need_approval' GROUP BY topic_id", ARRAY_A);

    if ($get_approval_count && !empty($get_approval_count)) {
        $TopicListApprovalID = array();
        foreach ($get_approval_count as $get) {
            $TopicListApprovalID[$get['topic_id']] = array('min_post_index' => $get['min_post_index'], 'count' => $get['count']);
        }
    } else {
        return false;
    }

    return $TopicListApprovalID;
}

//Аякс одобрение фронтенд
add_action('wp_ajax_pfm_approve_post_frontend', 'pfm_approve_post_frontend');

function pfm_approve_post_frontend() {

    $post_id = (int) $_POST['id'];
    $nonce = filter_input(INPUT_POST, 'nonce', FILTER_SANITIZE_SPECIAL_CHARS);

    if (!wp_verify_nonce($nonce, 'pfm_approve_post_' . $post_id)) {
        wp_send_json(array('error' => 'Проверка не пройдена'));
    }

    $args = array(
        'post_id' => $post_id,
        'post_status' => 'open'
    );

    if (!pfm_update_post($args)) {
        wp_send_json(array('error' => 'Ошибка одобрения поста'));
    }

    wp_send_json(array('content' => 'Пост одобрен'));
}

//Фильтруем контент поста требующего модерацию
add_filter('pfm_the_post_content', 'pfm_check_content_to_approval', 99);

function pfm_check_content_to_approval($content) {

    global $PrimePost, $user_ID;


    $ifpreview = filter_input(INPUT_POST, 'method', FILTER_SANITIZE_SPECIAL_CHARS);
    
    if($ifpreview && $ifpreview == 'get_preview') {
        return $content;
    }
    
    if (!$PrimePost->post_status) {
        $post_status = pfm_get_post($PrimePost->post_id)->post_status;
    } else {
        $post_status = $PrimePost->post_status;
    }

    if ($post_status == 'open') {

        return $content;
    }

    $notice = '<div class="pfm-post-need-approve">Сообщение ожидает утверждения</div>';

    if ($PrimePost->user_id == $user_ID && $post_status == 'need_approval') {
        if (pfm_is_user_can($user_ID, 'approve_posts')) {
            $nonce = wp_create_nonce('pfm_approve_post_' . $PrimePost->post_id);
            $notice = '<div class="pfm-post-need-approve">Сообщение ожидает утверждения<br><a href="#" data-nonce="' . $nonce . '" data-id="' . $PrimePost->post_id . '">Одобрить</a></div>';
        }

        return $notice . $content;
    }


    if (pfm_is_user_can($user_ID, 'approve_posts') && $post_status == 'need_approval') {
        $nonce = wp_create_nonce('pfm_approve_post_' . $PrimePost->post_id);
        $notice = '<div class="pfm-post-need-approve">Сообщение ожидает утверждения<br><a href="#" data-nonce="' . $nonce . '" data-id="' . $PrimePost->post_id . '">Одобрить</a></div>';
        return $notice . $content;
    }
    return $notice;
}

//Одобрение
function pfm_extend_approve_topic_bulk($ids = false) {
    //Переделаем id тем в id первых постов и вызовем одобрение постов
    $Posts = new PrimePosts();
    $posts = array();
    $posts = $Posts->get_results(array('topic_id__in' => $ids, 'post_index' => 1, 'fields' => array('post_id')));
    foreach ($posts as $post) {
        $posts_ids[] = (int) $post->post_id;
    }
    pfm_extend_approve_post_bulk($posts_ids);
}

function pfm_extend_approve_post_bulk($ids = false) {

    if (!$ids) {
        return false;
    }

    $result = '<div class="updated notice">';

    $i = 0;


    foreach ($ids as $id) {
        //Одобряем пост
        $id = (int) $id;
        $args = array(
            'post_id' => $id,
            'post_status' => 'open'
        );
        if (!pfm_update_post($args)) {
            $i--;
        }

        $i++;
    }

    $result .= '<p>Постов одобрено: ' . $i . '</p>';

    $result .= '</div>';

    echo $result;
}

function pfm_extend_approve_topic_single($id = false) {
    //Переделаем id тем в id первых постов
    $Posts = new PrimePosts();
    $posts = array();
    $posts = $Posts->get_results(array('topic_id' => $id, 'post_index' => 1, 'fields' => array('post_id')));
    foreach ($posts as $post) {
        $posts_ids = (int) $post->post_id;
    }
    pfm_extend_approve_post_single($posts_ids);
}

function pfm_extend_approve_post_single($id = false) {

    if (!$id) {
        return false;
    }

    $args = array(
        'post_id' => $id,
        'post_status' => 'open'
    );

    if (pfm_update_post($args)) {
        $result = '<div class="updated notice">';
        $result .= '<p>Пост ' . $id . ' одобрен</p>';
        $result .= '</div>';
    } else {
        $result = '<div class="updated error">';
        $result .= '<p>Пост ' . $id . ' не был одобрен</p>';
        $result .= '</div>';
    }
    echo $result;
}

//На утверждение
function pfm_extend_unapprove_topic_bulk($ids = false) {
    //Переделаем id тем в id первых постов
    $Posts = new PrimePosts();
    $posts = array();
    $posts = $Posts->get_results(array('topic_id__in' => $ids, 'post_index' => 1, 'fields' => array('post_id')));
    foreach ($posts as $post) {
        $posts_ids[] = (int) $post->post_id;
    }
    pfm_extend_unapprove_post_bulk($posts_ids);
}

function pfm_extend_unapprove_post_bulk($ids = false) {

    if (!$ids) {
        return false;
    }

    $result = '<div class="updated notice">';

    $i = 0;


    foreach ($ids as $id) {
        //Одобряем пост
        $id = (int) $id;
        $args = array(
            'post_id' => $id,
            'post_status' => 'need_approval'
        );
        if (!pfm_update_post($args)) {
            $i--;
        }

        $i++;
    }

    $result .= '<p>Постов отклонено: ' . $i . '</p>';

    $result .= '</div>';

    echo $result;
}

function pfm_extend_unapprove_topic_single($id = false) {
    //Переделаем id тем в id первых постов
    $Posts = new PrimePosts();
    $posts = array();
    $posts = $Posts->get_results(array('topic_id' => $id, 'post_index' => 1, 'fields' => array('post_id')));
    foreach ($posts as $post) {
        $posts_ids = (int) $post->post_id;
    }
    pfm_extend_unapprove_post_single($posts_ids);
}
function pfm_extend_unapprove_post_single($id = false) {

    if (!$id) {
        return false;
    }

    $args = array(
        'post_id' => $id,
        'post_status' => 'need_approval'
    );

    if (pfm_update_post($args)) {
        $result = '<div class="updated notice">';
        $result .= '<p>Пост ' . $id . ' отклонен</p>';
        $result .= '</div>';
    } else {
        $result = '<div class="updated error">';
        $result .= '<p>Пост ' . $id . ' не был отклонен</p>';
        $result .= '</div>';
    }
    echo $result;
}
