<?php

drupal_add_js(path_to_theme() . '/js/scroll.to.js');
drupal_add_js(path_to_theme() . '/js/modernizr.touch.js');
$vars['linked_logo_img'] = $vars['logo_img'] ? l($vars['logo_img'], '<front>', array('attributes' => array('alt' => t('To frontpage'), 'rel' => 'home', 'title' => t('To frontpage')), 'html' => TRUE)) : '';
