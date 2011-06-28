<?php
include('simple_html_dom.php');
class Processor {
  protected $styles;
  protected $styles_glob;
  private $colors;
  private $regex = '(#([0-9A-Fa-f]{3,6})\b)';
  private $site_regex = '';

  function __construct( $styles, $url = false ){
    $this->styles = $url ? $styles : $styles;
    $this->colors = array();
    $this->process($url);
  }

  public static function fromURL( $url ){
    $tmp = new self( $url, true );
    return $tmp;
  }

  public static function fromPaste( $styles ){
    $tmp = new self( $styles );
    return $tmp;
  }

  private function process($url){
    if ($url) {
      $this->get_css_files($this->styles);
    }
    preg_match_all($this->regex, $this->styles, $matches, PREG_SET_ORDER); //get all matches
    $c = $this->removeDupes($matches);
    $this->sortByHue($c);
  }

  public function getPalette(){
    return json_encode( $this->colors );
  }

  private function removeDupes($matches) {
    foreach ($matches as $match) {
      $c[] = $this->hex2RGB($match[0], true);
    }
    $c = array_filter($c);
    return $c;
  }
  private function sortByHue($c) {
    foreach ($c as $color) {
      list($r, $g, $b) = explode(',', $color);
      $hsv[] = $this->rgbtohsv($r,$g,$b);
    }
    $d = $this->sortColors($hsv);
    $this->colors = $d;
  }

  private function get_css_files($page) {
    $html = file_get_html($page);
    foreach($html->find('link') as $element) {
      if ($element->type == 'text/css') {
        $this->styles_glob .= file_get_contents($page .'/'. $element->href);
      }
    }
    $this->styles = $this->styles_glob;
  }

  private function rgb2Hex($rgb) {
    $newcolors = array();
    foreach ($rgb as $color) {
      if (!empty($color)) {
        list($r, $g, $b) = explode(',', $color);
        $hex = "#";
        $hex.= str_pad(dechex($r), 2, "0", STR_PAD_LEFT);
        $hex.= str_pad(dechex($g), 2, "0", STR_PAD_LEFT);
        $hex.= str_pad(dechex($b), 2, "0", STR_PAD_LEFT);

        $newcolors[$hex] = $hex;
      }
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

  private function rgbtohsv($r,$g,$b) {
    //Convert RGB to HSV
    $r /= 255;
    $g /= 255;
    $b /= 255;
    $min = min($r,$g,$b);
    $max = max($r,$g,$b);

    switch($max) {
      case 0:
          $h = $s = $v = 0;
          break;
      case $min:
          $h = $s = 0;
          $v = $max;
          break;
      default:
          $delta = $max - $min;
          if($r == $max) {
              $h = 0 + ($g - $b) / $delta;
          } elseif($g == $max) {
              $h = 2 + ($b - $r) / $delta;
          } else {
              $h = 4 + ($r - $g) / $delta;
          }
          $h *= 60;
          if($h < 0 ) $h += 360;
          $s = $delta / $max;
          $v = $max;
      }
    return array($h,$s,$v);
  }

  private function hsvtorgb($h,$s,$v) {
    //Convert HSV to RGB
    if($s == 0) {
        $r = $g = $b = $v;
    } else {
        $h /= 60.0;
        $s = $s;
        $v = $v;

        $hi = floor($h);
        $f = $h - $hi;
        $p = ($v * (1.0 - $s));
        $q = ($v * (1.0 - ($f * $s)));
        $t = ($v * (1.0 - ((1.0 - $f) * $s)));

        switch($hi) {
            case 0: $r = $v; $g = $t; $b = $p; break;
            case 1: $r = $q; $g = $v; $b = $p; break;
            case 2: $r = $p; $g = $v; $b = $t; break;
            case 3: $r = $p; $g = $q; $b = $v; break;
            case 4: $r = $t; $g = $p; $b = $v; break;
            default: $r = $v; $g = $p; $b = $q; break;
        }
    }
    return array(
        (integer) ($r * 255 + 0.5),
        (integer) ($g * 255 + 0.5),
        (integer) ($b * 255 + 0.5),
    );
  }

  private function sortColors($hsv) {
    foreach($hsv as $k => $v) {
      $hue[$k] = $v[0];
      $sat[$k] = $v[1];
      $val[$k] = $v[2];
    }
    array_multisort($hue,SORT_ASC,$sat,SORT_ASC,$val,SORT_ASC,$hsv,SORT_ASC);
    foreach($hsv as $k => $v) {
      list($hue,$sat,$val) = $v;
      list($r,$g,$b) = $this->hsvtorgb($hue,$sat,$val);
      $rgbvals[] = implode(',', array($r, $g, $b));
    }
    return $this->rgb2Hex($rgbvals);
  }
}
?>
