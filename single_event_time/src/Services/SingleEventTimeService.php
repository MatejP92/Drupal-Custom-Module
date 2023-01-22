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
      $time_to_event = round(($event_date->getTimestamp() - $current_time->getTimestamp()) / 86400);
      if ($time_to_event > 1) {
        $time_message = $time_to_event . ' ' . t('days left until event starts');
      } elseif ($time_to_event == 0) {
        $time_message = t('This event is happening today');
      } elseif ($time_to_event == 1) {
        $time_message = $time_to_event . ' ' . t('day left until event starts');
      } else {
        $time_message = t('Event has ended');
      }
      $event = [
        'time_message' => $time_message
      ];
    }
    return $event;
  }
}
