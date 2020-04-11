<?php

/**
 * Uninstaller
 *
 * @author cleversoft <hello.cleversoft@gmail.com>
 * @license MIT
 */

// Make sure plugin container is available.
if (!class_exists('Cafe\Plugin', false)) {
    require __DIR__.'/cafe-lite.php';
}

// Delete all settings.
delete_option(Cafe\Plugin::OPTION_NAME);
