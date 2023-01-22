<?php
namespace Drupal\single_event_time\Services;

use DateTime;
use DateTimeZone;
use Drupal;
use Drupal\node\NodeInterface;
use Drupal\Core\Datetime\DrupalDateTime;

class SingleEventTimeService{
  public function getEventTime($node = null){

    if(!($node instanceof NodeInterface) || $node->getType() != 'event') { return []; }
    $current_time = new DateTime();
    $timezone = new DateTimeZone(Drupal::config('system.date')->get('timezone')['default']);
    $event = [];
    if($node->field_date){
      $event_date = new DrupalDateTime($node->field_date->value, $timezone);
      $current_time->setTimezone($timezone);
      if($event_date < $current_time) {
        $time_message = t('Event has ended');
      } else {
        $time_diff = $event_date->diff($current_time);
        $time_diff_hours = $time_diff->days * 24 + $time_diff->h;
        if ($time_diff_hours < 24) {
          if($event_date->format('Y-m-d') == $current_time->modify('+1 day')->format('Y-m-d')) {
            $time_message = t('1 day left until event starts');
          } else {
            $time_message = t('Event is happening today');
          }
        } else {
          $time_message = t('%days days left until event starts', array('%days' => $time_diff->format('%a')));
        }
      }
      $event = [
        'time_message' => $time_message
      ];
    }
    return $event;
  }
}

