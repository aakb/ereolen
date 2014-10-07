<?php
/**
 * @file ting_object.tpl.php
 *
 * Template to render objects from the Ting database.
 *
 * Available variables:
 * - $object: The TingClientObject instance we're rendering.
 */

if (module_exists('ding_voxb')) {
  drupal_add_css(DING_VOXB_PATH . '/css/voxb-pager.css');
  drupal_add_css(DING_VOXB_PATH . '/css/voxb.css');
  drupal_add_css(DING_VOXB_PATH . '/css/jqModal.css');
  drupal_add_js(DING_VOXB_PATH . '/js/jqModal.js');
  drupal_add_js(DING_VOXB_PATH . '/js/livequery.js');
  drupal_add_js(DING_VOXB_PATH . '/js/cyclic.fade.js');
  drupal_add_js(DING_VOXB_PATH . '/js/voxb.item.js');

  require_once DING_VOXB_PATH . '/lib/VoxbItem.class.php';
  require_once DING_VOXB_PATH . '/lib/VoxbProfile.class.php';
  require_once DING_VOXB_PATH . '/lib/VoxbReviews.class.php';

  $faust_number = $object->localId;
  $voxb_item = new VoxbItem();
  $voxb_item->addReviewHandler('review', new VoxbReviews());
  $voxb_item->fetchByFaust($faust_number);
  $profile = unserialize($_SESSION['voxb']['profile']);
}
?>

