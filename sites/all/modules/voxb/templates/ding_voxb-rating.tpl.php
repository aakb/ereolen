<?php
/**
 * @file
 * Template for rating form.
 */
?>
<div class="voxb">
  <div class="ratingsContainer">
      <div class="addRatingContainer">
        <div id="<?php echo $data['faust_number']; ?>"<?php echo $data['able'] ? ' class="userRate"' : ''; ?>>
          <?php for ($i = 1; $i <= 5; $i++) : ?>
          <div class="rating <?php if ($data['rating'] >= $i) : ?>star-on<?php ;endif ?>"></div>
          <?php ;endfor ?>
        </div>
      </div>
      <?php
      if ($data['rating_count'] > 0) {
        echo '<span class="ratingCountSpan">(<span class="ratingVotesNumber">' . $data['rating_count'] . '</span> ' . t('Rates') . ')</span>';
      }
      ?>
      <img src="/<?php echo DING_VOXB_PATH . '/img/ajax-loader.gif'; ?>" alt="" class="ajax_anim" />
  </div>
</div>
