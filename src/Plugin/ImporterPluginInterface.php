<?php
namespace Drupal\products\Plugin;

use Drupal\Component\Plugin\ConfigurableInterface;
use Drupal\Component\Plugin\PluginInspectionInterface;
use Drupal\Core\Plugin\PluginFormInterface;

/**
 * Interface ImporterPluginInterface
 *
 * @package Drupal\product\Plugin
 */
interface ImporterPluginInterface extends PluginInspectionInterface, PluginFormInterface, ConfigurableInterface {

  /**
   * Perform the import. Returns TRUE if the import was successful or FALSE otherwise.
   *
   * @return mixed
   */
  public function import();
}
