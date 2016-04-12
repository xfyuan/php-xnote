<?php

class TalkTest extends PHPUnit_Framework_TestCase {
  protected $obj = null;

  protected function setUp() {
    $this->assign_normal_talk();
  }

  private function assign_normal_talk() {
    $this->obj = new App\Talk('Common Ruby Errors 45min');
  }

  private function assign_lightning_talk() {
    $this->obj = new App\Talk('Common Ruby Errors lightning');
  }

  public function testHaveAttributes() {
    $klass = get_class($this->obj);
    $this->assertClassHasAttribute('title', $klass);
    $this->assertClassHasAttribute('length', $klass);
    $this->assertClassHasAttribute('times', $klass);
    $this->assertClassHasAttribute('tag', $klass);
    $this->assertClassHasAttribute('marked', $klass);
  }

  public function testInitialTalk() {
    $this->assertEquals('Common Ruby Errors', $this->obj->title);
    $this->assertEquals(45, $this->obj->length);
    $this->assertEquals('min', $this->obj->times);
    $this->assertEquals('normal', $this->obj->tag);
    $this->assertFalse($this->obj->marked);
  }

  public function testInitialTalkLighting() {
    $this->assign_lightning_talk();

    $this->assertEquals('Common Ruby Errors', $this->obj->title);
    $this->assertEquals(5, $this->obj->length);
    $this->assertEquals('min', $this->obj->times);
    $this->assertEquals('lightning', $this->obj->tag);
    $this->assertFalse($this->obj->marked);
  }

  public function testParseInput() {
    $result = $this->obj->parse('Common Ruby Errors 45min');
    $this->assertContains(45, $result);
    $result = $this->obj->parse('Common Ruby Errors lightning');
    $this->assertContains('lightning', $result);
  }

  public function testToggleMarked() {
    $this->obj->toggle_marked();
    $this->assertTrue($this->obj->marked);
    $this->obj->toggle_marked();
    $this->assertFalse($this->obj->marked);
  }

  public function testPrint() {
    $this->assertEquals('Common Ruby Errors 45min', $this->obj->print());

    $this->assign_lightning_talk();
    $this->assertEquals('Common Ruby Errors lightning', $this->obj->print());
  }

}
