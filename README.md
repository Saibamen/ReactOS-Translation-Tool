# ReactOS Translation Tool
Translation Tool for ReactOS

Homepage: https://reactos.org/

Started by Adam Stachowicz (saibamenppl@gmail.com, http://it-maniak.pl/)

Features
----------

- Find missing translation RC files
- Find missing translation strings (**WIP**)
- Find wrong encoded files

Installation
----------

* Upload files to a web accessible directory on your server or hosting account
* Change the ReactOS source dir ($ROSDir) in config.php - must contain the base and dll directories

TODO
----------

- Fix pattern in diff.php to catch multiline strings with ""some text"" (part 1/2)
- Switch language code from POST to GET?? (good for linking)