<?php
namespace App\Tests;
use App\Conference;
use App\Talk;

class ConferenceTest extends \PHPUnit_Framework_TestCase {
  protected $obj = null;
  protected $talks = [];

  protected function setUp() {
    $talks = [
      'Writing Fast Tests Against Enterprise Rails 60min',
      'Overdoing it in Python 45min',
      'Lua for the Masses 30min',
      'Ruby Errors from Mismatched Gem Versions 45min',
      'Common Ruby Errors 45min',
      'Rails for Python Developers lightning',
      'Communicating Over Distance 60min',
      'Accounting-Driven Development 45min',
      'Woah 30min',
      'Sit Down and Write 30min',
      'Pair Programming vs Noise 45min',
      'Rails Magic 60min',
      'Ruby on Rails: Why We Should Move On 60min',
      'Clojure Ate Scala (on my project) 45min',
      'Programming in the Boondocks of Seattle 30min',
      'Ruby vs. Clojure for Back-End Development 30min',
      'Ruby on Rails Legacy App Maintenance 60min',
      'A World Without HackerNews 30min',
      'User Interface CSS in Rails Apps 30min',
    ];

    $data = <<<EOF
$talks[0]
$talks[1]
$talks[2]
$talks[3]
$talks[4]
$talks[5]
$talks[6]
$talks[7]
$talks[8]
$talks[9]
$talks[10]
$talks[11]
$talks[12]
$talks[13]
$talks[14]
$talks[15]
$talks[16]
$talks[17]
$talks[18]
EOF;

    $this->obj = new Conference($data);

    foreach($talks as $talk) {
      $this->talks[] = new Talk($talk);
    }
  }

  public function testHaveAttributes() {
    $klass = get_class($this->obj);
    $this->assertClassHasAttribute('days',            $klass);
    $this->assertClassHasAttribute('talks',           $klass);
    $this->assertClassHasAttribute('groupedTalks',    $klass);
    $this->assertClassHasAttribute('tracks',          $klass);
    $this->assertClassHasAttribute('scheduledTracks', $klass);
  }

  public function testInitial() {
    $this->assertCount(19, $this->obj->talks);
    $this->assertInstanceOf('App\Talk', $this->obj->talks[0]);

    $this->assertEquals(2, $this->obj->days);

    $this->assertCount(2, $this->obj->tracks);
    $this->assertInstanceOf('App\Track', $this->obj->tracks[0]);
  }

  public function testGroupedTalks() {
    $this->assertEquals(count($this->talks), count($this->obj->groupedTalks));
    $this->obj->scheduleTracksWithTalks();
    $this->assertCount(8, $this->obj->scheduledTracks[0]->talks);
    $this->assertCount(11, $this->obj->scheduledTracks[1]->talks);
  }

}
