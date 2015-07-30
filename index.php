<?php
/* PROJECT:     ReactOS Translation Tool
 * LICENSE:     GPL
 * AUTHORS:     Adam Stachowicz <saibamenppl@gmail.com>
 * AUTHOR URL:	http://it-maniak.pl/
 */

include_once('header.php');
require_once('config.php');

//$directory1 = new RecursiveDirectoryIterator($ROSDir);

$directory1 = new RecursiveDirectoryIterator($ROSDir. "base\applications");
$directory2 = new RecursiveDirectoryIterator($ROSDir. "base\setup");
$directory3 = new RecursiveDirectoryIterator($ROSDir. "base\shell");
$directory4 = new RecursiveDirectoryIterator($ROSDir. "base\system");

$directory5 = new RecursiveDirectoryIterator($ROSDir. "boot\\freeldr\\fdebug");

$directory6 = new RecursiveDirectoryIterator($ROSDir. "dll\cpl");
$directory7 = new RecursiveDirectoryIterator($ROSDir. "dll\shellext");
$directory8 = new RecursiveDirectoryIterator($ROSDir. "dll\win32");

$directory9 = new RecursiveDirectoryIterator($ROSDir. "media\\themes");
$directory10 = new RecursiveDirectoryIterator($ROSDir. "subsystems\mvdm\\ntvdm");
$directory11 = new RecursiveDirectoryIterator($ROSDir. "win32ss\user");

$it = new AppendIterator();
$it->append(new RecursiveIteratorIterator( $directory1 ));
$it->append(new RecursiveIteratorIterator( $directory2 ));
$it->append(new RecursiveIteratorIterator( $directory3 ));
$it->append(new RecursiveIteratorIterator( $directory4 ));
$it->append(new RecursiveIteratorIterator( $directory5 ));
$it->append(new RecursiveIteratorIterator( $directory6 ));
$it->append(new RecursiveIteratorIterator( $directory7 ));
$it->append(new RecursiveIteratorIterator( $directory8 ));
$it->append(new RecursiveIteratorIterator( $directory9 ));
$it->append(new RecursiveIteratorIterator( $directory10 ));
$it->append(new RecursiveIteratorIterator( $directory11 ));

$regex = new RegexIterator($it, '/^.+lang.+(US|En)\.rc$/i', RecursiveRegexIterator::GET_MATCH);

$allEnglish = 0;
$missingFiles = 0;

$lang = "pl";

$fileSearch = strtoupper($lang) .",". ucfirst($lang) .",". strtolower($lang);
// DEBUG
echo "Searching for: $fileSearch<br>";

$regex->rewind();

while($regex->valid())
{
    if (!$regex->isDot())
	{
		//echo 'Filename: ' . $regex->getFilename() . "<br>";
        //echo 'SubPathName: ' . $regex->getSubPathName() . "<br>";
		//echo 'PathInfo: ' . $regex->getPathInfo() . "<br><br>";
        //echo 'Key:         ' . $regex->key() . "<br>";
		

		$files = glob($regex->getPathInfo() . "\*{". $fileSearch ."}*.rc", GLOB_BRACE);
		
		$isFile = array_filter($files);

		if (empty($isFile))
		{
			echo '<b>No translation</b> for this path: '. $regex->getPathInfo() .'<br>';
			$missingFiles++;
		}
		
		/*$curr_dir = new RecursiveDirectoryIterator($regex->getPathInfo());
		$it2 = new RecursiveIteratorIterator( $curr_dir );
		$regex2 = new RegexIterator($it2, '/^.+(pl)\.rc$/i', RecursiveRegexIterator::GET_MATCH);
		
		while($regex2->valid()) {
			if (!$regex2->isDot()) {
				echo 'Keyyyyyyyyyy:         ' . $regex2->key() . "<br>";
			}
		}*/
    }
	
	$allEnglish++;
    $regex->next();
}

echo "<h2>All translation RC files for english: $allEnglish</h2>";
echo "<h2>Missing translations files for your language ($lang): $missingFiles</h2>";

include_once('footer.php');
?>