<?php
namespace Drupal\products\Plugin;

use Drupal\Component\Plugin\Exception\PluginException;
use Drupal\Component\Plugin\PluginBase;
use Drupal\Core\DependencyInjection\DependencySerializationTrait;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\products\Entity\Importer;
use GuzzleHttp\Client;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class ImporterPluginBase extends PluginBase implements ImporterPluginInterface, ContainerFactoryPluginInterface {
  use StringTranslationTrait, DependencySerializationTrait;
  /**
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  protected $entityTypeManager;

  /**
   * @var \GuzzleHttp\Client
   */
  protected $httpClient;

  /**
   * {@inheritDoc}
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    EntityTypeManager $entityTypeManager,
    Client $httpClient) {

    parent::__construct(
      $configuration,
      $plugin_id,
      $plugin_definition
    );
    $this->entityTypeManager = $entityTypeManager;
    $this->httpClient = $httpClient;
    if (!isset($configuration['config'])) {
      throw new PluginException('Missing importer configuration');
    }
    if (!$configuration['config'] instanceof Importer) {
      throw new PluginException('Wrong importer configuration');
    }
    $this->setConfiguration($configuration);
  }

  /**
   * {@inheritDoc}
   */
  public static function create(
    ContainerInterface $container,
    array $configuration,
    $plugin_id,
    $plugin_definition) {

    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
      $container->get('http_client')
    );
  }

  /**
   * {@inheritDoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    return [];
  }

  /**
   * {@inheritDoc}
   */
  public function validateConfigurationForm(array &$form, FormStateInterface $form_state) {
    // Do nothing by default.
  }

  /**
   * {@inheritDoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    // Do nothing by default.
  }

  /**
   * {@inheritDoc}
   */
  public function defaultConfiguration() {
    return [];
  }

  /**
   * {@inheritDoc}
   */
  public function getConfiguration() {
    return $this->configuration;
  }

  /**
   * {@inheritDoc}
   */
  public function setConfiguration(array $configuration) {
    $this->configuration = $configuration + $this->defaultConfiguration();
  }

}
