<?php
/* PROJECT:     ReactOS Translation Tool
 * LICENSE:     GPL
 * AUTHORS:     Adam Stachowicz <saibamenppl@gmail.com>
 * AUTHOR URL:	http://it-maniak.pl/
 */

include_once('header.php');
require_once('config.php');
?>

<h1>ReactOS Translation Tool - Search missing RC files</h1>

<div id="body">

<center>
Please type your language code. For example: pl for Polish, de for German<br/><br/>
<form method="POST" action="diff.php">
Language code:<br/>
<input type="text" name="lang" required="required" autofocus="autofocus" pattern="[A-Za-z]{2}" title="Two letter language code"/>
<br/><br/>
<input type="submit" value="Search"/>
</form>
</center>
<br/>

<?php

function diff_versions($leftContent, $rightContent) {
    $diff = true;
    $leftVersion = null;
    $rightVersion = null;

    $pattern = '/(LTEXT|PUSHBUTTON|CAPTION|GROUPBOX|RTEXT|MENUITEM|[0-9]+|IDS_.+|STRING_.+) .*"(.+)".*\R?/';

    if (preg_match_all($pattern, $leftContent, $matches) <= 0) {
        throw new Exception('Left content has no version line.');
    }

    $leftVersion = $matches[2];

    if (preg_match_all($pattern, $rightContent, $matches) <= 0) {
        throw new Exception('Right content has no version line.');
    }

    $rightVersion = $matches[2];

    return array(
        'diff' => $leftVersion === $rightVersion,
        'leftVersion' => $leftVersion,
        'rightVersion' => $rightVersion,
    );
}

$ROSDir = 'H:\ReactOS\\';

$fileContent1 = file_get_contents($ROSDir. 'dll\win32\wldap32\lang\wldap32_En.rc');
$fileContent2 = file_get_contents($ROSDir. 'dll\win32\wldap32\lang\wldap32_Pl.rc');

$array = diff_versions($fileContent1, $fileContent2);

$missing = $allEnglish = 0;

foreach ($array['leftVersion'] as $index => $english) {
	echo $english ." <b>--</b> ". $array['rightVersion'][$index];
	
	if ($english === $array['rightVersion'][$index])
	{
		echo " <b>Missing translation!!</b>";
		$missing++;
	}
	
	$allEnglish++;
	
	echo "<br>";
}

echo "<br><h2>Missing translations: ". $missing ."</h2>";
echo "All english strings: ". $allEnglish;

/*if (isset($_POST["lang"]) && !empty($_POST["lang"]))
{
	$lang = htmlspecialchars($_POST["lang"]);

	$langCode = strtoupper($lang) .",". ucfirst($lang) .",". strtolower($lang);
	
}*/

include_once('footer.php');
?>