<?php

namespace Mpdf;

/**
 * Setup our unit testing functionality
 *
 * @package    mPDF
 * @author     Blue Liquid Designs <admin@blueliquiddesigns.com.au>
 * @copyright  2015 Blue Liquid Designs
 * @license    GPLv2
 * @since      GPL-2.0
 */

// useful for command line tests
define('MPDF_ROOT', __DIR__ . '/../');

require_once MPDF_ROOT . 'vendor/autoload.php';

// Create a new instance of the mPDF class
// We do this here to force the autoloader to include the actual file and its constants
// It means tests will have access to all of mPDF's constants without first creating a new instance (and everything is loaded)
new Mpdf();

// Tell users about our `--group snapshot` test which is not run when running `phpunit`
fwrite(STDERR, "The 'snapshot' test group is not run by default. To run, use `phpunit --group snapshot`.\n");
