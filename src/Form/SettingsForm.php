<?php

namespace Drupal\node_view_mode\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure Node View Mode settings for this site.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'node_view_mode_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['node_view_mode.settings'];
  }

  /**
   * Get the view mode options.
   *
   * @return array
   *   An array of node view modes.
   */
  private function getOptions() {
    /** @var \Drupal\Core\Entity\EntityDisplayRepository $entity_display_repository */
    $entity_display_repository = \Drupal::service('entity_display.repository');
    $view_modes = $entity_display_repository->getViewModes('node');
    $options = [];
    foreach ($view_modes as $view_mode_id => $view_mode) {
      $options[$view_mode_id] = $view_mode['label'];
    }
    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);
    $node_types = \Drupal::entityTypeManager()
      ->getStorage('node_type')
      ->loadMultiple();
    foreach ($node_types as $node_type) {
      $form['view_mode_' . $node_type->id()] = [
        '#type' => 'select',
        '#title' => $this->t('@bundle view mode', ['@bundle' => $node_type->label()]),
        '#options' => $this->getOptions(),
        '#default_value' => $this->config('node_view_mode.settings')->get('view_mode_' . $node_type->id()),
      ];
    }
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $node_types = \Drupal::entityTypeManager()
      ->getStorage('node_type')
      ->loadMultiple();
    foreach ($node_types as $node_type) {
      $this->config('node_view_mode.settings')
        ->set('view_mode_' . $node_type->id() , $form_state->getValue('view_mode_' . $node_type->id()))
        ->save();
    }
    parent::submitForm($form, $form_state);
  }

}
