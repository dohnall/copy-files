<?php

namespace Dohnall\CopyFiles;

class CopyFiles
{
    public static function run()
    {
        $root = dirname(dirname(dirname(dirname(dirname(__FILE__)))));
        self::custom_copy($root.'/vendor/laravel/laravel', $root);
        self::removeDir($root.'/vendor/laravel/laravel');
    }

    public static function custom_copy($src, $dst) {

        // open the source directory
        $dir = opendir($src);

        // Make the destination directory if not exist
        @mkdir($dst);

        // Loop through the files in source directory
        while( $file = readdir($dir) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if ( is_dir($src . '/' . $file) )
                {

                    // Recursively calling custom copy function
                    // for sub directory
                    self::custom_copy($src . '/' . $file, $dst . '/' . $file);

                }
                else {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }

        closedir($dir);
    }
    
    public static function removeDir($target) {
        $directory = new RecursiveDirectoryIterator($target,  FilesystemIterator::SKIP_DOTS);
        $files = new RecursiveIteratorIterator($directory, RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($files as $file) {
            if (is_dir($file)) {
                rmdir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($target);
    }
}
