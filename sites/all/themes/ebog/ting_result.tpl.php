<div id="ting-search-summary">
  <?php print t('Your search returned !count results',
    array(
      '!searchPhrase' => arg(2),
      '!firstResult' => '<span class="firstResult"></span>',
      '!lastResult' => '<span class="lastResult"></span>',
      '!count' => '<span class="count"></span>',
    )); ?>
</div>

<div id="ting-search-sort">
  <label for="edit-ting-search-sort">
    <?php print t('Sorted by'); ?>
  </label>
  <select id="edit-ting-search-sort">
    <?php foreach ($sort_options as $sort => $label) : ?>
      <?php print '<option value="' . $sort . '">' . check_plain($label) . '</option>'; ?>
    <?php endforeach; ?>
  </select>
</div>


<div id="ting-search-result">
	<ul>
	</ul>
</div>
