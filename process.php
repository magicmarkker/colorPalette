<?php
include_once('Processor.class.php');
$obj = new Processor();
switch($_POST['action']) {
  case 'pasted':
    $styles = $_POST['styles'];
  break;
  case 'url':
    $url = $_POST['url'];
    $styles = file_get_contents($url);
  break;
}
print $obj->process($styles);
?>