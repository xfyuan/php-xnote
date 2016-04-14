<?php
namespace App;

class Track {
  const START_TIME   = '09:00';
  const LUNCH_TIME   = '12:00';
  const END_TIME     = '17:00';
  const LUNCH_LENGTH = 60;

  public $talks         = [];
  public $plannedTalks = [];
  public $starttime;
  public $endtime;
  public $lunchtime;
  public $totalLength;

  public function __construct(){
    $this->starttime    = self::START_TIME;
    $this->endtime      = self::END_TIME;
    $this->lunchtime    = self::LUNCH_TIME;
    $this->totalLength = $this->totalDiffLength(
      $this->trackDatetime($this->starttime),
      $this->trackDatetime($this->endtime)
    ) - self::LUNCH_LENGTH;
  }

  public function totalDiffLength($track_datetime1, $track_datetime2) {
    $interval     = $track_datetime1->diff($track_datetime2);
    $diff_hours   = $interval->format('%h');
    $diff_minutes = $interval->format('%i');

    return $diff_hours * 60 + $diff_minutes;
  }

  public function planTalks() {
    $datetime = $this->trackDatetime($this->starttime);
    $datetime_lunch = $this->trackDatetime(self::LUNCH_TIME);

    foreach($this->talks as $talk) {
      $marked_time = $datetime->format('h:iA');
      $this->plannedTalks[$marked_time] = $talk;
      $datetime->add(date_interval_create_from_date_string("{$talk->length} minutes"));

      if ($this->totalDiffLength($datetime, $datetime_lunch) < 5) {
        $marked_time = $datetime_lunch->format('h:iA');
        $this->plannedTalks[$marked_time] = new Talk('Lunch');
        $datetime->add(date_interval_create_from_date_string("1 hour"));
      }
    }

    $this->plannedTalks[$datetime->format('h:iA')] = new Talk('Networking Event');
  }

  public function trackDatetime($timestr) {
    $dts = new \DateTime();
    list($hour, $minutes) = explode(':', $timestr);
    $dts->setTime($hour, $minutes);
    return $dts;
  }
}
