<?php

class TrackTest extends PHPUnit_Framework_TestCase {
  protected $obj = null;
  protected $talks = [];

  protected function setUp() {
    $talks = [
      'Writing Fast Tests Against Enterprise Rails 60min',
      'Overdoing it in Python 45min',
      'Lua for the Masses 30min',
      'Ruby Errors from Mismatched Gem Versions 45min',
      'Ruby on Rails: Why We Should Move On 60min'
    ];

    $this->obj = new App\Track();

    foreach($talks as $talk) {
      $this->talks[] = new App\Talk($talk);
    }
  }

  public function testHaveAttributes() {
    $klass = get_class($this->obj);
    $this->assertClassHasAttribute('talks', $klass);
    $this->assertClassHasAttribute('planned_talks', $klass);
    $this->assertClassHasAttribute('starttime', $klass);
    $this->assertClassHasAttribute('endtime', $klass);
    $this->assertClassHasAttribute('halfday', $klass);
    $this->assertClassHasAttribute('total_length', $klass);
  }

  public function testTotalLength() {
    $this->obj->starttime = '09:00';
    $this->obj->endtime = '11:30';

    $this->obj->total_length = $this->obj->total_diff_length(
      $this->obj->track_datetime($this->obj->starttime),
      $this->obj->track_datetime($this->obj->endtime)
    );
    $this->assertEquals(150, $this->obj->total_length);
  }

  public function testPlanTalks() {
    $this->obj->talks = $this->talks;

    $this->obj->plan_talks();
    $this->assertEquals([
      '09:00AM',
      '10:00AM',
      '10:45AM',
      '11:15AM',
      '12:00PM',
      '01:00PM'
    ], array_keys($this->obj->planned_talks));
  }
}
