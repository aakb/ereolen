<?php
/**
 * @file
 * Ebog buy module admin settings file.
 */

/**
 * Administrative settings form.
 *
 * @ingroup forms
 *
 * @see ebog_buy_admin_settings_form_validate()
 *
 * @return array
 *   Form structure.
 */
function ebog_buy_admin_settings_form() {
  $form = array();

  $form['ebog_buy_purchase_ws'] = array(
    '#type' => 'textfield',
    '#title' => t('Resellers list web-service link'),
    '#description' => t('For ex. http://purchase.api.pubhub.dk/purchasereference/v1/?isbn='),
    '#default_value' => variable_get('ebog_buy_purchase_ws', 'http://purchase.api.pubhub.dk/purchasereference/v1/?isbn='),
    '#required' => TRUE,
  );

  $form['ebog_buy_secret_code'] = array(
    '#type' => 'textfield',
    '#title' => t('Secret key for web-service access'),
    '#default_value' => variable_get('ebog_buy_secret_code', ''),
    '#required' => TRUE,
  );

  return system_settings_form($form);
}

/**
 * Custom validation for administrative settings form.
 *
 * @ingroup forms
 *
 * @see ebog_buy_admin_settings_form()
 */
function ebog_buy_admin_settings_form_validate(&$form, &$form_state) {
  if (!valid_url($form_state['values']['ebog_buy_purchase_ws'], TRUE)) {
    form_set_error('ebog_buy_purchase_ws', t('Check entered URL.'));
  }
}
