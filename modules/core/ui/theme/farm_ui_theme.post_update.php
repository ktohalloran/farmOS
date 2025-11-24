<?php

/**
 * @file
 * Post update functions for farm_ui_theme module.
 */

declare(strict_types=1);

/**
 * Enable farm_form module.
 */
function farm_api_post_update_enable_farm_form(&$sandbox = NULL) {

  // Enable static scope module.
  if (!\Drupal::service('module_handler')->moduleExists('farm_form')) {
    \Drupal::service('module_installer')->install(['farm_form']);
  }

}