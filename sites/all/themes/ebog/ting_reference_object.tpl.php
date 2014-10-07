<?php

/**
 * @file
 * Template to render a ting object for ting references.
 */
?>
<div class="ting-references-object line rulerafter">
  <div class="picture unit grid-2 alpha">
    <?php if (strpos($cover, 'imagecache')): ?>
      <div class="inner left" style="margin-bottom:10px;">
        <?php print theme('image', $cover, $object->title, $object->title, NULL, FALSE); ?>
      </div>
    <?php else: ?>
      <div class="inner left nopicture" style="height:270px;margin-bottom:10px;">
        <?php print theme('image', $cover, $object->title, $object->title, NULL, FALSE); ?>
      </div>
    <?php endif;?>
  </div>
  <div class="meta unit grid-8 omega">
    <div class="inner">
      <h1 class="book-title"><?php print l(check_plain($object->record['dc:title'][''][0]), $object->url); ?></h1>
      <div class="author"><?php echo t('By !author', array('!author' => $author)); ?></div>

      <?php if (!empty($description)): ?>
        <div class="abstract">
          <p><?php print $description; ?></p>
        </div>
      <?php endif; ?>
      <?php if (isset($object->publisherDescription)): ?>
        <div class="publisherDescription">
          <?php if (isset($object->abstract) && $object->abstract): ?>
            <h2><?php echo t('Publisher description'); ?></h2>
          <?php endif; ?>
          <?php echo $object->publisherDescription; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

