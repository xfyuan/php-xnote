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
    $this->starttime   = self::START_TIME;
    $this->endtime     = self::END_TIME;
    $this->lunchtime   = self::LUNCH_TIME;
    $this->totalLength = $this->totalDiffLength(
      $this->trackDatetime($this->starttime),
      $this->trackDatetime($this->endtime)
    ) - self::LUNCH_LENGTH;
  }

  public function planTalks() {
    $datetime = $this->trackDatetime($this->starttime);
    $datetimeLunch = $this->trackDatetime(self::LUNCH_TIME);

    $this->plannedTalks = $this->plannedTalksWithLunch($datetime, $datetimeLunch);

    $this->fillNetworkEvent($datetime);
  }

  private function plannedTalksWithLunch($dts, $dts_lunch) {
    return array_reduce($this->talks, function($memo, $talk) use (&$dts, $dts_lunch) {
      $memo[$this->timeTag($dts)] = $talk;
      $dts->add(date_interval_create_from_date_string("{$talk->length} minutes"));

      if ($this->totalDiffLength($dts, $dts_lunch) < 5) {
        $memo[$this->timeTag($dts_lunch)] = new Talk('Lunch');
        $dts->add(date_interval_create_from_date_string("1 hour"));
      }

      return $memo;
    }, []);
  }

  private function fillNetworkEvent($datetime) {
    $this->plannedTalks[$this->timeTag($datetime)] = new Talk('Networking Event');
  }

  private function timeTag($dts) {
    return $dts->format('h:iA');
  }

  private function totalDiffLength($trackDatetime1, $trackDatetime2) {
    $interval    = $trackDatetime1->diff($trackDatetime2);
    $diffHours   = $interval->format('%h');
    $diffMinutes = $interval->format('%i');

    return $diffHours * 60 + $diffMinutes;
  }

  private function trackDatetime($timestr) {
    $dts = new \DateTime();
    list($hour, $minutes) = explode(':', $timestr);
    $dts->setTime($hour, $minutes);
    return $dts;
  }
}
