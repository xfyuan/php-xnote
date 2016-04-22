<?php
namespace App;

class Talk {
  const LIGHTNING_LENGTH = 5;
  const LUNCH_LENGTH     = 60;
  const TIME_UNIT        = 'min';
  const NORMAL_TAG       = 'normal';
  const LIGHTNING_TAG    = 'lightning';
  const DEFAULT_TAG      = 'default';

  public $title;
  public $length;
  public $tag;
  public $times  = self::TIME_UNIT;
  public $marked = false;

  public function __construct($input) {
    if (strtolower($input) === 'lunch' || strtolower($input) === 'networking event') {
      $this->title  = strtolower($input);
      $this->length = self::LUNCH_LENGTH;
      $this->tag    = self::DEFAULT_TAG;

    } elseif ($result = $this->parse($input)){
      $this->title  = $result[1];
      $this->length = count($result) > 3 ? (int)$result[3] : self::LIGHTNING_LENGTH;
      $this->tag    = preg_match("/\d+/", $result[2]) ? self::NORMAL_TAG : self::LIGHTNING_TAG;
    }
  }

  public function output() {
    switch ($this->tag) {
      case self::NORMAL_TAG:
        $string = "{$this->title} {$this->length}{$this->times}";
        break;
      case self::LIGHTNING_TAG:
        $string = "{$this->title} {$this->tag}";
        break;
      default:
        $string = ucwords($this->title);
        break;
    }
    return $string;
  }

  private function parse($input) {
    $regex = "/(.*)\s((\d+)\s*". self::TIME_UNIT ."|". self::LIGHTNING_TAG .")$/ui";
    preg_match($regex, $input, $matches);
    return $matches;
  }

}
