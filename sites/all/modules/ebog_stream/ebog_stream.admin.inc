<?php
/**
 * @file
 * Ebog stream module admin settings file.
 */

/**
 * Administrative settings form.
 *
 * @ingroup forms
 *
 * @see ebog_stream_admin_settings_form_validate()
 *
 * @return array
 *   Form structure.
 */
function ebog_stream_admin_settings_form() {
  $form = array();

  $form['ebog_stream_api_key'] = array(
    '#type' => 'textfield',
    '#title' => t('API key for streaming'),
    '#default_value' => variable_get('ebog_stream_api_key', ''),
    '#required' => TRUE,
  );

  return system_settings_form($form);
}

/**
 * Custom validation for administrative settings form.
 *
 * @ingroup forms
 *
 * @see ebog_stream_admin_settings_form()
 */
function ebog_stream_admin_settings_form_validate(&$form, &$form_state) {
  if (!($form_state['values']['ebog_stream_api_key'])) {
    form_set_error('ebog_stream_api_key', t('Please supply API key.'));
  }
}
