<?php

namespace Drupal\notice_entity\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Notice entities.
 *
 * @ingroup notice_entity
 */
interface NoticeInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Notice date ranges.
   *
   * @return array
   *   An array containing the start and end dates.
   */
  public function getDateRanges();

  /**
   * Gets the Notice name.
   *
   * @return string
   *   Name of the Notice.
   */
  public function getName();

  /**
   * Sets the Notice name.
   *
   * @param string $name
   *   The Notice name.
   *
   * @return \Drupal\notice_entity\Entity\NoticeInterface
   *   The called Notice entity.
   */
  public function setName($name);

  /**
   * Gets the Notice creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Notice.
   */
  public function getCreatedTime();

  /**
   * Sets the Notice creation timestamp.
   *
   * @param int $timestamp
   *   The Notice creation timestamp.
   *
   * @return \Drupal\notice_entity\Entity\NoticeInterface
   *   The called Notice entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Notice published status indicator.
   *
   * Unpublished Notice are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Notice is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Notice.
   *
   * @param bool $published
   *   TRUE to set this Notice to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\notice_entity\Entity\NoticeInterface
   *   The called Notice entity.
   */
  public function setPublished($published);

}
