<?php

namespace Mpdf;

class FileSystem
{
    public function fopen($filename, $mode, $use_include_path = null, $context = null)
    {
        return \fopen($filename, $mode, $use_include_path, $context);
    }

    public function silentFopen($filename, $mode, $use_include_path = null, $context = null)
    {
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

    public function file_exists($filename)
    {
        return \file_exists($filename);
    }

    public function is_writable($filename)
    {
        return \is_writeable($filename);
    }

    public function is_dir($filename)
    {
        return \is_dir($filename);
    }

    public function mkdir($filename)
    {
        return \mkdir($filename);
    }

    public function unlink($filename)
    {
        return \unlink($filename);
    }

    public function chmod($filename, $mode)
    {
        return \chmod($filename, $mode);
    }

    public function file($filename)
    {
        return \file($filename);
    }

    public function is_file($filename)
    {
        return \is_file($filename);
    }

    public function SilentFile($filename)
    {
        return @\file($filename);
    }

    public function filemtime($filename)
    {
        return \filemtime($filename);
    }

    public function realpath($filename)
    {
        return \realpath($filename);
    }

    public function stat($filename)
    {
        return \stat($filename);
    }

    public function curl_init($url)
    {
        return \curl_init($url);
    }

    public function curl_exec($ch)
    {
        return \curl_exec($ch);
    }

    public function curl_close($ch)
    {
        return \curl_close($ch);
    }

    public function curl_error($ch)
    {
        return \curl_error($ch);
    }
}