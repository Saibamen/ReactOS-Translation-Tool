<?php
/* PROJECT:     ReactOS Translation Tool
 * LICENSE:     GPL
 * AUTHORS:     Adam Stachowicz <saibamenppl@gmail.com>
 * AUTHOR URL:  http://it-maniak.pl/
 */

include_once 'header.php';
include_once 'langcodes.php';
?>

<h1>Search for wrong encoded files</h1>

<div id="body">
<center>
    <form method="GET" class="form-horizontal">
        <fieldset>
            <legend>Please choose your language and the directories, where you want to search for untranslated strings, from the lists below.</legend>
            <div class="form-group">
                <label class="col-md-4 control-label" for="lang">Language:</label>
                <div class="col-md-4">
                    <select id="lang" name="lang" class="form-control" required="required">
                        <?php foreach ($langcodes as $language) {
    echo '<option value="'.$language[0].'" ';
    if (isset($_SESSION['lang']) && $language[0] == $_SESSION['lang']) {
        echo 'selected';
    }
    echo '> $language[1]</option>';
}?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-4 control-label" for="dir">Directories:</label>
                <div class="col-md-4">
                <select id="dir" name="dir" class="form-control">
                    <option value="1">base, boot</option>
                    <option value="2" <?php if (isset($_GET['dir']) && $_GET['dir'] == '2') {
    echo 'selected';
}?>>dll</option>
                    <option value="3" <?php if (isset($_GET['dir']) && $_GET['dir'] == '3') {
    echo 'selected';
}?>>media, subsystems, win32ss</option>
                    <option value="100" <?php if (isset($_GET['dir']) && $_GET['dir'] == '100') {
    echo 'selected';
}?>>All ReactOS Source dir</option>
                </select>
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </fieldset>
    </form>
</center>
<br>

<?php
if (isset($_GET['lang']) && !empty($_GET['lang']) && isset($_GET['dir']) && is_numeric($_GET['dir'])) {
    $it = new AppendIterator();

    // Switch for directories
    switch ($_GET['dir']) {
        case '1':
            $directories = [
                new RecursiveDirectoryIterator($ROSDir.'base/applications'),
                new RecursiveDirectoryIterator($ROSDir.'base/setup'),
                new RecursiveDirectoryIterator($ROSDir.'base/shell'),
                new RecursiveDirectoryIterator($ROSDir.'base/system'),
                new RecursiveDirectoryIterator($ROSDir.'boot/freeldr/fdebug'),
            ];
            break;

        case '2':
            $directories = [
                new RecursiveDirectoryIterator($ROSDir.'dll/cpl'),
                new RecursiveDirectoryIterator($ROSDir.'dll/shellext'),
                new RecursiveDirectoryIterator($ROSDir.'dll/win32'),
            ];
            break;

        case '3':
            $directories = [
                new RecursiveDirectoryIterator($ROSDir.'media/themes'),
                new RecursiveDirectoryIterator($ROSDir.'subsystems/mvdm/ntvdm'),
                new RecursiveDirectoryIterator($ROSDir.'win32ss/user'),
            ];
            break;

        case '100':
            $directories = [
                new RecursiveDirectoryIterator($ROSDir),
            ];
            break;

        default:
            echo 'Something is wrong! Please try again.';
            exit;
    }

    foreach ($directories as $directory) {
        $it->append(new RecursiveIteratorIterator($directory));
    }

    $regex = new RegexIterator($it, '/^.+'.$langDir.'.+('.$originLang.')\.'.$fileExt.'$/i', RecursiveRegexIterator::GET_MATCH);

    $allWrongEnc = 0;

    $lang = htmlspecialchars($_GET['lang']);
    // Search for eg. PL,Pl,pl
    $fileSearch = strtoupper($lang).','.ucfirst($lang).','.strtolower($lang);

    // UTF-8 BOM starts with EF BB BF
    define('UTF8_BOM', chr(0xEF).chr(0xBB).chr(0xBF));

    $regex->rewind();
    while ($regex->valid()) {
        if (!$regex->isDot()) {
            $file = glob($regex->getPathInfo().'/*{'.$fileSearch.'}*.'.$fileExt, GLOB_BRACE);

            $isFile = array_filter($file);

            if (!empty($isFile)) {
                $text = file_get_contents($file[0]);
                // UTF-8 is good
                if (mb_check_encoding($text, 'UTF-8')) {
                    $first3 = substr($text, 0, 3);
                    // But UTF-8 with BOM not!
                    if ($first3 === UTF8_BOM) {
                        echo 'Detected <b>UTF-8 BOM</b> in '.$file[0].'<br>';
                        $allWrongEnc++;
                    }
                } else {
                    // Other encoding
                    echo 'Detected <b>other encoding</b> in '.$file[0].'<br>';
                    $allWrongEnc++;
                }
            }
        }
        $regex->next();
    }
    echo "<h3>All files with wrong encoding: $allWrongEnc</h3>";
}

include_once 'footer.php';
