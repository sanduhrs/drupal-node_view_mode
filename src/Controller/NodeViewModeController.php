<?php

namespace Drupal\node_view_mode\Controller;

use Drupal\Core\Entity\EntityInterface;
use Drupal\node\Controller\NodeViewController;

/**
 * Class NodeViewModeController.
 *
 * @package Drupal\node_view_mode\Controller
 */
class NodeViewModeController extends NodeViewController {

  /**
   * Builds the response.
   *
   * @param \Drupal\Core\Entity\EntityInterface $node
   *   The current node.
   * @param string $view_mode
   *   The current view mode.
   * @param string $langcode
   *   The current langcode.
   *
   * @return array
   *   A render array.
   */
  public function view(EntityInterface $node, $view_mode = 'full', $langcode = NULL) {
    $view_mode = \Drupal::config('node_view_mode.settings')
      ->get('view_mode_' . $node->bundle());
    $build = parent::view($node, $view_mode, $langcode);
    return $build;
  }

}
