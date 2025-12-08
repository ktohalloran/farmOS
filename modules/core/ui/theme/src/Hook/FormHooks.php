<?php

declare(strict_types=1);

namespace Drupal\farm_ui_theme\Hook;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Hook\Attribute\Hook;
use Drupal\farm_ui_theme\FarmUiThemeHelper;

/**
 * Form hook implementations for farm_ui_theme.
 */
class FormHooks {

  private function addFormProtection (&$form) {
    // Check that form protection setting is enabled.
    $config_factory = \Drupal::configFactory();
    $settings = $config_factory->get('farm_form.settings');
    $use_form_protection = $settings->get('enable_form_protection');

    // Add form protection library if enabled.
    if ($use_form_protection) {
      $form['#attributes']['class'][] = 'entity-or-quick-form-protected';
      $form['#attached']['library'][] = 'farm_form/form_protection';
    }
  }

  /**
   * Implements hook_form_BASE_FORM_ID_alter().
   */
  #[Hook('form_asset_form_alter')]
  public function formAssetFormAlter(&$form, FormStateInterface $form_state, $form_id) {
    /** @var \Drupal\Core\Entity\EntityForm $form_object */
    $form_object = $form_state->getFormObject();
    /** @var \Drupal\asset\Entity\AssetInterface $entity */
    $entity = $form_object->getEntity();
    FarmUiThemeHelper::setArchivedMessage($entity);

    $this->addFormProtection($form);
  }

  /**
   * Implements hook_form_BASE_FORM_ID_alter().
   */
  #[Hook('form_plan_form_alter')]
  public function formPlanFormAlter(&$form, FormStateInterface $form_state, $form_id) {
    /** @var \Drupal\Core\Entity\EntityForm $form_object */
    $form_object = $form_state->getFormObject();
    /** @var \Drupal\asset\Entity\AssetInterface $entity */
    $entity = $form_object->getEntity();
    FarmUiThemeHelper::setArchivedMessage($entity);

    $this->addFormProtection($form);
  }

  /**
   * Implements hook_form_BASE_FORM_ID_alter().
   */
  #[Hook('form_log_form_alter')]
  public function formLogFormAlter(&$form, FormStateInterface $form_state, $form_id) {
    $this->addFormProtection($form);
  }

  /**
   * Implements hook_form_BASE_FORM_ID_alter().
   */
  #[Hook('form_organization_form_alter')]
  public function formOrganizationFormAlter(&$form, FormStateInterface $form_state, $form_id) {
    $this->addFormProtection($form);
  }

  /**
   * Implements hook_form_BASE_FORM_ID_alter().
   */
  #[Hook('form_quick_form_alter')]
  public function formQuickFormAlter(&$form, FormStateInterface $form_state, $form_id) {
    $form['#attached']['library'][] = 'farm_ui_theme/quick';
  }

}
