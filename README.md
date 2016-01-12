# php-sync-sftp

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
$ftp = new GlSyncFtp("192.168.0.1", "username", "password");

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
docker pull atmoz/sftp
docker run -v //c/tmp/ftp:/home/foo/share -p 2222:22 -d atmoz/sftp foo:123:1001
```

Change ftp server config in file : phpunit.xml.dist

Launch from command line :

```console
vendor\bin\phpunit
```

## License MIT

## Contact

Authors : Emmanuel ROECKER & Rym BOUCHAGOUR

[Web Development Blog - http://dev.glicer.com](http://dev.glicer.com)