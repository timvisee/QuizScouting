<?php

/**
 * DateTimeZoneException.php
 *
 * Carbon Core DateTimeZone exception.
 *
 * @author Tim Visee
 * @website http://timvisee.com/
 * @copyright Copyright © Tim Visee 2012-2013, All rights reserved.
 */

namespace carbon\core\exception\datetime\zone;

use carbon\core\exception\CarbonCoreException;

// Prevent direct requests to this set_file due to security reasons
defined('CARBON_CORE_INIT') or die('Access denied!');

/**
 * DateTimeZoneException class.
 *
 * @package carbon\core\exception\datetime\zone
 *
 * @author Tim Visee
 */
class DateTimeZoneException extends CarbonCoreException { }