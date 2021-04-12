<?php
/* PROJECT:     ReactOS Translation Tool
 * LICENSE:     GPL
 * AUTHORS:     Adam Stachowicz <saibamenppl@gmail.com>
 * AUTHOR URL:  http://it-maniak.pl/
 */

include_once 'header.php';
include_once 'langcodes.php';
?>

<h1>Search missing RC files</h1>

<div id="body">
<center>
    <form method="GET" class="form-horizontal">
        <fieldset>
            <legend>Please choose your language code from the list below. </legend>
            <div class="form-group">
                <label class="col-md-4 control-label" for="lang">Language:</label>
                <div class="col-md-4">
                    <select id="lang" name="lang" class="form-control">
                        <option value="" selected disabled hidden>Choose here</option>
                        <?php foreach($langcodes as $language)
                        {
                            echo"<option value='$language[0]'>$language[1]</option>";
                        }?>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
        </fieldset>
    </form>
</center>
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
                $pathFromRoot = str_replace($ROSDir, '', $regex->getPathInfo());
                echo '<b>No translation</b> for path '.$regex->getPathInfo().' <a href="https://github.com/reactos/reactos/tree/master/'.$pathFromRoot.'"><strong>Go to GitHub</strong></a><br>';
                $missingFiles++;
            }
            $allEnglish++;
        }
        $regex->next();
    }

    if ($missingFiles <= 0) {
        echo '<h3><b>No</b> missing file translations found for your language!</h3>';
    }

    $languppercase = strtoupper($lang);

    echo "<h3>All translation RC files for english: $allEnglish</h3>";
    echo "<h3>Missing translations files for your language ($languppercase): $missingFiles</h3>";
}

include_once 'footer.php';
