<?php

namespace Drupal\products\Form;

use Drupal\Core\Entity\EntityConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * importer form.
 *
 * @property \Drupal\products\ImporterInterface $entity
 */
class ImporterDeleteForm extends EntityConfirmFormBase  {

  /**
   * ImporterDeleteForm constructor.
   *
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   */
  public function __construct(
    MessengerInterface $messenger
  ) {
    $this->messenger-> $messenger;
  }

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('messenger')
    );
  }

  /**
   * {@inheritDoc}
   */
  public function getQuestion() {
    return $this->t('Are you sure you want to delete %name?', ['%name' => $this->entity->label()]);
  }

  /**
   * {@inheritDoc}
   */
  function getCancelUrl() {
    return new Url('entity.importer.colllection');
  }

  /**
   * {@inheritDoc}
   */
  public function getConfirmText() {
    return $this->t('Delete');
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    /** @var \Drupal\products\Entity\Importer $importer */
    $importer = $this->entity;
    $importer->delete();
    $this->messenger->addMessage($this->t('Deleted %entity importer', ['%entity' => $importer->label()]));
    $form_state->setRedirectUrl($this->getCancelUrl());
  }

}
