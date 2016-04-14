<?php
namespace App;

class Conference {
  public $days             = null;
  public $talks            = [];
  public $groupedTalks    = [];
  public $tracks           = [];
  public $scheduledTracks = [];

  public function __construct($data) {
    $this->readSource($data);
    $this->refreshDays();
    $this->refreshTracks();
  }

  public function readSource($data) {
    if($talks = preg_split("/".PHP_EOL."/", $data)) {
      foreach($talks as $talk) {
        $this->talks[] = new Talk($talk);
      }
    }
  }

  public function refreshDays() {
    $minutes = array_reduce($this->talks, function($memo, $talk){
      return $memo += $talk->length;
    });
    $this->days = (int) ceil($minutes / (new Track())->totalLength);
  }

  public function refreshTracks() {
    for ($i=0; $i < $this->days; $i++) {
      $this->tracks[] = new Track();
    }
  }

  public function groupedTalks() {
    foreach($this->talks as $talk) {
      $this->groupedTalks[$talk->length][] = $talk;
    }
    krsort($this->groupedTalks, SORT_NUMERIC);
  }

  public function scheduleTracksWithTalks() {
    foreach($this->tracks as $track) {
      $total_track_length = $track->totalLength;

      foreach($this->groupedTalks as $length => $talks) {
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
      $track->planTalks();
      $this->scheduledTracks[] = $track;
    }
  }

  public function outputScheduledTracks() {
    foreach($this->scheduledTracks as $i => $track) {
      echo "Track" . ($i+1) . PHP_EOL;
      foreach($track->plannedTalks as $marked_time => $talk) {
        echo "{$marked_time} {$talk->output()}" . PHP_EOL;
      }
      echo PHP_EOL . PHP_EOL;
    }
  }

}
