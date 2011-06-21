<?php
class Processor {
  protected $styles;
  private $colors;
  private $regex = '(#([0-9A-Fa-f]{3,6})\b)';

  function __construct( $styles, $url = false ){
    $this->styles = $url ? file_get_contents( $styles ) : $styles;

    $this->process();
  }

  public static function fromURL( $url ){
    $tmp = new self( $url, true );
    return $tmp;
  }

  public static function fromPaste( $styles ){
    $tmp = new self( $styles );
    return $tmp;
  }

  private function process(){
      preg_match_all($this->regex, $this->styles, $matches, PREG_SET_ORDER); //get all matches
      $this->removeDupes($matches);
  }

  public function getPalette(){
    return json_encode( $this->colors );
  }

  public function removeDupes($matches) {

    foreach ($matches as $match) {
      $c[] = $this->hex2RGB($match[0], true);
    }
    $c = array_unique($c);
    $c = $this->rgb2Hex($c);
    $this->colors = $c;
  }

  private function rgb2Hex($rgb) {
    $newcolors = array();
    foreach ($rgb as $color) {
      list($r, $g, $b) = explode(',', $color);
      $hex = "#";
      $hex.= str_pad(dechex($r), 2, "0", STR_PAD_LEFT);
      $hex.= str_pad(dechex($g), 2, "0", STR_PAD_LEFT);
      $hex.= str_pad(dechex($b), 2, "0", STR_PAD_LEFT);
      
      $newcolors[$hex] = $hex;
    }
    return $newcolors;
  }
  /**
   * Thanks to hafees from http://www.php.net/manual/en/function.hexdec.php#99478 for the below
   * Convert a hexa decimal color code to its RGB equivalent
   *
   * @param string $hexStr (hexadecimal color value)
   * @param boolean $returnAsString (if set true, returns the value separated by the separator character. Otherwise returns associative array)
   * @param string $seperator (to separate RGB values. Applicable only if second parameter is true.)
   * @return array or string (depending on second parameter. Returns False if invalid hex color value)
   */
  private function hex2RGB($hexStr, $returnAsString = false, $seperator = ',') {
    $hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr); // Gets a proper hex string
    $rgbArray = array();
    if (strlen($hexStr) == 6) { //If a proper hex code, convert using bitwise operation. No overhead... faster
        $colorVal = hexdec($hexStr);
        $rgbArray['red'] = 0xFF & ($colorVal >> 0x10);
        $rgbArray['green'] = 0xFF & ($colorVal >> 0x8);
        $rgbArray['blue'] = 0xFF & $colorVal;
    } elseif (strlen($hexStr) == 3) { //if shorthand notation, need some string manipulations
        $rgbArray['red'] = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
        $rgbArray['green'] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
        $rgbArray['blue'] = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
    } else {
        return false; //Invalid hex color code
    }
    return $returnAsString ? implode($seperator, $rgbArray) : $rgbArray; // returns the rgb string or the associative array
  }
}
?>
