<?php
namespace App;

class Track {
  const START_TIME   = '09:00';
  const LUNCH_TIME   = '12:00';
  const END_TIME     = '17:00';
  const LUNCH_LENGTH = 60;

  /**
   * Talks in track
   *
   * @var array
   **/
  public $talks         = [];

  /**
   * Talks in track is planned
   *
   * @var array
   **/
  public $plannedTalks = [];

  /**
   * Track start time
   *
   * @var string
   **/
  public $starttime;

  /**
   * Track end time
   *
   * @var string
   **/
  public $endtime;

  /**
   * Track lunch time
   *
   * @var string
   **/
  public $lunchtime;

  /**
   * Track total time length which don't include lunch time
   *
   * @var number
   **/
  public $totalLength;

  /**
   * Create a new Track.
   *
   * @return void
   **/
  public function __construct(){
    $this->starttime   = self::START_TIME;
    $this->endtime     = self::END_TIME;
    $this->lunchtime   = self::LUNCH_TIME;
    $this->totalLength = $this->totalDiffLength(
      $this->trackDatetime($this->starttime),
      $this->trackDatetime($this->endtime)
    ) - self::LUNCH_LENGTH;
  }

  /**
   * Plan current track's talks
   *
   * @return void
   **/
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

  /**
   * add network event in track
   *
   * @param datetime $dts
   * @return void
   **/
  private function fillNetworkEvent($dts) {
    $this->plannedTalks[$this->timeTag($dts)] = new Talk('Networking Event');
  }

  /**
   * Time tag for a talk
   *
   * @param datetime $dts
   * @return string
   **/
  private function timeTag($dts) {
    return $dts->format('h:iA');
  }

  /**
   * Time difference between 2 datetimes by minutes
   *
   * @param datetime $dts1
   * @param datetime $dts2
   * @return number
   **/
  private function totalDiffLength($dts1, $dts2) {
    $interval    = $dts1->diff($dts2);
    $diffHours   = $interval->format('%h');
    $diffMinutes = $interval->format('%i');

    return $diffHours * 60 + $diffMinutes;
  }

  /**
   * convert a time sting into a datetime
   *
   * @param string $timestr
   * @return datetime
   **/
  private function trackDatetime($timestr) {
    $dts = new \DateTime();
    list($hour, $minutes) = explode(':', $timestr);
    $dts->setTime($hour, $minutes);
    return $dts;
  }
}
