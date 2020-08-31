<?php

namespace Drupal\products;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedTrait;

/**
 * Provides an interface defining a product entity type.
 */
interface ProductInterface extends ContentEntityInterface {
  /**
   * Gets the product name.
   *
   * @return string
   *   Name of the product.
   */
  public function getName();

  /**
   * Sets the product name.
   *
   * @param string $name
   *   The product name.
   *
   * @return \Drupal\products\ProductInterface
   *   The called product entity.
   */
  public function setName(string $name);

  /**
   * Gets the product number.
   *
   * @return int
   *   Product number.
   */
  public function getProductNumber();

  /**
   * Sets the product number.
   *
   * @param int $number
   *   The product number.
   *
   * @return \Drupal\products\ProductInterface
   *   The called product entity.
   */
  public function setProductNumber(int $number);

  /**
   * Gets the remote id.
   *
   * @return string
   *   Name remote id.
   */
  public function getRemoteId();

  /**
   * Sets the remote id.
   *
   * @param string $id
   *   The remote id.
   *
   * @return \Drupal\products\ProductInterface
   *   The called product entity.
   */
  public function setRemoteId(string $id);

  /**
   * Gets the product source.
   *
   * @return string
   *   Product source.
   */
  public function getSource();

  /**
   * Sets the product source.
   *
   * @param string $source
   *   The product source.
   *
   * @return \Drupal\products\ProductInterface
   *   The called product entity.
   */
  public function setSource(string $source);

  /**
   * Gets the creation timestamp.
   *
   * @return int
   *   Creation timestamp.
   */
  public function getCreatedTime();

  /**
   * Sets the product creation timestamp.
   *
   * @param int $timestamp
   *   The product name.
   *
   * @return \Drupal\products\ProductInterface
   *   The called product entity.
   */
  public function setCreatedTime(int $timestamp);

}
