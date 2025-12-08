<?php

declare(strict_types=1);

namespace Drupal\farm_form\Form;

use Drupal\Core\Cache\CacheTagsInvalidatorInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Config\TypedConfigManagerInterface;
use Drupal\Core\DependencyInjection\AutowireTrait;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a settings form for the form protection functionality added by the farm_form module.
 */
class FormProtectionSettingsForm extends ConfigFormbase {
  use AutowireTrait;

  /**
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'farm_form.settings';

  public function __construct(
    ConfigFactoryInterface $config_factory,
    TypedConfigManagerInterface $typed_config_manager,
    protected CacheTagsInvalidatorInterface $cacheTagsInvalidator,
  ) {
    parent::__construct($config_factory, $typed_config_manager);
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'farm_form_protection_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      static::SETTINGS,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateinterface $form_state) {
    $config = $this->config(static::SETTINGS);

    $form['enable_form_protection'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable form protection for entity forms and quick form'),
      '#description' => $this->t('Display warning when users attempt to navigate away from forms with unsaved changes.'),
      '#default_value' => $config->get('enable_form_protection'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->configFactory->getEditable(static::SETTINGS)
      ->set('enable_form_protection', $form_state->getValue('enable_form_protection'))
      ->save();

    parent::submitForm($form, $form_state);
  }
}
