<?php
namespace App\Tests;
use App\Track;
use App\Talk;

class TrackTest extends \PHPUnit_Framework_TestCase {
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

    $this->obj = new Track();

    $this->talks = array_map(function($talk) {
      return new Talk($talk);
    }, $talks);
  }

  /**
   * Test class properties
   *
   * @return void
   **/
  public function testHaveAttributes() {
    $klass = get_class($this->obj);
    $this->assertClassHasAttribute('talks', $klass);
    $this->assertClassHasAttribute('plannedTalks', $klass);
    $this->assertClassHasAttribute('starttime', $klass);
    $this->assertClassHasAttribute('endtime', $klass);
    $this->assertClassHasAttribute('totalLength', $klass);
  }

  /**
   * Test planned talks
   *
   * @return void
   **/
  public function testPlanTalks() {
    $this->obj->talks = $this->talks;

    $this->obj->planTalks();
    $this->assertEquals([
      '09:00AM',
      '10:00AM',
      '10:45AM',
      '11:15AM',
      '12:00PM',
      '01:00PM',
      '02:00PM'
    ], array_keys($this->obj->plannedTalks));
  }
}
