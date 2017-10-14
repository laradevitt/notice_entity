<?php

namespace Drupal\notice_entity\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;

/**
 * Class NoticeCloneController.
 */
class NoticeCloneController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Provides the notice clone submission form.
   *
   * @param $id
   *   (string) An entity id.
   *
   * @return array
   *   A notice clone submission form.
   */
  public function cloneForm($id) {

    $user = \Drupal::currentUser();

    // Load entity from entity ID.
    $controller = \Drupal::entityManager()->getStorage('notice');
    $entity = $controller->load($id);

    $clone = $entity->createDuplicate();

    $clone->created = time();
    $clone->uid = $user->id();

    // Build the form.
    $form = $this->entityFormBuilder()->getForm($clone);

    return $form;
  }

  /**
   * The _title_callback for the entity.notice.clone_form route.
   *
   * @param $id
   *   (string) An entity id.
   *
   * @return string
   *   The page title.
   */
  public function clonePageTitle($id) {
    // Load entity from entity ID.
    $controller = \Drupal::entityManager()->getStorage('notice');
    $entity = $controller->load($id);

    return $this->t('Clone notice <em>@title</em>', ['@title' => $entity->getName()]);
  }

}
