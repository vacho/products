<?php
namespace Drupal\products\Plugin;

use Drupal\Component\Plugin\PluginInspectionInterface;

/**
 * Interface ImporterPluginInterface
 *
 * @package Drupal\product\Plugin
 */
interface ImporterPluginInterface extends PluginInspectionInterface {

  /**
   * Perform the import. Returns TRUE if the import was successful or FALSE otherwise.
   *
   * @return mixed
   */
  public function import();
}
