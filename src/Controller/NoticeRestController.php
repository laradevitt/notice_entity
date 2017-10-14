<?php

namespace Drupal\notice_entity\Controller;

use Drupal\Core\Cache\CacheableJsonResponse;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Datetime\DrupalDateTime;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\Query\QueryFactory;

/**
* Class NoticeRestController.
*/
class NoticeRestController extends ControllerBase {

  /**
  * Entity query factory.
  *
  * @var \Drupal\Core\Entity\Query\QueryFactory
  */
  protected $entityQuery;

  /**
  * Constructs a new CustomRestController object.
  *
  * @param \Drupal\Core\Entity\Query\QueryFactory $entityQuery
  * The entity query factory.
  */
  public function __construct(QueryFactory $entity_query) {
    $this->entityQuery = $entity_query;
  }

  /**
  * {@inheritdoc}
  */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity.query')
    );
  }

  /**
  * Return today's notices in a formatted JSON response.
  *
  * @return string $date
  *   The date, formatted as CCYYMMDD.
  *
  * @return \Symfony\Component\HttpFoundation\JsonResponse
  * The formatted JSON response.
  */
  public function getNoticesToday($date) {

    // Initialize the response array.
    $response_array = [];

    $result = \Drupal::entityQuery('notice')
      ->condition("field_notice_daterange.value", $date, '<=')
      ->condition("field_notice_daterange.end_value", $date, '>=')
      ->execute();

    if ($result) {
      $storage = \Drupal::entityManager()->getStorage('notice');
      $entities = $storage->loadMultiple($result);
      foreach ($entities as $entity) {
        // Loop through daterange field as there may be more than one value.
        $dates = [];
        $date_ranges = $entity->get('field_notice_daterange')->getValue();
        foreach ($date_ranges as $range) {
          if (isset($range['value']) && isset($range['end_value'])) {
            $dates[] = ['start' => $range['value'], 'end' => $range['end_value']];
          }
        }
        $response_array[] = [
          'id' => $entity->id(),
          'dates' => $dates,
          'name' => $entity->name->value,
          'text' => $entity->field_notice_text->value,
        ];
      }
    }
    else {
      $response_array = ['message' => 'No current notices.'];
    }

     // Create the JSON response object and make sure it isn't cached.
    $response = new CacheableJsonResponse($response_array);
    $response->addCacheableDependency(['#cache' => ['max-age' => 0]]);

    return $response;
  }

}