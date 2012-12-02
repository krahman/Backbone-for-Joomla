<?php

/**
 * @package     Extly.Library
 * @subpackage  lib_extly - Extly Framework
 *
 * @author      Prieco S.A. <support@extly.com>
 * @copyright   Copyright (C) 2007 - 2012 Prieco, S.A. All rights reserved.
 * @license     http://http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @link        http://www.extly.com http://support.extly.com
 */
// No direct access
defined('_JEXEC') or die('Restricted access');

if (!defined('EXTLY_VERSION'))
{

	/**
	 * @name EXTLY_VERSION
	 */
	define('EXTLY_VERSION', '1.0.0');

	if (!defined('DS'))
	{
		define('DS', DIRECTORY_SEPARATOR);
	}

}

JLoader::import('extly.core.extly');

/**
 * This is the base class for the Extlyframework.
 *
 * @package     Extly.Components
 * @subpackage  com_xtsobipro
 * @since       1.0
 */
class Extlyframework
{

}
