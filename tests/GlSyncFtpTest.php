<?php
/**
 * Test GlSyncFtp
 *
 * PHP version 5.4
 *
 * @category  GLICER
 * @package   GlHtml\Tests
 * @author    Emmanuel ROECKER
 * @author    Rym BOUCHAGOUR
 * @copyright 2015 GLICER
 * @license   MIT
 * @link      http://dev.glicer.com/
 *
 * Created : 22/05/15
 * File : GlSyncFtpTest.php
 *
 */
namespace GlHtml\Tests;

use GlSyncFtp\GlSyncFtp;

/**
 * @covers \GlSyncFtpTest\GlSyncFtpTest
 * @backupGlobals disabled
 */
class GlSyncFtpTest extends \PHPUnit_Framework_TestCase
{
    public function testFtpDelete()
    {
        $ftp = new GlSyncFtp(FTP_SERVER_HOST, FTP_SERVER_USER, FTP_SERVER_PASSWORD);
        $ftp->syncDirectory(__DIR__ . '/delete', '/test', function($op,$message){});
    }

    public function testFtpNew()
    {
        $ftp = new GlSyncFtp(FTP_SERVER_HOST, FTP_SERVER_USER, FTP_SERVER_PASSWORD);
        $ftp->syncDirectory(__DIR__ . '/new', '/test',function($op,$message){});
    }

    public function testFtpUpdate()
    {
        $ftp = new GlSyncFtp(FTP_SERVER_HOST, FTP_SERVER_USER, FTP_SERVER_PASSWORD);
        $ftp->syncDirectory(__DIR__ . '/update', '/test',function($op,$message){});
    }
} 