<!-- ting_object.tpl -->
<div id="ting-object" class="line rulerafter">

  <div class="picture unit grid-3 alpha">
    <?php if (strpos($cover, 'imagecache')): ?>
      <div class="inner left" style="margin-bottom:10px;">
        <?php print theme('image', $cover, $object->title, $object->title, null, false); ?>
      </div>
    <?php else: ?>
      <div class="inner left nopicture" style="height:270px;margin-bottom:10px;">
        <?php print theme('image', $cover, $object->title, $object->title, null, false); ?>
      </div>
    <?php endif;?>
  </div>
  <div class="meta unit grid-8 omega">
    <div class="inner">
      <h1 class="book-title"><?php print check_plain($object->record['dc:title'][''][0]); ?></h1>
      <div class="author"><?php echo t('By !author', array('!author' => $author)); ?></div>
      <?php
      if (module_exists('ding_voxb')) {
        print($object->voxb_rating);
      }
       ?>
      <div class="facebook-like">
        <iframe src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fereolen.dk%2Fting%2Fobject%2F<?php echo $object->id; ?>&amp;send=false&amp;layout=box_count&amp;width=130&amp;show_faces=false&amp;action=recommend&amp;colorscheme=light&amp;font&amp;height=75" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:130px; height:75px;" allowTransparency="true"></iframe>
      </div>
      <?php if (isset($object->abstract) && $object->abstract) { ?>
        <div class="abstract">
          <p><?php print $object->abstract; ?></p>
        </div>
      <?php } ?>
      <?php if (isset($object->publisherDescription)) { ?>
        <div class="publisherDescription">
          <?php if (isset($object->abstract) && $object->abstract) { ?>
            <h2><?php echo t('Publisher description'); ?></h2>
          <?php } ?>
          <?php echo $object->publisherDescription; ?>
        </div>
      <?php } ?>
      <div class="details">
        <div class="left grid-4 omega alpha">
          <?php if (!empty($object->language)) { ?>
            <?php print theme('item_list', array($object->language), t('Language') . ':&nbsp;', 'span', array('class' => 'language'));?>
          <?php } ?>
          <?php if (!empty($object->record['dkdcplus:version'][''])) { ?>
            <?php print theme('item_list', $object->record['dkdcplus:version'][''], t('Version') . ':&nbsp;', 'span', array('class' => 'version'));?>
          <?php } ?>
          <?php if (!empty($object->subjects)) { ?>
            <?php print theme('item_list', $object->subjects, t('Subjects') . ':&nbsp;', 'ul', array('class' => 'subject'));?>
          <?php } ?>
        </div>
        <div class="right grid-4 omega alpha">
          <?php if (!empty($object->record['dc:source'][''])) { ?>
            <?php print theme('item_list', $object->record['dc:source'][''], t('Original title') . ':&nbsp;', 'span', array('class' => 'titles'));?>
          <?php } ?>
          <?php if (!empty($object->record['dc:identifier']['oss:PROVIDER-ID'])) { ?>
            <?php print theme('item_list', $object->record['dc:identifier']['oss:PROVIDER-ID'], t('ISBN') . ':&nbsp;', 'span', array('class' => 'identifier'));?>
          <?php } ?>
          <?php if (!empty($elib_format)) { ?>
            <?php print theme('item_list', array($elib_format), t('Format') . ':&nbsp;', 'span', array('class' => 'format')); ?>
          <?php } ?>
          <?php if (!empty($object->record['dc:publisher'][''])) { ?>
            <?php print theme('item_list', $object->record['dc:publisher'][''], t('Publisher') . ':&nbsp;', 'span', array('class' => 'publisher'));?>
          <?php } ?>
        </div>

        <div class="various">
          <?php print theme('item_list', array($object->type), t('Type') . ':&nbsp;', 'span', array('class' => 'type')); ?>
          <?php if (!empty($object->record['dc:format'][''])) { ?>
            <?php print theme('item_list', $object->record['dc:format'][''], t('Format'), 'span', array('class' => 'format'));?>
          <?php } ?>
          <?php if (!empty($object->record['dcterms:isPartOf'][''])) { ?>
            <?php print theme('item_list', $object->record['dcterms:isPartOf'][''], t('Available in'), 'span', array('class' => 'is-part-of'));?>
          <?php } ?>
          <?php if (!empty($object->record['dc:language']['oss:spoken'])) { ?>
            <?php print theme('item_list', $object->record['dc:language']['oss:spoken'], t('Speech'), 'span', array('class' => 'language'));?>
          <?php } ?>

          <?php if (!empty($object->record['dc:language']['oss:subtitles'])) { ?>
            <?php print theme('item_list', $object->record['dc:language']['oss:subtitles'], t('Subtitles'), 'span', array('class' => 'language'));?>
          <?php } ?>

          <?php if (!empty($object->record['dc:subject']['oss:genre'])) { ?>
            <?php print theme('item_list', $object->record['dc:subject']['oss:genre'], t('Genre'), 'span', array('class' => 'subject'));?>
          <?php } ?>
          <?php if (!empty($object->record['dcterms:spatial'][''])) { ?>
            <?php print theme('item_list', $object->record['dcterms:spatial'][''], NULL, 'span', array('class' => 'spatial')); ?>
          <?php } ?>
          <?php if (!empty($object->record['dc:contributor']['oss:dkind'])) { ?>
            <?php foreach($object->record['dc:contributor']['oss:dkind'] as $reader): ?>
              <?php $readers[] = l($reader,'ting/search/"' . $reader . '"'); ?>
            <?php endforeach;?>
            <?php print theme('item_list', $readers, t('Reader'), 'span', array('class' => 'contributor'));?>
          <?php } ?>
          <?php if (!empty($object->record['dc:contributor']['oss:act'])) { ?>
            <?php print theme('item_list', $object->record['dc:contributor']['oss:act'], t('Actor'), 'span', array('class' => 'contributor'));?>
          <?php } ?>
          <?php if (!empty($object->record['dc:contributor']['oss:mus'])) { ?>
            <?php print theme('item_list', $object->record['dc:contributor']['oss:mus'], t('Musician'), 'span', array('class' => 'contributor'));?>
          <?php } ?>

          <?php if (!empty($object->record['dcterms:hasPart']['oss:track'])) { ?>
            <?php print theme('item_list', $object->record['dcterms:hasPart']['oss:track'], t('Contains'), 'span', array('class' => 'contains'));?>
          <?php } ?>

          <?php if (!empty($object->record['dcterms:isReferencedBy'][''])) { ?>
            <?php print theme('item_list', $object->record['dcterms:isReferencedBy'][''], t('Referenced by'), 'span', array('class' => 'referenced-by'));?>
          <?php } ?>
          <?php if (!empty($object->record['dc:description'])) { ?>
            <?php foreach ($object->record['dc:description'] as $type => $dc_description) { ?>
            <?php } ?>
          <?php } ?>
          <?php if (!empty($object->record['dcterms:replaces'][''])) { ?>
            <?php print theme('item_list', $object->record['dcterms:replaces'][''], t('Previous title'), 'span', array('class' => 'titles'));?>
          <?php } ?>
          <?php if (!empty($object->record['dcterms:isReplacedBy'][''])) { ?>
            <?php print theme('item_list', $object->record['dcterms:isReplacedBy'][''], t('Later title'), 'span', array('class' => 'titles'));?>
          <?php } ?>
          <?php
            if (!empty($object->record['dc:identifier']['dcterms:URI'])) {
              $uris = array();
              foreach ($object->record['dc:identifier']['dcterms:URI'] as $uri) {
                $uris[] = l($uri, $uri);
              }
            }
          ?>
          <?php if (!empty($object->record['dcterms:extent'][''])) { ?>
            <?php print theme('item_list', $object->record['dcterms:extent'][''], t('Extent'), 'span', array('class' => 'version'));?>
          <?php } ?>
          <?php if (!empty($object->record['dc:rights'][''])) { ?>
            <?php print theme('item_list', $object->record['dc:rights'][''], t('Rights'), 'span', array('class' => 'rights'));?>
          <?php } ?>
        </div>
      </div>
      <?php if (isset($is_loan) && $is_loan) { ?>
      <span class="off-line" rel="<?php print $object->record['dc:identifier']['oss:PROVIDER-ID'][0]; ?>"></span>
      <?php } ?>
      <div class="icons">
        <ul>
          <?php if (isset($elib_sample_link)) { ?>
            <li><?php print l(t('Sample'), $elib_sample_link, array('html' => true, 'attributes' => array('action' => 'sample', 'target' => '_blank'))) ?></li>
            <li class="seperator"></li>
            <?php if (isset($is_loan) && $is_loan) {
                    $query = array(
                      'cvo' => $cvo,
                      'title' => $object->record['dc:title'][''][0],
                      'author' => publizon_get_authors($object, FALSE),
                  );
            ?>
            <li><?php print l(t('Read'), 'stream/' . $object->record['dc:identifier']['oss:PROVIDER-ID'][0], array('query' => array($query), 'html' => true, 'attributes' => array('class' => 'cvo', 'target' => '_blank'))) ?></li>
          <?php } else { ?>
              <li><?php print l(t('Borrow'), 'publizon/' . $object->record['dc:identifier']['oss:PROVIDER-ID'][0] . '/stream', array('html' => true, 'attributes' => array('class' => 'ting-object-loan', 'action' => 'stream', 'target' => '_blank'))) ?></li>
            <?php } ?>
            <?php
              if(user_is_logged_in()){
                print '<li class="seperator"></li>';
                print '<li>';
                print recall_list_add_link($object->record['dc:identifier']['oss:PROVIDER-ID'][0]);
                print '</li>';
              }
            }
            else {
            ?>
              <li class="unavailable"><span><?php echo t('Unavailable') ?></span></li>
            <?php
            }
          ?>
         </ul>
      </div>
      <?php
      if (module_exists('ding_voxb')) {
        echo $object->voxb_reviews;
      }
      ?>
    </div>
  </div>
 </div>
<?php if (module_exists('ding_voxb')) { ?>
  <div class="jqmWindow" id="dialog">
    <a href="#" class="jqmClose">Close</a>
    <hr>
    <p class="ajax_message">Service unavailable.</p>
  </div>
  <span class="ajaxLoaderTpl"><img src="/<?php echo DING_VOXB_PATH . '/img/ajax-loader.gif'; ?>" alt="in progress.." class="ajax_anim" /></span>
<?php } ?>
