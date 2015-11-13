<?php
/* PROJECT:     ReactOS Translation Tool
 * LICENSE:     GPL
 * AUTHORS:     Adam Stachowicz <saibamenppl@gmail.com>
 * AUTHOR URL:	http://it-maniak.pl/
 */
include_once('header.php');
?>

<h1>Search missing RC files</h1>

<div id="body">
<?php require_once('config.php'); ?>

<center>
    <legend>Please type your <a href="https://beta.wikiversity.org/wiki/List_of_ISO_639-1_codes">language code in ISO 639-1</a>. For example: pl for Polish, de for German</legend>
    <form method="GET" class="form-horizontal">
        <fieldset>
            <div class="form-group">
                <label class="col-md-4 control-label" for="lang">Language code:</label>
                <div class="col-md-4">
                    <input type="text" value="<?php echo isset($_SESSION['lang']) ? $_SESSION['lang'] : "" ?>" id="lang" name="lang" class="form-control input-md" required="required" autofocus="autofocus" pattern="[A-Za-z]{2}" title="Two letter language code"/>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
        </fieldset>
    </form>
</center>
<br/>

<?php
if (isset($_GET["lang"]) && !empty($_GET["lang"]))
{
    $directory1 = new RecursiveDirectoryIterator($ROSDir. "base/applications");
    $directory2 = new RecursiveDirectoryIterator($ROSDir. "base/setup");
    $directory3 = new RecursiveDirectoryIterator($ROSDir. "base/shell");
    $directory4 = new RecursiveDirectoryIterator($ROSDir. "base/system");
    $directory5 = new RecursiveDirectoryIterator($ROSDir. "boot/freeldr/fdebug");

    $directory6 = new RecursiveDirectoryIterator($ROSDir. "dll/cpl");
    $directory7 = new RecursiveDirectoryIterator($ROSDir. "dll/shellext");
    $directory8 = new RecursiveDirectoryIterator($ROSDir. "dll/win32");

    $directory9 = new RecursiveDirectoryIterator($ROSDir. "media/themes");
    $directory10 = new RecursiveDirectoryIterator($ROSDir. "subsystems/mvdm/ntvdm");
    $directory11 = new RecursiveDirectoryIterator($ROSDir. "win32ss/user");
    // Search in source dir - only for test
    // $directory100 = new RecursiveDirectoryIterator($ROSDir);

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
    // Search in source dir - only for test
    // $it->append(new RecursiveIteratorIterator( $directory100));

    $regex = new RegexIterator($it, '/^.+'. $langDir .'.+('. $originLang .')\.'. $fileExt .'$/i', RecursiveRegexIterator::GET_MATCH);

    $allEnglish = $missingFiles = 0;

    $lang = htmlspecialchars($_GET["lang"]);
    // Search for eg. PL,Pl,pl
    $fileSearch = strtoupper($lang) .",". ucfirst($lang) .",". strtolower($lang);

    $regex->rewind();
    while($regex->valid())
    {
        if (!$regex->isDot())
        {
            $file = glob($regex->getPathInfo() ."/*{". $fileSearch ."}*.". $fileExt, GLOB_BRACE);

            $isFile = array_filter($file);
            if (empty($isFile))
            {
                echo '<b>No translation</b> for path '. $regex->getPathInfo() .'<br/>';
                $missingFiles++;
            }
            $allEnglish++;
        }
        $regex->next();
    }

    echo "<h3>All translation RC files for english: $allEnglish</h3>";
    echo "<h3>Missing translations files for your language ($lang): $missingFiles</h3>";
}

include_once('footer.php');
?>
