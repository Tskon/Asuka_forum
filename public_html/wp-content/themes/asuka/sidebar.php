<main class="indexPage">

  <div id="jsAsukaSlider"></div>

  <?php
  if (is_active_sidebar('index_widgets')) : ?>
    <div class="contentBlocks">
      <?php dynamic_sidebar('index_widgets'); ?>
    </div>
  <?php endif; ?>
</main>
