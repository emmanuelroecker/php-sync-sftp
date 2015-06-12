# php-sync-sftp


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