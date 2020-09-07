<?php
namespace Drupal\products\Plugin\Importer;

use Drupal\Core\Form\FormStateInterface;
use Drupal\products\Plugin\ImporterPluginBase;

/**
 * Class JsonImporter
 *
 * @Importer(
 *   id = "json",
 *   label = @Translation("JSON Importer")
 * )
 */
class JsonImporter extends ImporterPluginBase {

  /**
   * {@inheritDoc}
   */
  public function defaultConfiguration() {
    return [
      'url' => ''
    ];
  }

  /**
   * {@inheritDoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form['url'] = [
      '#type' => 'url',
      '#default_value' => $this->configuration['url'],
      '#title' => $this->t('Url'),
      '#description' => $this->t('The url to the import resource'),
      '#required' => TRUE,
    ];
    return $form;
  }

  /**
   * {@inheritDoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    $this->configuration['url'] = $form_state->getValue('url');
  }

  /**
   * {@inheritDoc}
   */
  public function import() {
    $data = $this->getData();
    if (!$data) {
      return FALSE;
    }
    if (!isset($data->products)) {
      return FALSE;
    }
    $products = $data->products;
    foreach ($products as $product) {
      $this->persistProduct($product);
    }
    return TRUE;
  }

  /**
   * Loads the product data fron the remote URL.
   *
   * @return \stdClass
   */
  public function getData() {
    $request = $this->httpClient->get($this->configuration['url']);
    $string = $request->getBody()->getContents();
    return json_decode($string);
  }

  /**
   * Saves a Product entity from the remote data.
   *
   * @param $data
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  private function persistProduct($data) {
    /** @var \Drupal\products\Entity\ImporterInterface $config */
    $config = $this->configuration['config'];
    $existing = $this->entityTypeManager->getStorage('product')
      ->loadByProperties([
        'remote_id' => $data->id,
        'source' => $config->getSource()
      ]);

    // Create.
    if (!$existing) {
      $values = [
        'remote_id' => $data->id,
        'source' => $config->getSource()
      ];
      /** @var \Drupal\products\Entity\ProductInterface $product */
      $product = $this->entityTypeManager->getStorage('product')
        ->create($values);
      $product->setName($data->name);
      $product->setProductNumber($data->number);
      $product->save();
      return;
    }
    else {
      // Update.
      if (!$config->updateExisting()) {
        return;
      }
      /** @var \Drupal\products\Entity\ProductInterface $product */
      $product = reset($existing);
      $product->setName($data->name);
      $product->setProductNumber($data->number);
      $product->save();
    }
  }

}
