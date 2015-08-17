<?php
// ReactOS Source directory
$ROSDir = 'E:\ReactOS\\';

// Translations folder
$langDir = 'lang';

// We uses this to search origin language files
$originLang = 'US|En';

// Filename extension
$fileExt = 'rc';

// Ignored ROS strings list
$ignoredROSStrings = array('OK', 'Ok', '&OK', '[OK]\n', 'ReactOS', 'MS Shell Dlg', 'Arial', 'Static', 'STATIC', 'popup', 'http://www.reactos.org', 'DUMMY', 'Tab1', 'List1', 'List2', 'List3', 'Slider1', 'Text1', 'Text2', 'Text3', '?', '/', '...');

// Ignored Wine strings list (from http://fgouget.free.fr/wine-po/generic.spell.ignore)
$wineSpell = 'wine.spell.ignore';
$ignoredWineStrings = file($wineSpell, FILE_IGNORE_NEW_LINES);

// Maximize the default PHP time limit to 60 seconds
set_time_limit(60);

if (!file_exists($ROSDir))
{
    echo "ReactOS source path <b>$ROSDir</b> does not exist!";
    exit;
}
?>