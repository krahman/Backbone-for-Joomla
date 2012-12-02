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
defined('EXTLY_VERSION') or die('Restricted access');

/**
 * This is the base class for the Extly framework.
 *
 * @package     Extly.Components
 * @subpackage  com_xtsobipro
 * @since       1.0
 */
class Extly
{

	CONST APP_INITIALIZATION = '</script><script data-main="{DATA_MAIN}" src="{JURI_ROOT}libraries/extly/js/require/require.js"></script>

		<script>
		// Require.js allows us to configure shortcut alias
		require.config({
			// The shim config allows us to configure dependencies for
			// scripts that do not call define() to register a module
			shim: {
				\'underscore\': {
				exports: \'_\'
				},
				\'backbone\': {
				deps: [
				\'underscore\',
				\'jquery\'
						],
						exports: \'Backbone\'
				}
			},
			paths: {
				jquery: \'{JURI_ROOT}libraries/extly/js/jquery/jquery.min\',
				underscore: \'{JURI_ROOT}libraries/extly/js/lodash.min\',
				backbone: \'{JURI_ROOT}libraries/extly/js/backbone/backbone\',
				text: \'{JURI_ROOT}libraries/extly/js/require/text\',
				common: \'{JURI_ROOT}libraries/extly/js/common\',
				localstorage: \'{JURI_ROOT}libraries/extly/js/backbone/localstorage\',
			}
		});';

	/**
	 * loadMeta.
	 *
	 * @return	void.
	 *
	 * @since	1.0
	 */
	public static function loadMeta()
	{
		$document = JFactory::getDocument();
		$document->setMetaData('X-UA-Compatible', 'IE=edge,chrome=1');
	}

	/**
	 * loadStyle.
	 *
	 * @return	void.
	 *
	 * @since	1.0
	 */
	public static function loadStyle()
	{
		$document = JFactory::getDocument();
		$url = JURI::root();
		$document->addStyleSheet($url . 'libraries/extly/css/extly-bootstrap.css');
		$document->addStyleSheet($url . 'libraries/extly/css/extly-font-awesome.min.css');
		$document->addStyleSheet($url . 'libraries/extly/css/extly-base.css');
	}

	/**
	 * initApp.
	 *
	 * @param   string  $dataMain  Param
	 *
	 * @return	void.
	 *
	 * @since	1.0
	 */
	public static function initApp($dataMain)
	{
		$document = JFactory::getDocument();
		$jsapp_init = str_replace('{JURI_ROOT}', JURI::root(), self::APP_INITIALIZATION);
		$jsapp_init = str_replace('{DATA_MAIN}', $dataMain, $jsapp_init);

		$document->addScriptDeclaration($jsapp_init);
	}

}
