# Locate Binaries

[![Software License](https://img.shields.io/badge/license-MIT-418677.svg)](https://github.com/rosell-dk/locate-binary/blob/master/LICENSE)

Just a little class for locating binaries.
You need `exec()` or similar enabled for it to work.

Examples:

To locate installed `cwebp` binaries (found with either `which` or `whereis`):
```
$binariesFound = LocateBinaries::locateInstalledBinaries('cwebp');
```

Note that you get an array of matches - there may be more versions of a binary on a system.

PS: The library uses the [exec-with-fallback](https://github.com/rosell-dk/exec-with-fallback) library in order to be able to use alternatives to exec() when exec() is disabled.


The library also adds another method for locating binaries by peeking in common system paths, such as *usr/bin*.
However, beware that these dirs could be subject to open_basedir restrictions which would lead to warning entries in the error log.
Well warned, here it is:
```
$binariesFound = LocateBinaries::locateInCommonSystemPaths('convert');
```
