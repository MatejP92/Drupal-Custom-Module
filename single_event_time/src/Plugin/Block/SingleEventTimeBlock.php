<?php

namespace Drupal\single_event_time\Plugin\Block;

use Drupal;
use Drupal\Core\Block\BlockBase;


/**
* Provides a block with events time.
*
* @Block(
*   id = "single_event_time",
*   admin_label = @Translation("Single Event Time"),
*   category = "Single Event time"
* )
*/
class SingleEventTimeBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $route_match = Drupal::routeMatch();
    $node = $route_match->getParameter('node');
    if(!strpos($route_match->getRouteName(), "node")){
      return [];
    }
    $event = Drupal::service('single_event_time.single_event_time_service')->getEventTime($node);
    if (!empty($event)) {
      return [
        "#markup" => $event["time_message"]
      ];
    }
  }

   /**
   * {@inheritdoc}
   */
  public function getCacheMaxAge() {
    return 0;
  }
}
