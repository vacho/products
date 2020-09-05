<?php

namespace Drupal\products\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides an interface defining an importer entity type.
 */
interface ImporterInterface extends ConfigEntityInterface {

  /**
   * Returns the configuration specific to the chosen plugin.
   *
   * @return array
   */
  public function getPluginConfiguration();

  /**
   * Sets the plugin configuration.
   *
   * @param array $configuration
   *   The plugin configuration.
   */
  public function setPluginConfiguration(array $configuration);

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
