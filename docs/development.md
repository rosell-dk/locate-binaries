# Development environment
You must have PHP.
On Windows, you can grab it here: https://www.php.net/downloads.php. Create a php.ini file from php.ini-development and enable extensions_dir and common extensions, such as zip.
You should also get composer, which you can get here: https://getcomposer.org/Composer-Setup.exe


# Running test cases

Usually:

```sh
composer test-no-cov
```


```ps1
cd C:\code\locate-binaries
composer install
.\vendor\bin\phpunit
```


# Running build-coverage script

In powershell:

```ps1
.\scripts\build-coverage.ps1
```


