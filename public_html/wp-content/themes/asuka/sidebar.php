<main class="indexPage">
  <div class="news" id="jsAsukaNewsSlider">
    <div class="news__previewImg"></div>
    <div class="news__description" v-show="isNeedToShowNews">
      <h2>Заголовок новости</h2>
      Текст новости <br>
      Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusamus atque eveniet itaque nisi obcaecati
      quasi rem
      veritatis? Alias architecto ducimus id nihil numquam? Ad dolorem eum itaque repellat similique sunt?
    </div>
  </div>
  <?php
  if (is_active_sidebar('index_widgets')) : ?>
    <div class="contentBlocks">
      <?php dynamic_sidebar('index_widgets'); ?>
    </div>
  <?php endif; ?>
</main>


