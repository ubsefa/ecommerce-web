<?php

namespace Helpers;

class FileHelper
{
    public static function getDirectories($path)
    {
        $directories = array();
        foreach (scandir($path) as $directory) {
            if ($directory == "." || $directory == "..") {
                continue;
            }
            if (is_dir($path . "/" . $directory)) {
                array_push($directories, $directory);
            }
        }
        return $directories;
    }

    public static function getFiles($path, $extension)
    {
        $files = array();
        foreach (scandir($path) as $file) {
            if ($file == "." || $file == "..") {
                continue;
            }
            if (isset($extension)) {
                if (pathinfo($file, PATHINFO_EXTENSION) == $extension) {
                    array_push($files, $file);
                }
            } else {
                array_push($files, $file);
            }
        }
        return $files;
    }
}