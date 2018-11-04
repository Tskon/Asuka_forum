<?php

add_action('admin_menu', 'prm_init_admin_menu',10);
function prm_init_admin_menu(){
    add_submenu_page( 'pfm-menu', __('Role Manager'), __('Role Manager'), 'manage_options', 'prime-role-manager', 'prm_manager_page');
}

function prm_manager_page(){

    require_once 'class-prime-role-manager.php';

    $tabsManager = new Prime_Role_Manager(
                        array(
                            'meta-key'=>true,
                            'select-type'=>false,
                            'placeholder'=>false,
                            'sortable'=>false
                        )
                    );
    
    $content = '<style>'
            . '#rcl-custom-fields-editor.rcl-custom-fields-box{max-width: 550px;}'
            . '#rcl-custom-fields-editor .rcl-checkbox-box{width: 242px;}'
            . '</style>';

    $content .= '<h2>'.__('Prime Role Manager').'</h2>';

    $content .= $tabsManager->active_fields_box();

    echo $content;
    
}

