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
    // Construct a plugin JsonImporter for plugin_to_import.
    $plugin = \Drupal::service('products.importer_manager')
      ->createInstanceFromConfig('plugin_to_import');
    // Import.
    $plugin->import();

    return [
      '#markup' => 'Import performed',
      '#cache' => array(
        'max-age' => 0,
      ),
    ];
  }

}
