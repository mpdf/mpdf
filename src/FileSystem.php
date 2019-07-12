<?php

namespace Mpdf;

class FileSystem
{
    public function fopen($filename, $mode, $use_include_path = null, $context = null) {
        return \fopen($filename, $mode, $use_include_path, $context);
    }

    public function silentFopen($filename, $mode, $use_include_path = null, $context = null) {
        return @\fopen($filename, $mode, $use_include_path, $context);
    }

    public function file_get_contents($filename, $use_include_path = false, $context = null, $offset = 0)
    {
        return \file_get_contents($filename, $use_include_path, $context, $offset);
    }

    public function file_put_contents($filename, $data, $flags = 0, $context = null)
    {
        return \file_put_contents($filename, $data, $flags, $context);
    }

    public function silentFile_get_contents($filename, $use_include_path = false, $context = null, $offset = 0)
    {
        return @\file_get_contents($filename, $use_include_path, $context, $offset);
    }

    public function file_exists($filename) {
        return \file_exists($filename);
    }

    // file_exists
    // is_writable
    // is_dir
    // mkdir
    // unlink
    // rmdir
}