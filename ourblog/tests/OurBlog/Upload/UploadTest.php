<?php
/**
 * @group upload
 */
class OurBlog_UploadTest extends PHPUnit_Framework_TestCase
{
    public static $classGroups = array('upload');

    public function testUpload()
    {
        $_FILES = array(
            'file' => array(
                'name'     => 'ourats.png',
                'type'     => 'image/png',
                'size'     => 7386,
                'tmp_name' => '/tmp/php42up23',
                'error'    => UPLOAD_ERR_OK
            )
        );

        OurBlog_Upload::$unitTest = true;
        $baseDir = TESTS_ROOT_DIR . '/OurBlog/Upload';
        mkdir($baseDir . '/upload');
        copy($baseDir . '/ourats.png', $_FILES['file']['tmp_name']);

        $upload   = new OurBlog_Upload($baseDir . '/upload', 1);
        $filename = $upload->upload();

        $this->assertEquals('1-ourats.png', $filename);
        $this->assertFileEquals(
            $baseDir . '/upload/1-ourats.png',
            $baseDir . '/ourats.png'
        );

        unlink($baseDir . '/upload/1-ourats.png');
        rmdir($baseDir . '/upload');
    }
}
