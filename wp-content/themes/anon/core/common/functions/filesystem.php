<?php
/**
 * Filesystem Functionality
 *
 * @package  Zoo Theme
 * @author   Zootemplate
 * @link     http://www.zootemplate.com
 *
 */
if (!function_exists('WP_Filesystem')) {
    require ABSPATH . 'wp-admin/includes/file.php';
}

// Connect ot current filesystem handler.
WP_Filesystem();

/**
 * Load all PHP files inside a directory
 *
 * !NOTICE: This function does not load files in any order.
 *
 * @param  string  $dir        Basedir.
 * @param  array   $exclude    An array of excluded files.
 * @param  bool    $recursive  Whether to load files from sub-folders or not.
 */
function zoo_require_php_files_from($dir, $exclude = [], $recursive = false)
{
    $base = rtrim($dir, DIRECTORY_SEPARATOR);
    $files = new DirectoryIterator($base);

    foreach ($files as $file) {
        if ($recursive && $file->isDir() && $file->isReadable()) {
            zoo_require_php_files_from($file, $exclude, $recursive);
            continue;
        }
        $filename = $file->getFilename();
        if ($file->isFile() && ('php' === $file->getExtension()) && !in_array($filename, $exclude)) {
            require $base . DIRECTORY_SEPARATOR . $filename;
        }
    }
}
