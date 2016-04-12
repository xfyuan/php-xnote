<?php
namespace App;

class Conference {
  public $days             = null;
  public $talks            = [];
  public $grouped_talks    = [];
  public $tracks           = [];
  public $scheduled_tracks = [];

  public function __construct($data) {
    $this->read_source($data);
    $this->refresh_days();
    $this->refresh_tracks();
  }

  public function read_source($data) {
    if($talks = preg_split("/".PHP_EOL."/", $data)) {
      foreach($talks as $talk) {
        $this->talks[] = new Talk($talk);
      }
    }
  }

  public function refresh_days() {
    $minutes = array_reduce($this->talks, function($memo, $talk){
      return $memo += $talk->length;
    });
    $this->days = (int) ceil($minutes / (new Track())->total_length);
  }

  public function refresh_tracks() {
    for ($i=0; $i < $this->days; $i++) {
      $this->tracks[] = new Track();
    }
  }

  public function grouped_talks() {
    foreach($this->talks as $talk) {
      $this->grouped_talks[$talk->length][] = $talk;
    }
    krsort($this->grouped_talks, SORT_NUMERIC);
  }

  public function schedule_tracks_with_talks() {
    foreach($this->tracks as $track) {
      $total_track_length = $track->total_length;

      foreach($this->grouped_talks as $length => $talks) {
        foreach($talks as $talk) {
          if (!$talk->marked) {
            if ($total_track_length >= $length) {
              $track->talks[] = $talk;
              $talk->marked = true;
              $total_track_length -= $length;
            } else {
              break;
            }
          }
        }
      }
      $track->plan_talks();
      $this->scheduled_tracks[] = $track;
    }
  }

  public function output_scheduled_tracks() {
    foreach($this->scheduled_tracks as $i => $track) {
      echo "Track" . ($i+1) . PHP_EOL;
      foreach($track->planned_talks as $marked_time => $talk) {
        echo "{$marked_time} {$talk->output()}" . PHP_EOL;
      }
      echo PHP_EOL . PHP_EOL;
    }
  }

}
