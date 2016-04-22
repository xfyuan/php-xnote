<?php
namespace App\Tests;
use App\Talk;

class TalkTest extends \PHPUnit_Framework_TestCase {
  protected $obj = null;

  protected function setUp() {
    $this->assignNormalTalk();
  }

  private function assignNormalTalk() {
    $this->obj = new Talk('Common Ruby Errors 45min');
  }

  private function assignLightningTalk() {
    $this->obj = new Talk('Common Ruby Errors lightning');
  }

  /**
   * Test class properties
   *
   * @return void
   **/
  public function testHaveAttributes() {
    $klass = get_class($this->obj);
    $this->assertClassHasAttribute('title',  $klass);
    $this->assertClassHasAttribute('length', $klass);
    $this->assertClassHasAttribute('times',  $klass);
    $this->assertClassHasAttribute('tag',    $klass);
    $this->assertClassHasAttribute('marked', $klass);
  }

  /**
   * Test class initial
   *
   * @return void
   **/
  public function testInitialTalk() {
    $this->assertEquals('Common Ruby Errors', $this->obj->title);
    $this->assertEquals(45,       $this->obj->length);
    $this->assertEquals('min',    $this->obj->times);
    $this->assertEquals('normal', $this->obj->tag);
    $this->assertFalse($this->obj->marked);
  }

  /**
   * Test class initial when lighting talk
   *
   * @return void
   **/
  public function testInitialTalkLighting() {
    $this->assignLightningTalk();

    $this->assertEquals('Common Ruby Errors', $this->obj->title);
    $this->assertEquals(5,           $this->obj->length);
    $this->assertEquals('min',       $this->obj->times);
    $this->assertEquals('lightning', $this->obj->tag);
    $this->assertFalse($this->obj->marked);
  }

  /**
   * Test class print
   *
   * @return void
   **/
  public function testToString() {
    $this->assertEquals('Common Ruby Errors 45min', $this->obj);

    $this->assignLightningTalk();
    $this->assertEquals('Common Ruby Errors lightning', $this->obj);
  }

}
