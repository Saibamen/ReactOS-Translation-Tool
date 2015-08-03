<?php

function diff_versions($leftContent, $rightContent) {
    $diff = true;
    $leftVersion = null;
    $rightVersion = null;

    $pattern = '/(LTEXT|PUSHBUTTON|CAPTION|GROUPBOX|RTEXT) "(.*)"(.*)\R?/';

    if (preg_match_all($pattern, $leftContent, $matches) <= 1) {
        throw new Exception('Left content has no version line.');
    }

    $leftVersion = $matches[2];

    if (preg_match($pattern, $rightContent, $matches) !== 1) {
        throw new Exception('Right content has no version line.');
    }

    $rightVersion = $matches[2];

    return array(
        'diff' => $leftVersion === $rightVersion,
        'leftVersion' => $leftVersion,
        'rightVersion' => $rightVersion,
    );
}

$ROSDir = 'D:\ReactOS\\';

$fileContent11 = file_get_contents($ROSDir. 'base\applications\dxdiag\lang\en-US.rc');
$fileContent21 = file_get_contents($ROSDir. 'base\applications\dxdiag\lang\pl-PL.rc');

//var_dump(diff_versions($fileContent11, $fileContent21));

$array = diff_versions($fileContent11, $fileContent21);

foreach ($array as $key) {
	echo $key[0],"<br>";
}



?>