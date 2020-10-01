[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Saibamen/ReactOS-Translation-Tool/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Saibamen/ReactOS-Translation-Tool/?branch=master)
[![Maintainability](https://api.codeclimate.com/v1/badges/db55366000de6f6ad999/maintainability)](https://codeclimate.com/github/Saibamen/ReactOS-Translation-Tool/maintainability)
[![StyleCI](https://styleci.io/repos/39967290/shield)](https://styleci.io/repos/39967290)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/07387aac-72c8-460e-9aa8-d249c7a6a433/mini.png)](https://insight.sensiolabs.com/projects/07387aac-72c8-460e-9aa8-d249c7a6a433)

# ReactOS Translation Tool
Translation Tool for ReactOS

Homepage: https://reactos.org/

Author: [Adam Stachowicz](https://github.com/Saibamen) <saibamenppl@gmail.com> (http://it-maniak.pl/)

Features
----------

- Find missing translation RC files
- Find missing translation strings (**WIP**)
- Find wrong encoded files

Installation
----------

* Upload files to a web accessible directory on your server or hosting account
* Change the ReactOS source dir (`$ROSDir`) in `config.php` - must contain the base and dll directories

TODO
----------

- Fix pattern in diff.php to catch multiline strings with ""some text"" (part 1/2 - FIXED?)
