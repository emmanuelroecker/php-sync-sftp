<?php
require __DIR__ . '/../vendor/autoload.php';

mkdir(__DIR__ . '/../tests/delete');

use GlSyncFtp\GlSyncFtp;

$ftp = new GlSyncFtp(FTP_SERVER_HOST, FTP_SERVER_PORT,  FTP_SERVER_USER, FTP_SERVER_PASSWORD);
$ftp->syncDirectory(__DIR__ . '/delete', '/test');
$ftp->disconnect();
