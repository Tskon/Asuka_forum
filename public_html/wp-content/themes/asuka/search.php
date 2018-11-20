<?php
/**
 * The template for displaying search results pages.
 */

get_header(); ?>

<section>
  <main>

    <?php if (have_posts()) : ?>

      <header>
        <h1><?php printf('Результаты поиска для: %s', get_search_query()); ?></h1>
      </header>

      <?php
      // Start the loop.
      while (have_posts()) : the_post(); ?>

        <?php
        /*
         * Run the loop for the search to output the results.
         * If you want to overload this in a child theme then include a file
         * called content-search.php and that will be used instead.
         */
        get_template_part('content', 'search');

        // End the loop.
      endwhile;

      // Previous/next page navigation.
      the_posts_pagination(array(
        'prev_text' => 'Предыдущая',
        'next_text' => 'Следующая',
        'before_page_number' => '<span>Страница</span>',
      ));

    // If no content, include the "No posts found" template.
    else :
      get_template_part('content', 'none');

    endif;
    ?>

  </main>
</section>

<?php get_footer(); ?>
