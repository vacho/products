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
    // Get the configuration for a saved importer configuration entity.
    $config = \Drupal::entityTypeManager()->getStorage('importer')
      ->load('plugin_to_import');
    // Construct a plugin JsonImporter for plugin_to_import.
    // createInstance is a factory method from PluginManagerBase.
    $plugin = \Drupal::service('products.importer_manager')
      ->createInstance($config->getPluginId(), ['config' => $config]);
    // Import.
    $plugin->import();

    return [
      '#markup' => 'Import performed',
    ];
  }

}
