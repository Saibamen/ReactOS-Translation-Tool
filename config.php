<?php
// ReactOS Source directory
$ROSDir = 'E:\ReactOS\\';

// Translations folder
$langDir = 'lang';

// We uses this to search origin language files
$originLang = 'US|En';

// Filename extension
$fileExt = 'rc';

// Ignore string list
$ignoreString = array('OK', 'Ok', '&OK', '[OK]\n', 'ReactOS', 'MS Shell Dlg', 'Arial', 'Static', 'STATIC', 'popup', 'http://www.reactos.org', 'DUMMY', 'Tab1', 'List1', 'List2', 'List3', 'Slider1', 'Text1', 'Text2', 'Text3');

if (!file_exists($ROSDir))
{
    echo "ReactOS source path <b>$ROSDir</b> does not exist!";
    exit;
}
?>