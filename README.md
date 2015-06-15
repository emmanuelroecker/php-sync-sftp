# php-sync-sftp

Synchronize local files with sftp (ssh ftp) server.

Compare dates between local and remote files, not using crc.(cyclic redundancy check),
and update only on remote server.

## Installation

This library can be found on [Packagist](https://packagist.org/packages/glicer/sync-sftp).

The recommended way to install is through [composer](http://getcomposer.org).

Edit your `composer.json` and add:

```json
{
    "require": {
       "glicer/sync-sftp": "dev-master"
    }
}
```

And install dependencies:

```bash
php composer.phar install
```

## Example
```php
     <?php
     // Must point to composer's autoload file.
     require 'vendor/autoload.php';

     use GlSyncFtp\GlSyncFtp;

     //init connection informations
     $ftp = new GlSyncFtp("192.168.0.1", "username", "password");

     //launch synchronisation between local and remote files
     $ftp->syncDirectory(
        "/home/user/localDirectory,     //local source directory
        "/host/web/remoteDirectory",    //remote destination directory
                function ($op, $nbr, $path) {
                    switch ($op) {
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
                            echo $nbr . "-" . $path;
                    }
                }
        );
```

## Running Tests

Local ssh ftp server must be installed (On Windows, you can use cygwin openssh)

Change ftp server config in file : phpunit.xml.dist

Launch from command line :

```console
vendor\bin\phpunit
```

## Contact

Authors : Emmanuel ROECKER & Rym BOUCHAGOUR

[Web Development Blog - http://dev.glicer.com](http://dev.glicer.com)