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
 * @covers        \GlSyncFtpTest\GlSyncFtpTest
 * @backupGlobals disabled
 */
class GlSyncFtpTest extends \PHPUnit_Framework_TestCase
{

    public function testFtpNew()
    {
        $ftp = new GlSyncFtp(FTP_SERVER_HOST, FTP_SERVER_USER, FTP_SERVER_PASSWORD);
        $ftp->syncDirectory(
            __DIR__ . '/new',
                '/test',
                function ($op, $nbr, $path) {
                    switch ($op) {
                        case GlSyncFtp::CREATE_DIR:
                            $this->assertEquals(0, $nbr);
                            $this->assertEquals("/test/dir1", $path);
                            break;
                        case GlSyncFtp::NEW_FILE:
                            switch ($nbr) {
                                case 0:
                                    $this->assertEquals("/test/dir1/test1.txt", $path);
                                    break;
                                case 1:
                                    $this->assertEquals("/test/test2.txt", $path);
                                    break;
                                default:
                                    $this->fail();
                            }
                            break;
                        default:
                            $this->fail();
                    }
                }
        );

        $files = [];
        $dirs  = [];
        $ftp->getAllFiles('/test', $files, $dirs);

        $filesname = array_keys($files);
        $dirsname = array_keys($dirs);

        $this->assertCount(2, $filesname);
        $this->assertEquals("/dir1/test1.txt", $filesname[0]);
        $this->assertEquals("/test2.txt", $filesname[1]);
        $this->assertCount(1, $dirsname);
        $this->assertEquals("/dir1", $dirsname[0]);
    }

    public function testFtpUpdate()
    {
        $ftp = new GlSyncFtp(FTP_SERVER_HOST, FTP_SERVER_USER, FTP_SERVER_PASSWORD);
        $ftp->syncDirectory(
            __DIR__ . '/update',
                '/test',
                function ($op, $nbr, $path) {
                    switch ($op) {
                        case GlSyncFtp::DELETE_FILE:
                            $this->assertEquals("/test/dir1/test1.txt", $path);
                            break;
                        case GlSyncFtp::DELETE_DIR:
                            $this->assertEquals("/test/dir1", $path);
                            break;
                        case GlSyncFtp::CREATE_DIR:
                            $this->assertEquals("/test/dir2", $path);
                            break;
                        case GlSyncFtp::UPDATE_FILE:
                            $this->assertEquals("/test/test2.txt", $path);
                            break;
                        case GlSyncFtp::NEW_FILE:
                            switch ($nbr) {
                                case 0:
                                    $this->assertEquals("/test/dir2/test3.txt", $path);
                                    break;
                                case 1:
                                    $this->assertEquals("/test/test2.txt", $path);
                                    break;
                                default:
                                    $this->fail();
                            }
                            break;
                        default:
                            $this->fail();
                    }
                }
        );

        $files = [];
        $dirs  = [];
        $ftp->getAllFiles('/test',$files, $dirs);

        $filesname = array_keys($files);
        $dirsname = array_keys($dirs);

        $this->assertCount(2, $filesname);
        $this->assertEquals("/dir2/test3.txt", $filesname[0]);
        $this->assertEquals("/test2.txt", $filesname[1]);
        $this->assertCount(1, $dirsname);
        $this->assertEquals("/dir2", $dirsname[0]);
    }

    public function testFtpDelete()
    {
        $ftp = new GlSyncFtp(FTP_SERVER_HOST, FTP_SERVER_USER, FTP_SERVER_PASSWORD);
        $ftp->syncDirectory(
            __DIR__ . '/delete',
                '/test',
                function ($op, $nbr, $path) {
                    switch ($op) {
                        case GlSyncFtp::DELETE_DIR:
                            $this->assertEquals("/test/dir2", $path);
                            break;
                        case GlSyncFtp::DELETE_FILE:
                            switch ($nbr) {
                                case 0:
                                    $this->assertEquals("/test/dir2/test3.txt", $path);
                                    break;
                                case 1:
                                    $this->assertEquals("/test/test2.txt", $path);
                                    break;
                                default:
                                    $this->fail();
                            }
                            break;
                        default:
                            $this->fail();
                    }
                }
        );

        $files = [];
        $dirs  = [];
        $ftp->getAllFiles('/test', $files, $dirs);

        $this->assertCount(0, $files);
        $this->assertCount(0, $dirs);
    }
} 