<section class="no-results not-found">
	<header class="page-header">
		<h1 class="page-title">Ничего не найдено</h1>
	</header>
	<div class="page-content">
		<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>
			<p><?php printf( 'Готовы написать первый пост? <a href="%1$s">Начните здесь</a>.', esc_url( admin_url( 'post-new.php' ) ) ); ?></p>
		<?php elseif ( is_search() ) : ?>
			<p>По данным словам ничего не найдено.</p>
			<?php get_search_form(); ?>
		<?php else : ?>
			<p>Ничего не найдено, возможно поможет "поиск"</p>
			<?php get_search_form(); ?>
		<?php endif; ?>
	</div>
</section>
