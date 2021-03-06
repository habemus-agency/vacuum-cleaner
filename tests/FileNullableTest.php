<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use Habemus\Vacuum\Cleaner;
use Habemus\Vacuum\Filters\CustomFilter;
use Habemus\Vacuum\FileUpload;
use Habemus\Vacuum\File;

final class FileNullableTest extends TestCase {

    function test(){

        $file_path = __DIR__ . '/test-files/habemus.png';

        $file_data = [ 
            'name' => 'testfile.php',
            'tmp_name' => $file_path,
            'type' => mime_content_type($file_path),
            'error' => 0,
            'size' => filesize($file_path),
        ];

        $this->assertTrue(FileUpload::isNativeFileUploadData($file_data));

        $file = new File($file_path);

        $validator = new Cleaner([
            'test_file' => $file_data,
        ]);

        $validated = $validator->validate([
            'test_file' => 'nullable|file|max:100000|mimes:docx',
        ]);
        

        $this->assertTrue($validator->isValid());


        $validator = new Cleaner([
            'test_file' => $file,
        ]);

        $validated = $validator->validate([
            'test_file' => 'nullable|max:100000|mimes:png',
        ]);

        $this->assertFalse($file->isEmpty());

        $this->assertTrue($validator->isValid());

        $empty_file_data = [ 
            'name' => 'testfile.pdf',
            'tmp_name' => '',
            'type' => null,
            'error' => 4,
            'size' => 0,
        ];

        $validator = new Cleaner([
            'test_file' => $empty_file_data,
        ]);

        $validated = $validator->validate([
            'test_file' => 'nullable|file|max:100000|mimes:docx',
        ]);


        $this->assertTrue($validator->isValid());

    }
}