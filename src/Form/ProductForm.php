<?php

namespace Drupal\products\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the product entity edit forms.
 */
class ProductForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {

    $entity = $this->entity;
    $status = parent::save($form, $form_state);

    if ($status == SAVED_NEW) {
      $this->messenger()->addMessage($this->t('New product has been created.'));
    }
    else {
      $this->messenger()->addStatus($this->t('The product has been updated.'));
    }

    $form_state->setRedirect('entity.product.canonical', ['product' => $entity->id()]);
  }

}
