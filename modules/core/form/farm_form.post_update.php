<?php

/**
 * @file
 * Post update hooks for the farmOS Form module.
 */

declare(strict_types=1);

/**
 * Add new form protection configuration.
 */
function farm_form_post_update_add_form_protection_config() {
  $config_factory = \Drupal::configFactory();
  $config = $config_factory->getEditable('farm_form.settings');

  // Set new configuration values.
  $config->set('enable_form_protection', TRUE);

  // Save the configuration.
  $config->save();
}
