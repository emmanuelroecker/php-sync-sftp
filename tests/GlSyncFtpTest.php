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
namespace GlSyncFtp\Tests;

use GlSyncFtp\GlSyncFtp;

/**
 * @covers        \GlSyncFtp\GlSyncFtp
 * @backupGlobals disabled
 */
class GlSyncFtpTest extends \PHPUnit_Framework_TestCase
{
    private function assertAndRemove($elem, array &$list)
    {
        if (($key = array_search($elem, $list)) !== false) {
            unset($list[$key]);
        } else {
            $this->fail($elem);
        }
    }

    public function testFtpDeleteAll()
    {
        $ftp = new GlSyncFtp(FTP_SERVER_HOST, FTP_SERVER_PORT, FTP_SERVER_USER, FTP_SERVER_PASSWORD);
        if (!is_dir(__DIR__ . '/delete')) {
            mkdir(__DIR__ . '/delete');
        };
        $ftp->syncDirectory(__DIR__ . '/delete', '/data');
        $files = [];
        $dirs  = [];
        $ftp->getAllFiles('/data', $files, $dirs);
        $ftp->disconnect();

        $filesname = array_keys($files);
        $dirsname  = array_keys($dirs);

        $this->assertCount(0, $filesname, var_export($filesname, true));
        $this->assertCount(0, $dirsname, var_export($dirsname, true));
    }

    public function testFtpNew()
    {
        $ftp = new GlSyncFtp(FTP_SERVER_HOST, FTP_SERVER_PORT, FTP_SERVER_USER, FTP_SERVER_PASSWORD);

        $listDirs  = ["/data/dir1", "/data/dir3"];
        $listFiles = ["/data/dir3/Test4.txt", "/data/dir1/test1.txt", "/data/test2.txt"];
        $ftp->syncDirectory(
            __DIR__ . '/new',
                '/data',
                function ($op, $path) use (&$listDirs, &$listFiles) {
                    switch ($op) {
                        case GlSyncFtp::CREATE_DIR:
                            $this->assertAndRemove($path, $listDirs);
                            break;
                        case GlSyncFtp::NEW_FILE:
                            $this->assertAndRemove($path, $listFiles);
                            break;
                        default:
                            $this->fail($op);
                    }
                }
        );

        if (count($listDirs) > 0) {
            $this->fail("bad dirs " . var_export($listDirs, true));
        };
        if (count($listFiles) > 0) {
            $this->fail("bad files " . var_export($listFiles, true));
        };

        $files = [];
        $dirs  = [];
        $ftp->getAllFiles('/data', $files, $dirs);
        $ftp->disconnect();

        $filesname = array_keys($files);
        $dirsname  = array_keys($dirs);

        $this->assertCount(3, $filesname);
        $this->assertContains("/dir1/test1.txt", $filesname);
        $this->assertContains("/dir3/Test4.txt", $filesname);
        $this->assertContains("/test2.txt", $filesname);
        $this->assertCount(2, $dirsname);
        $this->assertContains("/dir1", $dirsname);
        $this->assertContains("/dir3", $dirsname);
    }

