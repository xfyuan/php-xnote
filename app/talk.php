<?php
namespace App;

class Talk {
  const LIGHTNING_LENGTH = 5;
  const LUNCH_LENGTH     = 60;
  const TIME_UNIT        = 'min';
  const NORMAL_TAG       = 'normal';
  const LIGHTNING_TAG    = 'lightning';
  const DEFAULT_TAG      = 'default';
  const IS_PUBLIC_EVENT  = true;
  const PUBLIC_EVENT     = ['lunch', 'networking event'];

  /**
   * Talk title
   *
   * @var string
   **/
  public $title;

  /**
   * Talk time length
   *
   * @var number
   **/
  public $length;

  /**
   * Talk tag
   *
   * @var string
   **/
  public $tag;

  /**
   * Talk time unit
   *
   * @var string
   **/
  public $times  = self::TIME_UNIT;

  /**
   * Mark a talk is planned or not
   *
   * @var bool
   **/
  public $marked = false;

  /**
   * Create a new Talk.
   *
   * @param string $input
   * @return void
   **/
  public function __construct($input) {
    if ($result = $this->parse($input)){
      $this->title  = $result[1];

      if ($result[0] === self::IS_PUBLIC_EVENT) {
        $this->length = self::LUNCH_LENGTH;
        $this->tag    = self::DEFAULT_TAG;
      } else {
        $this->length = count($result) > 3 ? (int)$result[3] : self::LIGHTNING_LENGTH;
        $this->tag    = preg_match("/\d+/", $result[2]) ? self::NORMAL_TAG : self::LIGHTNING_TAG;
      }
    }
  }

  /**
   * __toString
   *
   * @return void
   **/
  public function __toString() {
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

  /**
   * Parse talk plan string
   *
   * @param string $input
   * @return array
   **/
  private function parse($input) {
    if (in_array(strtolower($input), self::PUBLIC_EVENT)) {
      return [self::IS_PUBLIC_EVENT, strtolower($input)];
    }

    $regex = "/(.*)\s((\d+)\s*". self::TIME_UNIT ."|". self::LIGHTNING_TAG .")$/ui";
    preg_match($regex, $input, $matches);
    return $matches;
  }

}
