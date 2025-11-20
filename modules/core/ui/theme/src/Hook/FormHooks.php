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
  }

  /**
   * Implements hook_form_BASE_FORM_ID_alter().
   */
  #[Hook('form_quick_form_alter')]
  public function formQuickFormAlter(&$form, FormStateInterface $form_state, $form_id) {
    $form['#attached']['library'][] = 'farm_ui_theme/quick';
  }

  /**
   * Implements hook_form_alter()
   */
  #[Hook('form_alter')]
  public function formAlter(&$form, FormStateInterface $form_state, $form_id) {
    $allowed_form_id_list = ["add_form", "edit_form", "quick_form"];
    $add_to_form = false;
    foreach ($allowed_form_id_list as $allowed_form_id) {
      $add_to_form = str_contains($form_id, $allowed_form_id);
    }
    if ($add_to_form) {
      $form['#attached']['library'][] = 'farm_ui_theme/form_protection';
    }
  }
}
