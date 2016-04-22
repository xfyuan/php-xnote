<?php
namespace App;

class Conference {

  /**
   * Days of whole conference
   *
   * @var number
   **/
  public $days            = null;

  /**
   * whole talks
   *
   * @var array
   **/
  public $talks           = [];

  /**
   * whole talks sorted by time length
   *
   * @var array
   **/
  public $sortedTalks    = [];

  /**
   * whole tracks
   *
   * @var array
   **/
  public $tracks          = [];

  /**
   * whole tracks filled with planned talks
   *
   * @var array
   **/
  public $scheduledTracks = [];

  /**
   * Create a new Conference.
   *
   * @param string $data
   * @return void
   **/
  public function __construct($data) {
    $this->readSource($data);
    $this->refreshDays();
    $this->refreshTracks();
  }

  /**
   * Schedule tracks with talks
   *
   * @return void
   **/
  public function scheduleTracksWithTalks() {
    $this->sortedTalks();
    $this->scheduledTracks = array_reduce($this->tracks, function($memo, $track) {
      $track->talks = $this->talksForCurrentTrack($track);
      $track->planTalks();
      $memo[] = $track;
      return $memo;
    }, []);
  }

  /**
   * Print scheduled tracks of whole conference
   *
   * @return void
   **/
  public function outputScheduledTracks() {
    foreach($this->scheduledTracks as $i => $track) {
      echo "Track" . ($i+1) . PHP_EOL;
      echo implode(PHP_EOL, $this->printableTrack($track));
      echo PHP_EOL . PHP_EOL;
    }
  }

  /**
   * Sort whole conference's talks by time length
   *
   * @return void
   **/
  private function sortedTalks() {
    $this->sortedTalks = array_reduce($this->talks, function($memo, $talk) {
      $key = $talk->length . preg_replace('/ /', '-', strtolower($talk->title));
      $memo[$key] = $talk;
      return $memo;
    }, []);
    krsort($this->sortedTalks, SORT_NUMERIC);
  }

  /**
   * Printable track with it's full talks
   *
   * @param object $track
   * @return array
   **/
  private function printableTrack($track) {
    return array_map(function($time_tag, $talk) {
      return  "{$time_tag} {$talk}";
    }
    ,array_keys($track->plannedTalks)
    ,$track->plannedTalks);
  }

  /**
   * All talks which can be filled into current track until it's full filled
   *
   * @param object $track
   * @return array
   **/
  private function talksForCurrentTrack($track) {
    $totalTrackLength = $track->totalLength;
    return array_reduce($this->sortedTalks, function($memo, $talk) use (&$totalTrackLength) {
      if (!$talk->marked && $totalTrackLength >= $talk->length) {
        $memo[] = $talk;
        $talk->marked = true;
        $totalTrackLength -= $talk->length;
      }
      return $memo;
    }, []);
  }

  /**
   * Parse plan string of conference schedule
   *
   * @param string $data
   * @return void
   **/
  private function readSource($data) {
    if($talks = preg_split("/".PHP_EOL."/", $data)) {
      $this->talks = array_map(function($talk) {
        return new Talk($talk);
      }, $talks);
    }
  }

  /**
   * Calculate whole days needed for the conference
   *
   * @return void
   **/
  private function refreshDays() {
    $minutes = array_reduce($this->talks, function($memo, $talk){
      return $memo += $talk->length;
    }, 0);
    $this->days = (int) ceil($minutes / (new Track())->totalLength);
  }

  /**
   * Generate track object for each day
   *
   * @return void
   **/
  private function refreshTracks() {
    for ($i=0; $i < $this->days; $i++) {
      $this->tracks[] = new Track();
    }
  }
}