    public function testFtpUpdate()
    {
        $ftp = new GlSyncFtp(FTP_SERVER_HOST, FTP_SERVER_PORT, FTP_SERVER_USER, FTP_SERVER_PASSWORD);

        $deleteFiles = ["/data/dir1/test1.txt", "/data/dir3/Test4.txt"];
        $deleteDirs  = ["/data/dir1"];
        $createDirs  = ["/data/dir2"];
        $updateFiles = ["/data/test2.txt"];
        $newFiles    = ["/data/dir2/test3.txt", "/data/test2.txt", "/data/dir3/test4.txt"];
        $ftp->syncDirectory(
            __DIR__ . '/update',
                '/data',
                function ($op, $path) use (&$deleteFiles, &$deleteDirs, &$createDirs, &$updateFiles, &$newFiles) {
                    switch ($op) {
                        case GlSyncFtp::DELETE_FILE:
                            $this->assertAndRemove($path, $deleteFiles);
                            break;
                        case GlSyncFtp::DELETE_DIR:
                            $this->assertAndRemove($path, $deleteDirs);
                            break;
                        case GlSyncFtp::CREATE_DIR:
                            $this->assertAndRemove($path, $createDirs);
                            break;
                        case GlSyncFtp::UPDATE_FILE:
                            $this->assertAndRemove($path, $updateFiles);
                            break;
                        case GlSyncFtp::NEW_FILE:
                            $this->assertAndRemove($path, $newFiles);
                            break;
                        default:
                            $this->fail();
                    }
                }
        );

        $files = [];
        $dirs  = [];
        $ftp->getAllFiles('/data', $files, $dirs);
        $ftp->disconnect();

        $filesname = array_keys($files);
        $dirsname  = array_keys($dirs);

        $this->assertCount(3, $filesname);
        $this->assertContains("/dir2/test3.txt", $filesname);
        $this->assertContains("/dir3/test4.txt", $filesname);
        $this->assertContains("/test2.txt", $filesname);
        $this->assertCount(2, $dirsname);
        $this->assertContains("/dir2", $dirsname);
        $this->assertContains("/dir3", $dirsname);
    }

    public function testFtpDelete()
    {
        $ftp         = new GlSyncFtp(FTP_SERVER_HOST, FTP_SERVER_PORT, FTP_SERVER_USER, FTP_SERVER_PASSWORD);
        $deleteDirs  = ["/data/dir2", "/data/dir3"];
        $deleteFiles = ["/data/dir2/test3.txt", "/data/test2.txt", "/data/dir3/test4.txt"];
        $ftp->syncDirectory(
            __DIR__ . '/delete',
                '/data',
                function ($op, $path) use (&$deleteDirs, &$deleteFiles) {
                    switch ($op) {
                        case GlSyncFtp::DELETE_DIR:
                            $this->assertAndRemove($path, $deleteDirs);
                            break;
                        case GlSyncFtp::DELETE_FILE:
                            $this->assertAndRemove($path, $deleteFiles);
                            break;
                        default:
                            $this->fail();
                    }
                }
        );

        $files = [];
        $dirs  = [];
        $ftp->getAllFiles('/data', $files, $dirs);
        $ftp->disconnect();

        $this->assertCount(0, $files);
        $this->assertCount(0, $dirs);
    }

    public function testFtpDirectories()
    {
        $list = [
            __DIR__ . '/new'    => '/data',
            __DIR__ . '/update' => '/data',
            __DIR__ . '/delete' => '/data'
        ];

        $ftp        = new GlSyncFtp(FTP_SERVER_HOST, FTP_SERVER_PORT, FTP_SERVER_USER, FTP_SERVER_PASSWORD);
        $createDirs = ["/data/dir1", "/data/dir3", "/data/dir2"];
        $newFiles   = [
            "/data/dir1/test1.txt",
            "/data/test2.txt",
            "/data/dir3/test4.txt",
            "/data/dir3/Test4.txt",
            "/data/dir2/test3.txt"
        ];
        $ftp->syncDirectories(
            $list,
                function ($src, $dst) {
                },
                function ($op, $path) use (&$createDirs, &$newFiles) {
                    switch ($op) {
                        case GlSyncFtp::CREATE_DIR:
                            $this->assertAndRemove($path, $createDirs);
                            break;
                        case GlSyncFtp::NEW_FILE:
                            $this->assertAndRemove($path, $newFiles);
                            break;
                        default:
                    }
                }
        );

        $files = [];
        $dirs  = [];
        $ftp->getAllFiles('/data', $files, $dirs);
        $ftp->disconnect();

        $this->assertCount(0, $files);
        $this->assertCount(0, $dirs);
    }
} 
