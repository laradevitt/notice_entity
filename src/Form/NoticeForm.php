<?php

namespace Drupal\notice_entity\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\scheduled_updates\Entity\ScheduledUpdate;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;

/**
 * Form controller for Notice edit forms.
 *
 * @ingroup notice_entity
 */
class NoticeForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\notice_entity\Entity\Notice */
    $form = parent::buildForm($form, $form_state);

    $entity = $this->entity;

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = &$this->entity;

    $status = parent::save($form, $form_state);

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Notice.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Notice.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.notice.collection');
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);

  }
}
