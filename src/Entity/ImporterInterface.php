<?php

namespace Drupal\products\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;
use Drupal\Core\Url;
/**
 * Provides an interface defining an importer entity type.
 */
interface ImporterInterface extends ConfigEntityInterface {

  /**
   * Returns the url where the import can get the data from.
   *
   * @return Url
   */
  public function getUrl();

  /**
   * Returns the plugin ID to be used by this importer.
   *
   * @return string
   */
  public function getPluginId();

  /**
   * Whether or no the update existing products if they have already been imported.
   *
   * @return bool
   */
  public function updateExisting();

  /**
   * Returns the source of the products.
   *
   * @return string
   */
  public function getSource();

}
