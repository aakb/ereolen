<?php
// $Id$

/**
 * @file
 * Template to render a Ting collection of books.
 */

module_load_include('isbn_static_func.inc', 'elib');
foreach ($collection->objects as $obj) {
  $isbn = $obj->record['dc:identifier']['oss:PROVIDER-ID'][0];
  $alttext = t('@titel af @forfatter',array('@titel' => $obj->title, '@forfatter' => $obj->creators_string));
?>

  <li class="display-book ting-collection ruler-after line clear-block" id="<?php print $obj->id ?>">

    <div class="picture">
      <?php if ($elib[$isbn]['elib_book_cover']) { ?>
        <?php print l(theme('image', $elib[$isbn]['elib_book_cover'], $alttext, $alttext, null, false), $obj->url, array('html' => true)); ?>
      <?php } ?>
    </div>

    <div class="record">
      <div class="left">
        <h3>
          <?php print l($obj->title, $obj->url, array('attributes' => array('class' =>'title'))) ;?>
        </h3>
        <div class="meta">
          <?php if (isset($elib[$isbn]['author'])) : ?>
            <span class="creator">
              <?php echo t('By !creator_name', array('!creator_name' => $elib[$isbn]['author'])); ?>
            </span>
          <?php endif; ?>
          <div id="<?php print $obj->objects[0]->localId ?>"></div>
          <?php if ($obj->date) : ?>
            <span class="publication_date">
              <?php echo t('(%publication_date%)', array('%publication_date%' => $obj->date)) /* TODO: Improve date handling, localizations etc. */ ?>
            </span>
          <?php endif; ?>
        </div>
        <div class="rating-for-faust">
          <div class="<?php print $obj->localId; ?>"></div>
        </div>
        <?php if ($obj->subjects) : ?>
          <div class="subjects">
            <h4><?php echo t('Subjects:') ?></h4>
            <ul>
              <?php foreach ($obj->subjects as $subject) : ?>
                <li><?php echo $subject ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>
      </div>
      <div class="right">
        <?php if ($obj->abstract) : ?>
          <div class="abstract">
            <p>
              <?php print drupal_substr(check_plain($obj->abstract), 0, 200) . '...'; ?>
            </p>
          </div>
        <?php endif; ?>
        <div class="icons">
          <ul>
            <?php
              if (isset($elib[$isbn]['elib_sample_link'])) {
            ?>
              <li><?php print l(t('Sample'), $elib[$isbn]['elib_sample_link'], array('html' => true, 'attributes' => array('target' => '_blank','action' => 'sample'))) ?></li>
              <li class="seperator"></li>
              <li><?php print l(t('Loan'), $obj->url.'/download', array('html' => true, 'attributes' => array('rel' => 'lightframe[|width:350px; height:120px;]'))) ?></li>
              <li class="seperator"></li>
              <li class="deactivated"><?php print l(t('Buy'), 'butik', array('html' => true, 'attributes' => array('rel' => 'lightframe')))?></li>
            <?php
              }
              else {
            ?>
              <li class="unavailable"><span><?php echo t('Unavailable') ?></span></li>
            <?php
              }
            ?>

          </ul>
        </div>
      </div>
    </div>

  </li>
<?php
}
?>