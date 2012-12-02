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

?>

<div class="extly">

	<p style="margin: 0 auto 10px; text-align:center;">
      <a onclick="window.open('https://github.com/anibalsanchez/Backbone-for-Joomla/archive/master.zip');" class="btn btn-success btn-large" href="http://sites.fastspring.com/prieco/instant/support-our-work">Download & Donate</a>
    </p>
    <p class="text-info" style="margin: 0 auto 10px; text-align:center;">
    	<i class="icon-info-sign"></i> Support our work for <span class="label label-info">Backbone-for-Joomla</span>.
    </p>

	<section id="todoapp">
		<header id="header">
			<h1>todos</h1>
			<input id="new-todo" placeholder="What needs to be done?" autofocus>
		</header>
		<section id="main">
			<input id="toggle-all" type="checkbox"> <label for="toggle-all">Mark all as complete</label>
			<ul id="todo-list"></ul>
		</section>
		<footer id="footer"></footer>
	</section>
	<footer id="info">
		<p>Double-click to edit a todo</p>
		<p>
			Written by <a href="http://addyosmani.github.com/todomvc/">Addy Osmani</a>
		</p>
		<p>
			Part of <a href="http://todomvc.com">TodoMVC</a>
		</p>
	</footer>

</div>
