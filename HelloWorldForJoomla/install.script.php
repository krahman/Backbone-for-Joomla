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

if (!class_exists('PlgSystemInstallerInstallerScript'))
{

	/**
	 * PlgSystemInstallerInstallerScript.
	 *
	 * @package     Extly.Components
	 * @subpackage  com_helloworldforjoomla
	 * @since       1.0
	 */
	class PlgSystemInstallerInstallerScript
	{
		protected $packages = array();

		protected $sourcedir;

		protected $installerdir;

		protected $manifest;

		/**
		 * @param $parent
		 */
		protected function setup($parent)
		{
			$this->sourcedir    = $parent->getParent()->getPath('source');
			$this->manifest     = $parent->getParent()->getManifest();
			$this->installerdir = $this->sourcedir . '/' . 'installer';
		}

		/**
		 * @param $parent
		 *
		 * @return bool
		 */
		public function install($parent)
		{

			$this->cleanBogusError();

			jimport('joomla.filesystem.file');
			jimport('joomla.filesystem.folder');

			$retval            = true;
			$buffer            = '';
			$install_html_file = dirname(__FILE__) . '/install.html';
			$install_css_file  = dirname(__FILE__) . '/install.css';
			$tmp_path          = JPATH_ROOT . '/tmp';

			// Drop out Style
			if (file_exists($install_css_file))
			{
				$buffer .= JFile::read($install_html_file);
			}

			if (JFolder::exists($tmp_path))
			{
				// Copy install.css to tmp dir for inclusion
				JFile::copy($install_css_file, $tmp_path . '/install.css');
			}

			// Opening HTML
			ob_start();
			?>
<div id="rokinstall-logo">
	<ul id="rokinstall-status">
		<?php
		$buffer .= ob_get_clean();

		$run_installer = true;
		if (is_file(dirname(__FILE__) . '/requirements.php'))
		{
			// Check to see if requierments are met
			if (($loaderrors = file_exists(dirname(__FILE__) . '/requirements.php')) !== true)
			{

				$package['name'] = '';
				$msg             = "Requirements check failed.<br />" . implode('<br />', $loaderrors);
				$buffer .= $this->printerror($package, $msg);
				$run_installer = false;
			}
			require_once dirname(__FILE__) . '/requirements.php';
		}

		// Cycle through cogs and install each

		if ($run_installer)
		{
			if (count($this->manifest->cogs->children()))
			{
				if (!class_exists('RokInstaller'))
				{
					require_once $this->installerdir . '/' . 'RokInstaller.php';
				}

				foreach ($this->manifest->cogs->children() as $cog)
				{
					$folder = $this->sourcedir . '/' . trim($cog);

					jimport('joomla.installer.helper');
					if (is_dir($folder))
					{
						// If its actually a directory then fill it up
						$package                = Array();
						$package['dir']         = $folder;
						$package['type']        = JInstallerHelper::detectType($folder);
						$package['installer']   = new RokInstaller;
						$package['name']        = (string) $cog->name;
						$package['state']       = 'Success';
						$package['description'] = (string) $cog->description;
						$package['msg']         = '';
						$package['type']        = ucfirst((string) $cog['type']);

						$package['installer']->setCogInfo($cog);

						// Add installer to static for possible rollback
						$this->packages[] = $package;
						if (!@$package['installer']->install($package['dir']))
						{
							while ($error = JError::getError(true))
							{
								$package['msg'] .= $error;
							}
							$buffer .= $this->printerror($package, $package['msg']);
							break;
						}
						if ($package['installer']->getInstallType() == 'install')
						{
							$buffer .= $this->printInstall($package);
						}
						else
						{
							$buffer .= $this->printUpdate($package);
						}
					}
					else
					{
						$package                = Array();
						$package['dir']         = $folder;
						$package['name']        = (string) $cog->name;
						$package['state']       = 'Failed';
						$package['description'] = (string) $cog->description;
						$package['msg']         = '';
						$package['type']        = ucfirst((string) $cog['type']);
						$buffer .= $this->printerror($package, JText::_('JLIB_INSTALLER_ABORT_NOINSTALLPATH'));

						break;
					}
				}
			}
			else
			{
				$parent->getParent()->abort(JText::sprintf('JLIB_INSTALLER_ABORT_PACK_INSTALL_NO_FILES', JText::_('JLIB_INSTALLER_' . strtoupper($this->route))));
			}
		}

		// Closing HTML
		ob_start();
		?>
	</ul>
</div>
<?php
$buffer .= ob_get_clean();

// Return stuff
echo $buffer;
return $retval;
		}

		/**
		 * @param $parent
		 */
		public function uninstall($parent)
		{

		}

		/**
		 * @param $parent
		 *
		 * @return bool
		 */
		public function update($parent)
		{
			return $this->install($parent);
		}

		/**
		 * @param $type
		 * @param $parent
		 */
		public function preflight($type, $parent)
		{
			$this->setup($parent);

			// Load Event Handler
			$event_handler_file = $this->installerdir . '/RokInstallerEvents.php';

			require_once $event_handler_file;
			$dispatcher = JDispatcher::getInstance();
			new RokInstallerEvents($dispatcher);
		}

		/**
		 * @param $type
		 * @param $parent
		 */
		public function postflight($type, $parent)
		{
			$conf = JFactory::getConfig();
			$conf->set('debug', false);
			$parent->getParent()->abort();
		}

		/**
		 * @param null $msg
		 * @param null $type
		 */
		public function abort($msg = null, $type = null)
		{
			if ($msg)
			{
				JError::raiseWarning(100, $msg);
			}
			foreach ($this->packages as $package)
			{
				$package['installer']->abort(null, $type);
			}
		}

		/**
		 * @param $package
		 * @param $msg
		 *
		 * @return string
		 */
		public function printerror($package, $msg)
		{
			ob_start();
			?>
<li class="rokinstall-failure">
<span class="rokinstall-row">
<span class="rokinstall-icon">
<span>
</span>
</span>
<?php echo $package['name'];?> installation failed
</span>
<span class="rokinstall-errormsg">
<?php echo $msg; ?>
</span>
</li>
<?php
$out = ob_get_clean();
return $out;
		}

		/**
		 * @param $package
		 *
		 * @return string
		 */
		public function printInstall($package)
		{
			ob_start();
			?>
<li class="rokinstall-success">
<span class="rokinstall-row">
<span class="rokinstall-icon">
<span></span>
</span>
<?php echo $package['name'];?> installation was successful
</span>
</li>
<?php
$out = ob_get_clean();
return $out;
		}

		/**
		 * @param $package
		 *
		 * @return string
		 */
		public function printUpdate($package)
		{
			ob_start();
			?>
<li class="rokinstall-update">
<span class="rokinstall-row">
<span class="rokinstall-icon">
<span></span>
</span>
<?php echo $package['name'];?> update was successful
</span>
</li>
<?php
$out = ob_get_clean();
return $out;
		}

		/**
		 *
		 */
		protected function cleanBogusError()
		{
			$errors = array();
			while (($error = JError::getError(true)) !== false)
			{
				if (!($error->get('code') == 1 && $error->get('level') == 2 && $error->get('message') == JText::_('JLIB_INSTALLER_ERROR_NOTFINDXMLSETUPFILE')))
				{
					$errors[] = $error;
				}
			}
			foreach ($errors as $error)
			{
				JError::addToStack($error);
			}

			$app               = JFactory::getApplication();
			$enqueued_messages = $app->get('_messageQueue');
			$other_messages    = array();
			if (!empty($enqueued_messages) && is_array($enqueued_messages))
			{
				foreach ($enqueued_messages as $enqueued_message)
				{
					if (!($enqueued_message['message'] == JText::_('JLIB_INSTALLER_ERROR_NOTFINDXMLSETUPFILE') && $enqueued_message['type']) == 'error')
					{
						$other_messages[] = $enqueued_message;
					}
				}
			}
			$app->set('_messageQueue', $other_messages);
		}
	}
}
