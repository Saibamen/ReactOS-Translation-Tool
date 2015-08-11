<?php
/* PROJECT:     ReactOS Translation Tool
 * LICENSE:     GPL
 * AUTHORS:     Adam Stachowicz <saibamenppl@gmail.com>
 * AUTHOR URL:	http://it-maniak.pl/
 */

include_once('header.php');
?>

<h1>Search missing translation strings</h1>

<div id="body">

<?php

require_once('config.php');

if (!(isset($_GET["dir"]) && is_numeric($_GET["dir"])))
{
	echo '<center>
	<form method="GET" class="form-horizontal">
	<fieldset>
	<legend>Select directories:</legend>
	<div class="form-group">
		<label class="col-md-4 control-label" for="selectbasic">Directories:</label>
		<div class="col-md-4">
		<select name="dir" class="form-control">
			<option value="1">base, boot</option> 
			<option value="2">dll</option>
			<option value="3">media, subsystems, win32ss</option>
		</select>
		</div>
	</div>
	<button type="submit" class="btn btn-primary">Go</button>
	</fieldset>
	</form>';
}
else
{
	echo '<center>
	<legend>Please type your language code. For example: pl for Polish, de for German</legend>
	<form method="POST" action="diff.php?dir='. $_GET["dir"] .'" class="form-horizontal">
	<fieldset>
		<div class="form-group">
			<label class="col-md-4 control-label" for="Nazwa">Language code:</label>
			<div class="col-md-4">
				<input type="text" name="lang" class="form-control input-md" required="required" autofocus="autofocus" pattern="[A-Za-z]{2}" title="Two letter language code"/>
			</div>
		</div>
		<button type="submit" class="btn btn-primary">Search</button>
	</fieldset>
	</form>
	</center>
	<br/>';

	if (isset($_POST["lang"]) && !empty($_POST["lang"]))
	{
		// Switch for directories
		switch ($_GET["dir"])
		{
			case "1":
				$directory1 = new RecursiveDirectoryIterator($ROSDir. "base\applications");
				$directory2 = new RecursiveDirectoryIterator($ROSDir. "base\setup");
				$directory3 = new RecursiveDirectoryIterator($ROSDir. "base\shell");
				$directory4 = new RecursiveDirectoryIterator($ROSDir. "base\system");
				$directory5 = new RecursiveDirectoryIterator($ROSDir. "boot\\freeldr\\fdebug");
				
				$it = new AppendIterator();
				$it->append(new RecursiveIteratorIterator( $directory1 ));
				$it->append(new RecursiveIteratorIterator( $directory2 ));
				$it->append(new RecursiveIteratorIterator( $directory3 ));
				$it->append(new RecursiveIteratorIterator( $directory4 ));
				$it->append(new RecursiveIteratorIterator( $directory5 ));
				break;

			case "2":
				$directory6 = new RecursiveDirectoryIterator($ROSDir. "dll\cpl");
				$directory7 = new RecursiveDirectoryIterator($ROSDir. "dll\shellext");
				$directory8 = new RecursiveDirectoryIterator($ROSDir. "dll\win32");
				
				$it = new AppendIterator();
				$it->append(new RecursiveIteratorIterator( $directory6 ));
				$it->append(new RecursiveIteratorIterator( $directory7 ));
				$it->append(new RecursiveIteratorIterator( $directory8 ));
				break;

			case "3":
				$directory9 = new RecursiveDirectoryIterator($ROSDir. "media\\themes");
				$directory10 = new RecursiveDirectoryIterator($ROSDir. "subsystems\mvdm\\ntvdm");
				$directory11 = new RecursiveDirectoryIterator($ROSDir. "win32ss\user");
				
				$it = new AppendIterator();
				$it->append(new RecursiveIteratorIterator( $directory9 ));
				$it->append(new RecursiveIteratorIterator( $directory10 ));
				$it->append(new RecursiveIteratorIterator( $directory11 ));
				break;
			
			// Search in source dir - only for test
			case "100":
				$directory1 = new RecursiveDirectoryIterator($ROSDir);
				
				$it = new AppendIterator();
				$it->append(new RecursiveIteratorIterator( $directory1 ));
				break;
			default:
				echo "Something is wrong! Please try again.";
				exit;
		}
		
		function diff_versions($leftContent, $rightContent)
		{
			$diff = true;
			$leftVersion = null;
			$rightVersion = null;

			// FIXME: Search multi-line with ""some text""
			$pattern = "/^(?!FONT)[^\"\\n]*\"\\K(?!\\s+\")([^\"]+)/m";

			if (preg_match_all($pattern, $leftContent, $matches) <= 0)
				throw new Exception('Left content has no version line.');

			$leftVersion = $matches[1];

			if (preg_match_all($pattern, $rightContent, $matches) <= 0)
				throw new Exception('Right content has no version line.');

			$rightVersion = $matches[1];

			return array(
				'diff' => array_intersect($leftVersion, $rightVersion),
				'leftVersion' => $leftVersion,
				'rightVersion' => $rightVersion,
			);
		}

		$regex = new RegexIterator($it, '/^.+'. $langDir .'.+('. $originLang .')\.'. $fileExt .'$/i', RecursiveRegexIterator::GET_MATCH);

		$allEnglish = $missingFiles = $missing = 0;

		$lang = htmlspecialchars($_POST["lang"]);
		$fileSearch = strtoupper($lang) .",". ucfirst($lang) .",". strtolower($lang);	

		$regex->rewind();
		while($regex->valid())
		{
			if (!$regex->isDot())
			{
				$file = glob($regex->getPathInfo() ."\*{". $fileSearch ."}*.". $fileExt, GLOB_BRACE);
				
				$isFile = array_filter($file);

				if (empty($isFile))
				{
					echo '<b>No translation</b> for path '. $regex->getPathInfo() .'<hr>';
					$missingFiles++;
				}
				else
				{
					$fileContent1 = file_get_contents($regex->key());
					$fileContent2 = file_get_contents($file[0]);
					
					$array = diff_versions($fileContent1, $fileContent2);
					
					if ($array['diff'])
					{
						echo $regex->getPathInfo() .'<br><br>';
						
						foreach ($array['leftVersion'] as $index => $english)
						{
							// Check if this same and ignore some words
							if ($english === $array['rightVersion'][$index] && !in_array($english, $ignoreString))
							{
								echo "<b>Missing translation: </b>";
								echo $english ."<br>";
								$missing++;
							}
						}
						echo "<hr>";
					}
				}
				$allEnglish++;
			}
			$regex->next();
		}

		echo "<h3>All translation RC files for english: $allEnglish</h3>";
		echo "<h3>Missing translation files for your language ($lang): $missingFiles</h3>";
		echo "<h3>Missing translations: $missing</h3>";
	}
}

include_once('footer.php');
?>