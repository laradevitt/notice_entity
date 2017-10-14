<?php

namespace Drupal\notice_entity;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Notice entity.
 *
 * @see \Drupal\notice_entity\Entity\Notice.
 */
class NoticeAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\notice_entity\Entity\NoticeInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished notice entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published notice entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit notice entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete notice entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add notice entities');
  }

}
