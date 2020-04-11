<?php

/**
 * Uninstaller
 *
 * @author cleversoft <hello.cleversoft@gmail.com>
 * @license MIT
 */

// Make sure plugin container is available.
if (!class_exists('CafePro\Plugin', false)) {
    require __DIR__.'/cafe-pro.php';
}

// Delete all settings.
delete_option(CafePro\Plugin::OPTION_NAME);
