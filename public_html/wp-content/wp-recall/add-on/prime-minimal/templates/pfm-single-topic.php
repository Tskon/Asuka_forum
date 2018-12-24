<div class="prime-forum-item <?php pfm_the_topic_classes(); ?>">
    <?php pfm_the_topic_manager(); ?>
	<?php 
		global $PrimeTopic;
		echo '<a class="prime-forum-modern-avatar" href="'.get_author_posts_url($PrimeTopic->user_id).'">'.get_avatar($PrimeTopic->user_id, 300 ).'</a>';
	?>
    <div class="prime-forum-title">
        <div class="prime-general-title"><?php pfm_the_forum_icons(); ?><a class="" title="<?php _e('Go to topic','wp-recall'); ?>" href="<?php pfm_the_topic_permalink(); ?>"><?php pfm_the_topic_name(); ?></a></div>
        <span><?php _e('Last message','wp-recall'); ?></span>
        <span><?php pfm_the_last_post(); ?></span>
        <?php pfm_page_navi(array('type'=>'topic')); ?>
    </div>
    <div class="prime-forum-topics-modern">
        <span class="prime-forum-count" ><span><?php pfm_the_post_count(); ?></span><p><?php _e('Messages','wp-recall'); ?></p></span>
    </div>
</div>
