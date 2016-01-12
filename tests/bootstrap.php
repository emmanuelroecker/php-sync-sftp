<?php
require __DIR__ . '/../vendor/autoload.php';

use GlSyncFtp\GlSyncFtp;

$ftp = new GlSyncFtp(FTP_SERVER_HOST, FTP_SERVER_USER, FTP_SERVER_PASSWORD);
$ftp->syncDirectory(__DIR__ . '/delete', '/test');
$ftp->disconnect();
