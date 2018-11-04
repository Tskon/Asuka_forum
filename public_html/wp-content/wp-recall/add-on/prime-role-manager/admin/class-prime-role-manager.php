<?php

class Prime_Role_Manager extends Rcl_Custom_Fields_Manager{
    
    public $roles;
    
    function __construct($options = false) {
        
        $this->roles = new PrimeRoles();
        
        parent::__construct('prime-role-manager', $options);

        $this->setup_tabs();

        add_filter('rcl_custom_field_options', array($this, 'edit_tab_options'), 10, 3);
        
    }
    
    function get_cap_names(){
        
        $names = array(
            'forum_view' => __('Доступ к форуму'),
            'topic_create' => __('Создание темы'),
            'topic_delete' => __('Ограниченное удаление темы'),
            'topic_edit' => __('Ограниченное изменение темы'),
            'topic_other_delete' => __('Удаление любых тем'),
            'topic_other_edit' => __('Изменение любых тем'),
            'topic_fix' => __('Закрепление темы'),
            'topic_close' => __('Закрытие темы'),
            'topic_migrate' => __('Перенос темы'),
            'post_create' => __('Создание сообщения в теме'),
            'post_edit' => __('Ограниченное изменение сообщения'),
            'post_delete' => __('Ограниченное удаление сообщения'),
            'post_other_edit' => __('Изменение любых сообщений'),
            'post_other_delete' => __('Удаление любых сообщений'),
            'post_migrate' => __('Перенос сообщения')
        );
        
        $names = apply_filters('prm_capability_names', $names);
        
        return $names;
        
    }
    
    function get_default_options(){
        
        $caps = $this->roles->get_capabilities();
        
        $capNames = $this->get_cap_names();
        
        $values = array();
        foreach($caps as $capID => $value){
            
            $name = (isset($capNames[$capID]))? $capNames[$capID]: $capID;
            
            $values[$capID] = $name;
            
        }
        
        $defaultOptions[] = array(
            'type' => 'checkbox',
            'slug'=> 'capabilities',
            'title' => __('Права роли'),
            'values'=>$values
        );

        return $defaultOptions;
    }
    
    function active_fields_box(){

        return $this->manager_form($this->get_default_options());
        
    }
    
    function is_default_tab($slug){
        
        $defaultRoles = $this->roles->get_default_roles();
        
        if(isset($defaultRoles[$slug]))
            return true;
        
        return false;
        
    }
    
    function setup_tabs(){
        
        $defaultTabs = $this->get_default_tabs();
        
        if($this->fields){
            
            foreach($this->fields as $k => $tab){

                if($this->is_default_tab($tab['slug'])){
                    $this->fields[$k]['delete'] = false;
                }else{
                    if(isset($tab['default-role'])){
                        unset($this->fields[$k]);
                    }
                }
                
            }
            
            foreach($defaultTabs as $tab){
                if($this->exist_active_field($tab['slug']))continue;
                $this->fields[] = $tab;
            }
            
        }else{
            
            $this->fields = $defaultTabs;
            
        }
        
    }
    
    function get_default_tabs(){

        $defaultRoles = $this->roles->get_default_roles();
        
        $fields = array();
        
        foreach($defaultRoles as $role_id => $props){
            
            $caps = array();
            foreach($props['capabilities'] as $capID => $capVal){
                if(!$capVal) continue;
                $caps[] = $capID;
            }
            
             $tabData = array(
                'type-edit' => false,
                'slug' => $role_id,
                'delete' => false,
                'default-role' => true,
                'type' => 'custom',
                'title' => $props['name'],
                'capabilities' => $caps
            );
            
            
            
            $fields[] = $tabData;
            
        }
        
        return $fields;

    }
    
    function edit_tab_options($options, $field, $type){
        
        if(!isset($field['slug'])) return $options;

        if($this->is_default_tab($field['slug'])){

            if(in_array($field['slug'],array('ban','guest'))){ 
                
                $capNames = $this->get_cap_names();
                
                foreach($options as $k => $option){
                    if($option['slug'] == 'capabilities'){
                        $options[$k]['values'] = array(
                            'forum_view' => $capNames['forum_view'],
                            'post_create' => $capNames['post_create']
                        );
                    }
                }

            }
            
            $options[] = array(
                'type' => 'hidden',
                'slug' => 'default-role',
                'value' => 1
            );
            
        }
        
        return $options;
        
    }
    
}

