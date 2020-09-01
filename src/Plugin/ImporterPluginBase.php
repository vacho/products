<?php
namespace Drupal\products\Plugin;

use Drupal\Component\Plugin\Exception\PluginException;
use Drupal\Component\Plugin\PluginBase;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\products\Plugin\ImporterPluginInterface;
use GuzzleHttp\Client;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class ImporterPluginBase extends PluginBase implements ImporterPluginInterface, ContainerFactoryPluginInterface {

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

    $this->entityTypeManager = $httpClient;
    if (!isset($configuration['config'])) {
      throw new PluginException('Missing importer configuration');
    }
    if (!$configuration['config'] instanceof ImporterPluginInterface) {
      throw new PluginException('Wrong importer configuration');
    }

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
      $container->get('htttp_client')
    );
  }

}
