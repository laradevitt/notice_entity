<?php

namespace Drupal\notice_entity;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Notice entities.
 *
 * @ingroup notice_entity
 */
class NoticeListBuilder extends EntityListBuilder {


  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['name'] = $this->t('Main text');
    $header['field_notice_text'] = $this->t('Summary');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\notice_entity\Entity\Notice */
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.notice.canonical',
      ['notice' => $entity->id()]
    );
    $row['field_notice_text'] = $entity->field_notice_text->value;
    return $row + parent::buildRow($entity);
  }

  /**
   * {@inheritdoc}
   */
  protected function getTitle() {
    // This doesn't seem to work.
    return $this->t('Notices');
  }
}
