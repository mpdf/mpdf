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

require_once __DIR__ . '/../vendor/autoload.php';

// Create a new instance of the mPDF class
// We do this here to force the autoloader to include the actual file and its constants
// It means tests will have access to all of mPDF's constants without first creating a new instance (and everything is loaded)
new Mpdf();
