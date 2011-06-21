<?php
include_once('Processor.class.php');

switch($_POST['action']) {
  case 'pasted':
    $styles = $_POST['styles'];
    $P = Processor::fromPaste($styles);
  break;
  case 'url':
    $url = $_POST['url'];
    $P = Processor::fromURL('http://google.com');
  break;
}
print $P->getPalette();
?>