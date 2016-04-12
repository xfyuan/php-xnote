<?php
namespace App;

class Talk {
  const LIGHTING_LENGTH = 5;
  const TIME_UNIT       = 'min';
  const NORMAL_TAG      = 'normal';
  const LIGHTING_TAG    = 'lightning';

  public $title;
  public $length;
  public $tag;
  public $times  = self::TIME_UNIT;
  public $marked = false;

  public function __construct($input) {
    if($result = $this->parse($input)){
      $this->title     = $result[1];
      $this->length    = count($result) > 3 ? (int)$result[3] : self::LIGHTING_LENGTH;
      $this->tag       = preg_match("/\d+/", $result[2]) ? self::NORMAL_TAG: self::LIGHTING_TAG;
    }
  }

  public function parse($input) {
    $regex = "/(.*)\s((\d+)\s*". self::TIME_UNIT ."|". self::LIGHTING_TAG .")$/ui";
    preg_match($regex, $input, $matches);
    return $matches;
  }

  public function print() {
    $string = $this->tag === self::NORMAL_TAG ? "{$this->length}{$this->times}" : "{$this->tag}";
    return "{$this->title} {$string}";
  }

  public function toggle_marked() {
    $this->marked = !$this->marked;
  }
}
