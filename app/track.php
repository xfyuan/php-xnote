<?php
namespace App;

class Track {
  const START_TIME   = '09:00';
  const LUNCH_TIME   = '12:00';
  const END_TIME     = '17:00';
  const LUNCH_LENGTH = 60;

  public $talks         = [];
  public $planned_talks = [];
  public $halfday       = null;
  public $starttime;
  public $endtime;
  public $lunchtime;
  public $total_length;

  public function __construct(){
    $this->starttime    = self::START_TIME;
    $this->endtime      = self::END_TIME;
    $this->lunchtime    = self::LUNCH_TIME;
    $this->total_length = $this->total_diff_length(
      $this->track_datetime($this->starttime),
      $this->track_datetime($this->endtime)
    ) - self::LUNCH_LENGTH;
  }

  public function total_diff_length($track_datetime1, $track_datetime2) {
    $interval     = $track_datetime1->diff($track_datetime2);
    $diff_hours   = $interval->format('%h');
    $diff_minutes = $interval->format('%i');

    return $diff_hours * 60 + $diff_minutes;
  }

  public function plan_talks() {
    $datetime = $this->track_datetime($this->starttime);
    $datetime_lunch = $this->track_datetime(self::LUNCH_TIME);

    foreach($this->talks as $talk) {
      $marked_time = $datetime->format('h:iA');
      $this->planned_talks[$marked_time] = $talk;
      $datetime->add(date_interval_create_from_date_string("{$talk->length} minutes"));

      if ($this->total_diff_length($datetime, $datetime_lunch) < 5) {
        $marked_time = $datetime_lunch->format('h:iA');
        $this->planned_talks[$marked_time] = new Talk('Lunch');
        $datetime->add(date_interval_create_from_date_string("1 hour"));
      }
    }

    $this->planned_talks[$datetime->format('h:iA')] = new Talk('Networking Event');
  }

  public function track_datetime($timestr) {
    $dts = new \DateTime();
    list($hour, $minutes) = explode(':', $timestr);
    $dts->setTime($hour, $minutes);
    return $dts;
  }
}
