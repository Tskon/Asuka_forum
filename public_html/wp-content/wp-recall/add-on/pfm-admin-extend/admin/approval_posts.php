<?php

//Добавим опцию для роли 'требуется модерация'
add_filter('pfm_capabilities', 'pfm_capabilities_extend');

function pfm_capabilities_extend($capabilities) {
    $capabilities['post_need_approval'] = false;
    $capabilities['approve_posts'] = false;
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

//Одобрение поста

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
