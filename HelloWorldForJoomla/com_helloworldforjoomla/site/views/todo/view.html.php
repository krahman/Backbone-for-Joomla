<?php

/**
 * @package     Extly.Components
 * @subpackage  com_helloworldforjoomla - General package manager for Backbone Extensions
 *
 * @author      Prieco S.A. <support@extly.com>
 * @copyright   Copyright (C) 2007 - 2012 Prieco, S.A. All rights reserved.
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @link        http://www.extly.com http://support.extly.com
 */
// No direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

/**
 * HelloWorldForJoomlaViewINfo.
 *
 * @package     Extly.Components
 * @subpackage  com_helloworldforjoomla
 * @since       1.0
 */
class HelloWorldForJoomlaViewTodo extends JView
{

	/**
	 * Method to display a view.
	 *
	 * @return	JController		This object to support chaining.
	 *
	 * @since	1.5
	 */
	public function display()
	{
		Extly::loadMeta();
		Extly::loadStyle();
		Extly::initApp('components/com_helloworldforjoomla/views/todo/js/main');

		parent::display();
	}
}
