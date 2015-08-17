<?php
// ReactOS Source directory - must contain the base and dll directories
$ROSDir = 'E:/ReactOS/';

// Translations folder
$langDir = 'lang';

// We uses this to search origin language files
$originLang = 'US|En';

// Filename extension
$fileExt = 'rc';

// Ignored ROS strings list
$ROSSpellFilename = 'ReactOS.spell';

// Ignored Wine strings list (from http://fgouget.free.fr/wine-po/generic.spell.ignore)
$wineSpellFilename = 'Wine.spell';

// Maximize the default PHP time limit to 60 seconds
set_time_limit(60);

if (!file_exists($ROSDir))
{
    echo "ReactOS source path <b>$ROSDir</b> does not exist!";
    exit;
}
?>