<?php

/**
 * @file
 * Contains \Drupal\products\Controller\RunPluginController.
 */

namespace Drupal\products\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class NombreControlador.
 *
 * @package Drupal\mimodulo\Controller
 */

class RunPluginController extends ControllerBase {

  public function run() {
    $config = \Drupal::entityTypeManager()->getStorage('importer')
      ->load('plugin_to_import');
    $plugin = \Drupal::service('products.importer_manager')
      ->createInstance($config->getPluginId(), ['config' => $config]);
    $plugin->import();

    return [
      '#markup' => 'Import performed',
    ];
  }

}
