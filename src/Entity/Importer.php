<?php

namespace Drupal\products\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines the importer entity.
 *
 * @ConfigEntityType(
 *   id = "importer",
 *   label = @Translation("Importer"),
 *   handlers = {
 *     "list_builder" = "Drupal\products\ImporterListBuilder",
 *     "form" = {
 *       "add" = "Drupal\products\Form\ImporterForm",
 *       "edit" = "Drupal\products\Form\ImporterForm",
 *       "delete" = "Drupal\products\Form\ImporterDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "importer",
 *   admin_permission = "administer importer",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "add-form" = "/admin/structure/importer/add",
 *     "edit-form" = "/admin/structure/importer/{importer}/edit",
 *     "delete-form" = "/admin/structure/importer/{importer}/delete",
 *     "collection" = "/admin/structure/importer"
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "plugin",
 *     "plugin_configuration",
 *     "update_existing",
 *     "source"
 *   }
 * )
 */
class Importer extends ConfigEntityBase implements ImporterInterface  {

  /**
   * The importer ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The importer label.
   *
   * @var string
   */
  protected $label;

  /**
   *  The configuration specific to the plugin.
   *
   * @var array
   */
  protected $plugin_configuration = [];

  /**
   * The plugin ID of the plugin to be used for processing this import.
   *
   * @var string.
   */
  protected $plugin;

  /**
   * Whether or not to update existing products if they have already been imported.
   *
   * @var bool
   */
  protected $update_existing = TRUE;

  /**
   * The source of the products.
   *
   * @var string
   */
  protected $source;

  /**
   * {@inheritDoc}
   */
  public function getPluginId() {
    return $this->plugin;
  }

  /**
   * {@inheritDoc}
   */
  public function updateExisting() {
    return $this->update_existing;
  }

  /**
   * {@inheritDoc}
   */
  public function getPluginConfiguration() {
    return $this->plugin_configuration;
  }

  /**
   * {@inheritDoc}
   */
  public function setPluginConfiguration(array $configuration) {
    $this->plugin_configuration = $configuration;
  }

  /**
   * {@inheritDoc}
   */
  public function getSource() {
    return $this->source;
  }
}
