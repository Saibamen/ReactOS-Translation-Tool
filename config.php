<?php

// DO NOT touch this variable! php_uname() returns the type of the OS as string!
$uname = strtolower(php_uname());

// ReactOS Source directory - must contain the base and dll directories
$ROSDir = 'E:/ReactOS';

// Test - run trough all folders in Missing files function
$test = false;

$ROSDir = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $ROSDir);

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

// Use HTML5 <details> tag to show/hide missing translation strings (need to click to expand list)
$detailsTag = true;

// Print info about fully translated file
$showTranslationOK = false;

// Maximize the default PHP time limit to 60 seconds
set_time_limit(60);

if (!file_exists($ROSDir)) {
    echo "ReactOS source path <b>$ROSDir</b> does not exist!";

    if (strpos($uname, 'linux') !== false) {
        echo "<br><br>For <b><u>Linux users</u></b>: make sure you have set the right permission rights for both the ReactOS source code directory and the tool directory. Use <i>chmod</i> command and set their rights to <b>755</b> for both these directories if necessary. In any case you're using XAMPP for Linux, change the user and usergroup to yours. To do so read this: <a href='https://askubuntu.com/a/221593'>Change XAMPP's usergroup and username (link)</a>";
    } elseif (strpos($uname, 'win') !== false) {
        echo '<br><br>For <b><u>Windows users</u></b>: make sure the ReactOS source code path is <b>CORRECT</b> or that it is not <b>CORRUPT</b>';
    }

    exit;
}

if (substr($ROSDir, -1) !== DIRECTORY_SEPARATOR) {
    $ROSDir .= DIRECTORY_SEPARATOR;
}
