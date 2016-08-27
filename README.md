# php-sync-sftp

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/emmanuelroecker/php-sync-sftp/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/emmanuelroecker/php-sync-sftp/?branch=master)
[![Build Status](https://travis-ci.org/emmanuelroecker/php-sync-sftp.svg)](https://travis-ci.org/emmanuelroecker/php-sync-sftp)
[![Coverage Status](https://coveralls.io/repos/emmanuelroecker/php-sync-sftp/badge.svg?branch=master&service=github)](https://coveralls.io/github/emmanuelroecker/php-sync-sftp?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/11462785-d3ea-4ce5-a1ef-e870799ceb13/mini.png)](https://insight.sensiolabs.com/projects/11462785-d3ea-4ce5-a1ef-e870799ceb13)
[![Dependency Status](https://www.versioneye.com/user/projects/57c172d0968d640049e12195/badge.svg?style=flat-square)](https://www.versioneye.com/user/projects/57c172d0968d640049e12195)

Synchronize local files with sftp (ssh ftp) server.

Compare dates between local and remote files, not using crc.(cyclic redundancy check),
and update files only on remote sftp server.

## Installation

This library can be found on [Packagist](https://packagist.org/packages/glicer/sync-sftp).

The recommended way to install is through [composer](http://getcomposer.org).

Edit your `composer.json` and add:

```json
{
    "require": {
       "glicer/sync-sftp": "dev-master"
    },
    "prefer-stable": true,
    "minimum-stability": "dev"
}
```

And install dependencies:

```bash
php composer.phar install
```

## Example

```php
// Must point to composer's autoload file.
require 'vendor/autoload.php';

use GlSyncFtp\GlSyncFtp;

//init connection informations
$ftp = new GlSyncFtp("192.168.0.1", 22, "username", "password");

//launch synchronisation between local directory and remote files
$ftp->syncDirectory(
    "/home/user/localDirectory", //local source directory
        "/host/web/remoteDirectory", //remote destination directory
        function ($op, $path) {
            switch ($op) {
                case GlSyncFtp::CREATE_DIR:
                    echo "Create Directory : ";
                    break;
                case GlSyncFtp::DELETE_DIR:
                    echo "Delete Directory : ";
                    break;
                case GlSyncFtp::DELETE_FILE:
                    echo "Delete File : ";
                    break;
                case GlSyncFtp::UPDATE_FILE:
                    echo "Update File : ";
                    break;
                case GlSyncFtp::NEW_FILE:
                    echo "New File : ";
                    break;
                default:
            }
            echo $path . "\n";
        }
);

//launch synchronisation between list of local directories and remote directories
$fps->syncDirectories(
    [
        "/home/user/localDirectory1" => "/host/web/remoteDirectory1",
        "/home/user/localDirectory2" => "/host/web/remoteDirectory2"
    ],
        function ($src, $dst) {
            echo "Sync directory : $src with $dst";
        },
        function ($op, $path) {
            switch ($op) {
                case
                GlSyncFtp::CREATE_DIR:
                    echo "Create Directory : ";
                    break;
                case GlSyncFtp::DELETE_DIR:
                    echo "Delete Directory : ";
                    break;
                case GlSyncFtp::DELETE_FILE:
                    echo "Delete File : ";
                    break;
                case GlSyncFtp::UPDATE_FILE:
                    echo "Update File : ";
                    break;
                case GlSyncFtp::NEW_FILE:
                    echo "New File : ";
                    break;
                default:
            }
            echo $path . "\n";
        }
);
```

## Running Tests

Local ssh ftp server must be installed (On Windows, you can use cygwin openssh)

With [Docker](http://www.docker.com/) : 

```console
(cd docker ; sudo tar xpvzf docker.tar.gz)
docker pull rhasselbaum/scrappy-sftp
docker run -d --name sftp -p 2022:22 -v /$(pwd)/docker/sftp-root:/sftp-root -v /$(pwd)/docker/credentials:/creds rhasselbaum/scrappy-sftp
```

Launch from command line :

```console
vendor\bin\phpunit
```

Change ftp server config in file : phpunit.xml.dist

## License MIT

## Contact

Authors : Emmanuel ROECKER & Rym BOUCHAGOUR

[Web Development Blog - http://dev.glicer.com](http://dev.glicer.com)
