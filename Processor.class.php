<?php
class Processor {

  public $styles;
  
  private $regex = '(#([0-9A-Fa-f]{3,6})\b)';

  public function __construct() {}

  public function process($styles) {
    $this->styles = $styles;
    preg_match_all($this->regex, $this->styles, $matches, PREG_SET_ORDER); //get all matches
    $matches = array_unique($matches, SORT_REGULAR); //remove duplicates
    //prepare the array to be json compatible
    //and easy to parse ex. #fff => fff
    foreach ($matches as $match) {
      $colors[$match[1]] = $match[0];
    }
    sort($colors);
    return json_encode($colors);
  }
}

?>
