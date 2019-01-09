<?php

require_once('class/BCGFontFile.php');
require_once('class/BCGColor.php');
require_once('class/BCGDrawing.php');

require_once('class/BCGcode128.barcode.php');

// The arguments are R, G, and B for color.
$colorFront = new BCGColor(0, 0, 0);
$colorBack = new BCGColor(255, 255, 255);

$font = new BCGFontFile('./font/Arial.ttf', 18);

$code = new BCGcode128();
$code->setScale(7); // Resolution
$code->setThickness(30); // Thickness
$code->setForegroundColor($colorFont); // Color of bars
$code->setBackgroundColor($colorBack); // Color of spaces
$code->setFont($font); // Font (or 0)
$code->parse('HELLO'); // Text

$drawing = new BCGDrawing('hello.png', $colorBack);
$drawing->setBarcode($code);
$drawing->draw();
$drawing->finish(BCGDrawing::IMG_FORMAT_PNG);



?>