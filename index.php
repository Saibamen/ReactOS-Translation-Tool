<!DOCTYPE html>

<?php
/* PROJECT:     ReactOS Translation Tool
 * LICENSE:     GPL
 * AUTHORS:     Adam Stachowicz <saibamenppl@gmail.com>
 * AUTHOR URL:  http://it-maniak.pl/
 */

include_once 'header.php';
?>

<h1>Search missing RC files</h1>

<div id="body">
    <center>
        <form method="GET" class="form-horizontal">
            <fieldset>
                <legend>Please type your <a href="https://beta.wikiversity.org/wiki/List_of_ISO_639-1_codes">language code in ISO 639-1</a>. For example: pl for Polish, de for German</legend>
                <div class="form-group">
                    <label class="col-md-4 control-label" for="lang">Language code:</label>
                    <div class="col-md-4">
                        <input type="text" value="<?php echo isset($_SESSION['lang']) ? $_SESSION['lang'] : '' ?>" id="lang" name="lang" class="form-control input-md" required="required" autofocus="autofocus" pattern="[A-Za-z]{2}" title="Two letter language code"/>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Search</button>
            </fieldset>
        </form>
    </center>
</div>
<br>

<?php
if (isset($_GET['lang']) && !empty($_GET['lang'])) {
    $it = new AppendIterator();

    if ($test === false) {
        $directories = [
            new RecursiveDirectoryIterator($ROSDir.'base/applications'),
            new RecursiveDirectoryIterator($ROSDir.'base/setup'),
            new RecursiveDirectoryIterator($ROSDir.'base/shell'),
            new RecursiveDirectoryIterator($ROSDir.'base/system'),
            new RecursiveDirectoryIterator($ROSDir.'boot/freeldr/fdebug'),
            new RecursiveDirectoryIterator($ROSDir.'dll/cpl'),
            new RecursiveDirectoryIterator($ROSDir.'dll/shellext'),
            new RecursiveDirectoryIterator($ROSDir.'dll/win32'),
            new RecursiveDirectoryIterator($ROSDir.'media/themes'),
            new RecursiveDirectoryIterator($ROSDir.'subsystems/mvdm/ntvdm'),
            new RecursiveDirectoryIterator($ROSDir.'win32ss/user'),
        ];
    } else {
        // Search in source dir - only for test
        $directories = [
            new RecursiveDirectoryIterator($ROSDir),
        ];
    }

    foreach ($directories as $directory) {
        $it->append(new RecursiveIteratorIterator($directory));
    }

    $regex = new RegexIterator($it, '/^.+'.$langDir.'.+('.$originLang.')\.'.$fileExt.'$/i', RecursiveRegexIterator::GET_MATCH);

    $allEnglish = $missingFiles = 0;

    $lang = htmlspecialchars($_GET['lang']);
    // Search for eg. PL,Pl,pl
    $fileSearch = strtoupper($lang).','.ucfirst($lang).','.strtolower($lang);

    $regex->rewind();
    while ($regex->valid()) {
        if (!$regex->isDot()) {
            $file = glob($regex->getPathInfo().'/*{'.$fileSearch.'}*.'.$fileExt, GLOB_BRACE);

            $isFile = array_filter($file);
            if (empty($isFile)) {
                echo '<b>No translation</b> for path '.$regex->getPathInfo().'<br>';
                $missingFiles++;
            }
            $allEnglish++;
        }
        $regex->next();
    }

    if ($missingFiles <= 0) {
        echo '<h3><b>No</b> missing file translations found for your language!</h3>';
    }

    echo "<h3>All translation RC files for english: $allEnglish</h3>";
    echo "<h3>Missing translations files for your language ($lang): $missingFiles</h3>";
}

include_once 'footer.php';
