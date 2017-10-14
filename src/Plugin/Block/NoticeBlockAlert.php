<?php

namespace Drupal\notice_entity\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'NoticeBlockAlert' block.
 *
 * @Block(
 *  id = "notice_block_alert",
 *  category = @Translation("Notices"),
 *  admin_label = @Translation("Notice alerts"),
 * )
 */
class NoticeBlockAlert extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

    $build = [];

    if (\Drupal::currentUser()->hasPermission('view published notice entities')) {

      // Build a basic render array so that we can render our alert template.
      // We'll add a wrapper to it so that we can manipulate its contents with
      // JavaScript. Since JS is providing the data we don't care if what is
      // being output here is cached.
      $template = ['#theme' => 'notice_block_alert'];
      $renderer = \Drupal::service('renderer')->renderPlain($template);
      $html = '<div class="notice-alerts" style="display:none;">' . $renderer . '</div>';

      $build = [
        '#type' => 'inline_template',
        '#template' => $html,
        '#weight' => 1000,
      ];

      $build['#attached'] = array(
        'library' => array('notice_entity/notice-entity-alerts'),
      );
    }
    return $build;
  }

}
