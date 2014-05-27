<?php

/**
 * Implementation of HOOK_theme().
 */
function ebog_theme(&$existing, $type, $theme, $path) {
  $hooks = omega_theme($existing, $type, $theme, $path);
  return $hooks;
}

/**
 * Implements hook_preprocess_ting_object().
 *
 * Add extra information form elib to the ting object.
 */
function ebog_preprocess_ting_object(&$vars) {
  $isbn = $vars['object']->record['dc:identifier']['oss:PROVIDER-ID'][0];

  // Override ting object page title.
  drupal_set_title(check_plain($vars['object']->title . ' ' . t('af') . ' ' . $vars['object']->creators_string));

  // Create the author field.
  $vars['author'] = publizon_get_authors($vars['object']);

  // Load the product.
  try {
    $product = new PublizonProduct($isbn);

    // Get cover image.
    $vars['cover'] = $product->getCover('170_x');

    // Get ebook sample link.
    if (!empty($product->teaser_link)) {
      $vars['elib_sample_link'] = 'stream/' . $isbn . '/preview/';
    }

    if ($product->getFormat()) {
      $vars['elib_format'] = $product->getFormat();
    }

    // Check if the book is loaned by the user.
    global $user;
    if ($user->uid > 0) {
      $user_loans = new PublizonUserLoans($user->uid);
      $vars['is_loan'] = $user_loans->isLoan($isbn, TRUE);
      if ($vars['is_loan']) {
        $vars['cvo'] = $user_loans->loans[$isbn]->internal_order_number;
      }
    }
  }
  catch (Exception $e) {
    drupal_set_message($e->getMessage(), 'error');
  }
}

/**
 * Implements hook_preprocess_ting_reference_object().
 *
 * Add extra information form elib to the ting object.
 */
function ebog_preprocess_ting_reference_object(&$vars) {
  $isbn = $vars['object']->record['dc:identifier']['oss:PROVIDER-ID'][0];

  // Override ting object page title.
  drupal_set_title(check_plain($vars['object']->title . ' ' . t('af') . ' ' . $vars['object']->creators_string));

  // Create the author field.
  $vars['author'] = publizon_get_authors($vars['object']);

  // Load the product.
  try {
    $product = new PublizonProduct($isbn);

    // Get cover image.
    $vars['cover'] = $product->getCover('120_x');

    // Get description.
    $vars['description'] = $product->description;
  }
  catch (Exception $e) {
    drupal_set_message($e->getMessage(), 'error');
  }
}

/**
 * Implements hook_preprocess_ting_search_collection().
 *
 * Add extra information from elib to the ting objects.
 */
function ebog_preprocess_ting_search_collection(&$vars) {
  foreach ($vars['collection']->objects as $obj) {
    $isbn = $obj->record['dc:identifier']['oss:PROVIDER-ID'][0];
    if (isset($vars['elib'])) {
      $vars['elib'][$isbn] = array();
    }
    else {
      $vars['elib'] = array();
    }

    // Get authors.
    $vars['elib'][$isbn]['author'] = publizon_get_authors($obj);

    try {
      $product = new PublizonProduct($isbn);

      // Get cover image.
      $vars['elib'][$isbn]['elib_book_cover'] = $product->getCover('170_x');

      // Get e-book sample link.
      if (!empty($product->teaser_link)) {
        $vars['elib'][$isbn]['elib_sample_link'] = 'stream/' . $isbn . '/preview/';
      }

      // Check if the book is loaned by the user.
      global $user;
      if ($user->uid > 0) {
        $user_loans = new PublizonUserLoans($user->uid);
        $vars['elib'][$isbn]['is_loan'] = $user_loans->isLoan($isbn, TRUE);
        if ($vars['elib'][$isbn]['is_loan']) {
          $vars['elib'][$isbn]['cvo'] = $user_loans->loans[$isbn]->internal_order_number;
        }
      }
    }
    catch (Exception $e) {
      drupal_set_message($e->getMessage(), 'error');
    }
  }
}

/**
 * Implements hook_preprocess_page().
 */
function ebog_preprocess_page(&$vars, $hook) {
  global $user;

  array_pop($vars['primary_links']);
  if ($user->uid != 0) {
    $vars['primary_links']['account-link'] = array(
      'href'  => 'min_side',
      'title' => t('Min side'),
    );
  }
  else {
    $vars['primary_links']['login-link'] = array(
      'href'  => 'user',
      'title' => t('Login'),
    );
  }

  $primary_links = theme('links', $vars['primary_links'], array('class' => 'menu'));
  $vars['navigation'] = '<div class="block block-menu" id="block-menu-primary-links"><div class="content">' . $primary_links . '</div></div>';

  if (arg(0) == 'min_side' && $user->uid == 0) {
    drupal_goto('user', drupal_get_destination());
  }

  if (arg(3) == 'stream' || arg(3) == 'download' || (isset($_GET['clean']) && $_GET['clean'] == 1)) {
    $vars['template_files'] = array('page-clean');
    $vars['css']['all']['theme']['sites/all/themes/ebog/css/style.css'] = FALSE;
  }
}


/**
 * Create a string of attributes form a provided array.
 *
 * @param $attributes
 * @return string
 */
function ebog_render_attributes($attributes) {
  return omega_render_attributes($attributes);
}
