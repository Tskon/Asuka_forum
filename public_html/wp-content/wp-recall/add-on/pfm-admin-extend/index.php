<?php

if (!is_admin()):
    add_action('rcl_enqueue_scripts', 'rcl_pfm_admin_extend_script_frontend', 10);
endif;

function rcl_pfm_admin_extend_script_frontend() {
    if (!rcl_exist_addon('prime-forum') || !is_prime_forum()) {
        return false;
    }
    rcl_enqueue_style('pfm-admin-extend', rcl_addon_url('style.css', __FILE__));
    rcl_enqueue_script('pfm-admin-extend', rcl_addon_url('script.js', __FILE__));
}

//Админка
if (is_admin()):
    add_action('admin_head', 'rcl_pfm_admin_extend_script_admin', 10);

//require('admin/option.php');
endif;

function rcl_pfm_admin_extend_script_admin() {
    rcl_enqueue_style('pfm-admin-extend', rcl_addon_url('admin/style.css', __FILE__));
    //rcl_enqueue_script('pfm-admin-extend', rcl_addon_url('admin/script.js', __FILE__));
}

//Добавляем кнопку в меню recall
add_action('admin_menu', 'rcl_pfm_admin_extend_topics', 10);

function rcl_pfm_admin_extend_topics() {
    $Posts = new PrimePosts();
    $postCount = $Posts->count(array('post_status' => 'need_approval', 'post_index' => 1));
    $notice = ' <span class="update-plugins count-' . $postCount . '"><span class="plugin-count">' . $postCount . '</span></span>';
    add_submenu_page('pfm-menu', 'Все темы', 'Все темы' .$notice, 'manage_options', 'pfm-topics', 'rcl_pfm_admin_extend_topics_menu');
}

function rcl_pfm_admin_extend_topics_menu() {

    require_once('admin/topics.php');
}

//Добавляем кнопку в меню recall
add_action('admin_menu', 'rcl_pfm_admin_extend_posts', 10);

function rcl_pfm_admin_extend_posts() {
    $Posts = new PrimePosts();
    $postCount = $Posts->count(array('post_status' => 'need_approval'));
    $notice = ' <span class="update-plugins count-' . $postCount . '"><span class="plugin-count">' . $postCount . '</span></span>';
    add_submenu_page('pfm-menu', 'Все ответы', 'Все ответы' . $notice, 'manage_options', 'pfm-reply', 'rcl_pfm_admin_extend_posts_menu');
}

function rcl_pfm_admin_extend_posts_menu() {

    require_once('admin/reply.php');
}

require_once('admin/approval.php');

//Удаление топика

function pfm_extend_delete_topic_bulk($ids = false) {

    if (!$ids) {
        return false;
    }

    $result = '<div class="updated notice">';

    $i = 0;


    foreach ($ids as $id) {
        //Удаляем топик
        $id = (int) $id;
        if (!pfm_delete_topic($id)) {
            $i--;
        }

        $i++;
    }

    $result .= '<p>Тем удалено: ' . $i . '</p>';

    $result .= '</div>';

    echo $result;
}

function pfm_extend_delete_topic_single($id = false) {

    if (!$id) {
        return false;
    }
    if (pfm_delete_topic($id)) {
        $result = '<div class="updated notice">';
        $result .= '<p>Тема ' . pfm_get_topic_name($id) . ' удалена</p>';
        $result .= '</div>';
    } else {
        $result = '<div class="updated error">';
        $result .= '<p>Тема ' . pfm_get_topic_name($id) . ' не была удалена</p>';
        $result .= '</div>';
    }
    echo $result;
}

//Удаление поста

function pfm_extend_delete_post_bulk($ids = false) {

    if (!$ids) {
        return false;
    }

    $result = '<div class="updated notice">';

    $i = 0;


    foreach ($ids as $id) {
        //Удаляем пост
        $id = (int) $id;
        if (!pfm_delete_post($id)) {
            $i--;
        }

        $i++;
    }

    $result .= '<p>Постов удалено: ' . $i . '</p>';

    $result .= '</div>';

    echo $result;
}

function pfm_extend_delete_post_single($id = false) {

    if (!$id) {
        return false;
    }
    if (pfm_delete_post($id)) {
        $result = '<div class="updated notice">';
        $result .= '<p>Пост ' . $id . ' удален</p>';
        $result .= '</div>';
    } else {
        $result = '<div class="updated error">';
        $result .= '<p>Пост ' . $id . ' не был удален</p>';
        $result .= '</div>';
    }
    echo $result;
}

//Закрытие темы

function pfm_extend_close_topic_bulk($ids = false) {

    if (!$ids) {
        return false;
    }

    $result = '<div class="updated notice">';

    $i = 0;


    foreach ($ids as $id) {
        //Закрываем тему
        $id = (int) $id;
        if (!pfm_topic_close($id)) {
            $i--;
        }

        $i++;
    }

    $result .= '<p>Тем закрыто: ' . $i . '</p>';

    $result .= '</div>';

    echo $result;
}

function pfm_extend_close_topic_single($id = false) {

    if (!$id) {
        return false;
    }
    if (pfm_topic_close($id)) {
        $result = '<div class="updated notice">';
        $result .= '<p>Тема ' . $id . ' закрыта</p>';
        $result .= '</div>';
    } else {
        $result = '<div class="updated error">';
        $result .= '<p>Тема ' . $id . ' не была закрыта</p>';
        $result .= '</div>';
    }
    echo $result;
}

//Открытие темы

function pfm_extend_open_topic_bulk($ids = false) {

    if (!$ids) {
        return false;
    }

    $result = '<div class="updated notice">';

    $i = 0;


    foreach ($ids as $id) {
        //Закрываем тему
        $id = (int) $id;
        if (!pfm_topic_unclose($id)) {
            $i--;
        }

        $i++;
    }

    $result .= '<p>Тем открыто: ' . $i . '</p>';

    $result .= '</div>';

    echo $result;
}

function pfm_extend_open_topic_single($id = false) {

    if (!$id) {
        return false;
    }
    if (pfm_topic_unclose($id)) {
        $result = '<div class="updated notice">';
        $result .= '<p>Тема ' . $id . ' открыта</p>';
        $result .= '</div>';
    } else {
        $result = '<div class="updated error">';
        $result .= '<p>Тема ' . $id . ' не была открыта</p>';
        $result .= '</div>';
    }
    echo $result;
}

//Буферизация вывода для работы wp_redirect
function app_output_buffer() {

    ob_start();
}

parse_str($_SERVER['QUERY_STRING'], $get_array);
if ($get_array['page'] == 'pfm-topics' || $get_array['page'] == 'pfm-reply') {
    add_action('init', 'app_output_buffer');
}