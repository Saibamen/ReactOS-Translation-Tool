<?php
/* PROJECT:     ReactOS Translation Tool
 * LICENSE:     GPL
 * AUTHORS:     Adam Stachowicz <saibamenppl@gmail.com>
 * AUTHOR URL:  http://it-maniak.pl/
 */

include_once 'header.php';
include_once 'langcodes.php';
?>

<h1>Search missing translation strings</h1>

<div id="body">
<center>
    <form method="GET" class="form-horizontal">
        <fieldset>
            <legend>Please choose your language and the directories, where you want to search for untranslated strings, from the lists below.</legend>
            <div class="form-group">
                <label class="col-md-4 control-label" for="lang">Language:</label>
                <div class="col-md-4">
                    <select id="lang" name="lang" class="form-control" required="required">
                        <?php
                        foreach ($langcodes as $language) {
                            echo '<option value="'.$language[0].'" ';
                            if (isset($_SESSION['lang']) && $language[0] == $_SESSION['lang']) {
                                echo 'selected';
                            }
                            echo '>'.$language[1].'</option>';
                        }
                        ?>
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

                            function diff_versions($leftContent, $rightContent)
                            {
                                $rightVersion = null;

                                $pattern = '/^(?!FONT|\\s*\\*|\\#include|\\s*ICON)[^"\\n]*"\\K(?!\\s*(?:"|\\n))([^"]+)/m';

                                if (preg_match_all($pattern, $leftContent, $matches) <= 0) {
                                    throw new Exception('Left content has no version line.');
                                }

                                $leftVersion = $matches[1];

                                if (preg_match_all($pattern, $rightContent, $matches) <= 0) {
                                    throw new Exception('Right content has no version line.');
                                }

                                $rightVersion = $matches[1];

                                return [
                                    'diff'         => array_intersect($leftVersion, $rightVersion),
                                    'leftVersion'  => $leftVersion,
                                    'rightVersion' => $rightVersion,
                                ];
                            }

                            function exceptions_error_handler($severity, $message, $filename, $lineno)
                            {
                                if (error_reporting() == 0) {
                                    return;
                                }
                                if (error_reporting() & $severity) {
                                    throw new ErrorException($message, 0, $severity, $filename, $lineno);
                                }
                            }

                            set_error_handler('exceptions_error_handler');

                            $regex = new RegexIterator($it, '/^.+'.$langDir.'.+('.$originLang.')\.'.$fileExt.'$/i', RecursiveRegexIterator::GET_MATCH);

                            $missing = $allStrings = 0;

                            $lang = htmlspecialchars($_GET['lang']);
                            // Search for eg. PL,Pl,pl
                            $fileSearch = strtoupper($lang).','.ucfirst($lang).','.strtolower($lang);

                            // ReactOS and Wine Strings - array
                            $ignoredROSStrings = file($ROSSpellFilename, FILE_IGNORE_NEW_LINES);
                            $ignoredWineStrings = file($wineSpellFilename, FILE_IGNORE_NEW_LINES);

                            $regex->rewind();
                            while ($regex->valid()) {
                                if (!$regex->isDot()) {
                                    $file = glob($regex->getPathInfo().'/*{'.$fileSearch.'}*.'.$fileExt, GLOB_BRACE);

                                    $isFile = array_filter($file);

                                    if (empty($isFile)) {
                                        echo '<b>No translation</b> for path '.$regex->getPathInfo().'<hr>';
                                    } else {
                                        $fileContent1 = file_get_contents($regex->key());
                                        $fileContent2 = file_get_contents($file[0]);

                                        $array = diff_versions($fileContent1, $fileContent2);

                                        if ($array['diff']) {
                                            $currentMissing = $missing;
                                            $missingTextMessage = null;

                                            foreach ($array['leftVersion'] as $index => $english) {
                                                // Catch offset error
                                                try {
                                                    // Check if this same and ignore some words
                                                    if ($english === $array['rightVersion'][$index] && !in_array($english, $ignoredROSStrings) && !in_array($english, $ignoredWineStrings)) {
                                                        $missingTextMessage .= '<b>Missing translation:</b> '.htmlspecialchars($english).'<br>';
                                                        $missing++;
                                                    }
                                                    $allStrings++;
                                                } catch (Exception $e) {
                                                    $missingTextMessage .= 'Missing stuff in your language<br>';
                                                    $allStrings++;
                                                    $missing++;
                                                }
                                            }

                                            if ($currentMissing == $missing) {
                                                $messageForFile = 'Seems <b>OK :)</b> Some strings was ignored by ReactOS and Wine spell files.<br>';
                                            } elseif ($detailsTag) {
                                                $messageForFile = '<details open><summary><strong>Click here to see/hide missing translations in file ('.($missing - $currentMissing).')</strong></summary>'.$missingTextMessage.'</details>';
                                            } else {
                                                $messageForFile = $missingTextMessage;
                                            }

                                            if ($currentMissing != $missing || $showTranslationOK) {
                                                $pathFromRoot = str_replace($ROSDir, '', $regex->getPathInfo());
                                                echo $regex->getPathInfo().' (<a href="https://github.com/reactos/reactos/tree/master/'.$pathFromRoot.'">Go to GitHub</a>)<br><br>'.$messageForFile.'<hr>';
                                            }
                                        }
                                    }
                                }
                                $regex->next();
                            }

                            $languppercase = strtoupper($lang);

                            echo "<h3>All strings for english: $allStrings</h3>";
                            echo "<h3>Missing translations for your language ($languppercase): $missing</h3>";

                            // Rounded percent
                            $percent = round((($allStrings - $missing) / $allStrings) * 100, 2);
                            echo "<h3>Language $languppercase translated in $percent%</h3>";
                        }

include_once 'footer.php';
