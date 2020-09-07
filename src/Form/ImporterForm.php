<?php

namespace Drupal\products\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\SubformState;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\products\Plugin\ImporterManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * importer form.
 *
 * @property \Drupal\products\ImporterInterface $entity
 */
class ImporterForm extends EntityForm {

  /**
   * @var \Drupal\products\Plugin\ImporterManager
   */
  protected $importerManager;

  /**
   * ImporterForm constructor.
   *
   * @param \Drupal\products\Plugin\ImporterManager $importerManager
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   */
  public function __construct(
    ImporterManager $importerManager,
    MessengerInterface $messenger
  ) {
    $this->importerManager = $importerManager;
    $this->messenger = $messenger;
  }

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('products.importer_manager'),
      $container->get('messenger')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {

    $form = parent::form($form, $form_state);
    /** @var \Drupal\products\Entity\Importer $importer */
    $importer = $this->entity;

    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $importer->label(),
      '#description' => $this->t('Name of the importer.'),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $importer->id(),
      '#machine_name' => [
        'exists' => '\Drupal\products\Entity\Importer::load',
      ],
      '#disabled' => !$importer->isNew(),
    ];

    $definitions = $this->importerManager->getDefinitions();
    $options = [];
    foreach ($definitions as $id => $definition) {
      $options[$id] = $definition['label'];
    }
    $form['plugin'] = [
      '#type' => 'select',
      '#title' => $this->t('Plugin'),
      '#default_value' => $importer->getPluginId(),
      '#options' => $options,
      '#description' => $this->t('The plugin to be used with this importer'),
      '#required' => TRUE,
      '#empty_option' => $this->t('Please select a plugin'),
      '#ajax' => [
        'callback' => [$this, 'pluginConfigAjaxCallback'],
        'wrapper' => 'plugin-configuration-wrapper'
      ],
    ];

    $form['plugin_configuration'] = [
      '#type' => 'hidden',
      '#attributes' => [
        'id' => 'plugin-configuration-wrapper'
      ],
      '#tree' => TRUE,
      '#open' => TRUE,
    ];
    $plugin_id = NULL;
    if ($importer->getPluginId()) {
      $plugin_id = $importer->getPluginId();
    }
    if ($form_state->getValue('plugin') && $plugin_id != $form_state->getValue('plugin')) {
      $plugin_id = $form_state->getValue('plugin');
    }
    if ($plugin_id) {
      $existing_config = [
       'config' => $importer
      ] + $importer->getPluginConfiguration();
      $plugin = $this->importerManager->createInstance($plugin_id, $existing_config);
      $form['plugin_configuration']['#type'] = 'details';
      $form['plugin_configuration']['#title'] = $this->t(
        'Plugin configuration for <em>@plugin</em>', [
          '@plugin' => $plugin->getPluginDefinition()['label']
        ]);
      $form['plugin_configuration'][$plugin_id] = [
        // Defer the building of the plugin subform to a process callback.
        '#process' => [[get_class($this), 'processPluginConfiguration']],
        '#plugin' => $plugin,
      ];
    }

    $form['update_existing'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Update existing'),
      '#description' => $this->t('Whether to update existing products if already imported'),
      '#default_value' => $importer->updateExisting(),
    ];

    $form['source'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Source'),
      '#maxlength' => 255,
      '#default_value' => $importer->label(),
      '#description' => $this->t('The source of the products.'),
    ];

    return $form;
  }

  /**
   * Ajax callback for the plugin configuration form elements.
   *
   * @param $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return array
   */
  public function pluginConfigAjaxCallback($form, FormStateInterface $form_state) {
    return $form['plugin_configuration'];
  }

  /**
   * Build the plugin subform.
   *
   * @param array $element
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return array
   */
  public static function processPluginConfiguration(array &$element, FormStateInterface $form_state) {
    /** @var \Drupal\products\Plugin\ImporterPluginInterface $plugin */
    $plugin = $element['#plugin'];
    $subform_state = SubformState::createForSubform(
      $element,
      $form_state->getCompleteForm(),
      $form_state);
    return $plugin->buildConfigurationForm($element, $subform_state);
  }

  /**
   * {@inheritDoc}
   */
  public function buildEntity(array $form, FormStateInterface $form_state) {
    if ($form_state->getValue('plugin_configuration') == "") {
      $form_state->setValue('plugin_configuration', []);
    }
    /** @var \Drupal\products\Entity\ImporterInterface $entity */
    $entity = parent::buildEntity($form, $form_state);
    $plugin_id = $form_state->getValue('plugin');
    if ($plugin_id) {
      $configuration = ['config' => $entity];
      $plugin_configuration = $form_state->getValue(['plugin_configuration', $plugin_id]);
      if ($plugin_configuration) {
        $configuration += $plugin_configuration;
      }
      /** @var \Drupal\Core\Plugin\PluginFormInterface $plugin */
      $plugin = $this->importerManager->createInstance($plugin_id, $configuration);
      if (isset($form['plugin_configuration'][$plugin_id])) {
        $subform_state = SubformState::createForSubform(
          $form['plugin_configuration'][$plugin_id],
          $form_state->getCompleteForm(),
          $form_state
        );
        $plugin->submitConfigurationForm(
          $form['plugin_configuration'][$plugin_id],
          $subform_state
        );
      }
      $configuration = $plugin->getConfiguration();
      unset($configuration['config']);
      $entity->setPluginConfiguration($configuration);
    }
    return $entity;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    /** @var \Drupal\products\Entity\Importer $importer */
    $importer = $this->entity;
    $status = $importer->save();
    switch ($status) {
      case SAVED_NEW:
        $this->messenger->addMessage($this->t('Created the %label Importer', ['%label' => $importer->label()]));
        break;
      default:
        $this->messenger->addMessage($this->t('Saved the %label Importer', ['%label' => $importer->label()]));
    }
    $form_state->setRedirectUrl($importer->toUrl('collection'));
  }

}
