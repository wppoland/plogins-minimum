<?php
/**
 * Constants needed by PHPStan to analyse the plugin without bootstrapping WordPress.
 *
 * @package Minimum
 */

declare(strict_types=1);

namespace {
    if (! defined('ABSPATH')) {
        define('ABSPATH', '/tmp/wordpress/');
    }
}

namespace Minimum {
    if (! defined('Minimum\\VERSION')) {
        define('Minimum\\VERSION', '0.1.0');
    }
    if (! defined('Minimum\\PLUGIN_FILE')) {
        define('Minimum\\PLUGIN_FILE', '/tmp/minimum/minimum.php');
    }
    if (! defined('Minimum\\PLUGIN_DIR')) {
        define('Minimum\\PLUGIN_DIR', '/tmp/minimum');
    }
}
