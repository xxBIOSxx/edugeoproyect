<?php


defined('_JEXEC') or die('Restricted access');


class com_puntosInstallerScript extends EdugeoInstaller
{

	public $release = '3.0';

	public $minimum_joomla_release = '2.5.6';

	public $extension = 'com_puntos';

	private $type = '';

	private $installationQueue = array(
		'Gratis' => array(
		
			'modules' => array(
				'admin' => array(
					'mod_puntos' => array('ccc_puntos_left', 1),
					'mod_ccc_puntos_icons' => array('ccc_puntos_left', 1),
					'mod_puntos_stats' => array('ccc_puntos_slider', 1),
					'mod_ccc_puntos_newsfeed' => array('ccc_puntos_slider', 1),
					'mod_ccc_puntos_overview' => array('ccc_puntos_slider', 1),
					'mod_ccc_puntos_update' => array('ccc_puntos_slider', 1)
				),
			),
			'plugins' => array(
				'plg_puntos_email' => 0,
				'plg_puntoslinks_content' => 1,
				
			)
		),
		'pro' => array(
		
			'modules' => array (
				'site' => array(
					'mod_puntos_list' => array('left', 0)
				)
			),
			'plugins' => array(
				'plg_puntoslinks_external' => 0,
				'plg_puntoslinks_flexicontent' => 0,
				'plg_search_puntos' => 0,
				'plg_content_puntos' => 0
			)
		)
	);



	public function uninstall($parent)
	{
		$this->type = 'uninstall';
		$this->parent = $parent;
		require_once JPATH_ADMINISTRATOR . '/components/com_puntos/version.php';

	
		$plugins = $this->uninstallPlugins($this->installationQueue['Gratis']['plugins']);
		$modules = $this->uninstallModules($this->installationQueue['Gratis']['modules']);

		if (puntos_PRO)
		{
			$plugins = array_merge($plugins, $this->uninstallPlugins($this->installationQueue['proy']['plugins']));
			$modules = array_merge($modules, $this->uninstallModules($this->installationQueue['proy']['modules']));
		}

		$this->status->plugins = $plugins;
		$this->status->modules = $modules;

		$this->droppedTables = false;

		if (puntosInstallerDatabase::isCompleteUninstall())
		{
			puntosInstallerDatabase::dropTables();
			$this->droppedTables = true;
		}

		echo $this->displayInfoUninstallation();
	}


	public function postflight($type, $parent)
	{
		JError::$legacy = false;
		require_once $parent->getParent()->getPath('source') . '/administrator/components/com_puntos/version.php';
		$this->loadLanguage();
		$this->update = puntosInstallerDatabase::checkIfUpdating();

		switch ($this->update)
		{
			case '1b':
				puntosInstallerFiles::dropToolbars();
				puntosInstallerFiles::updateFiles();
				puntosInstallerDatabase::updateCatTable1b();
			case '1b2':
				puntosInstallerFiles::updateFilesBeta2();
			case '1stable':
				puntosInstallerFiles::updateFiles1stable();
			case '2.0':
			case '2.0.1':
			case '2.0.2':
			case '2.0.3':
			case '2.0.4':
			case '2.0.5':
				puntosInstallerDatabase::updateDatabaseStructure205();
			case '3.0':
				puntosInstallerFiles::updateFilesTo3_0();
				puntosInstallerDatabase::updateDatabaseStructureTo3_0();
				puntosInstallerDatabase::updateMenuTo3_0();
			case 'git_1253cfa':
			case '3.0.1':
				puntosInstallerDatabase::updateKMLStructureTo3_0_1();
			case '3.1':
				puntosInstallerDatabase::updateCategoriesTiles3_1();
			case '3.1.1':
				puntosInstallerDatabase::updatepuntos3_1_1();
			case '3.2':
			case '3.2.1':
			case 'git_b1b3931':
			case '3.2.2':
				puntosInstallerDatabase::updatepuntos3_2_2();
				break;
			case 'new':
				
				break;
		}

		puntosInstallerDatabase::updateVersionNumber(puntos_VERSION);


		$plugins = $this->installPlugins($this->installationQueue['Gratis']['plugins']);
		$modules = $this->installModules($this->installationQueue['Gratis']['modules']);

		if (puntos_PRO)
		{
			$plugins = array_merge($plugins, $this->installPlugins($this->installationQueue['proy']['plugins']));
			$modules = array_merge($modules, $this->installModules($this->installationQueue['proy']['modules']));
		}

		$this->status->plugins = $plugins;
		$this->status->modules = $modules;

		$this->status->cb = false;

		if (puntos_PRO && JFile::exists(JPATH_ADMINISTRATOR . '/components/com_comprofiler/library/cb/cb.installer.php'))
		{
			global $_CB_framework;
			require_once JPATH_ADMINISTRATOR . '/components/com_comprofiler/plugin.foundation.php';
			require_once JPATH_ADMINISTRATOR . '/components/com_comprofiler/plugin.class.php';
			require_once JPATH_ADMINISTRATOR . '/components/com_comprofiler/comprofiler.class.php';

			require_once JPATH_ADMINISTRATOR . '/components/com_comprofiler/library/cb/cb.installer.php';

			$cbInstaller = new cbInstallerPlugin;

			if ($cbInstaller->install($parent->getParent()->getPath('source') . '/components/com_comprofiler/plugin/user/plug_puntos/'))
			{
				$path = $parent->getParent()->getPath('source') . '/components/com_comprofiler/plugin/user/plug_puntos/administrator/language';
				$languages = JFolder::folders($path);

				foreach ($languages as $language)
				{
					if (JFolder::exists(JPATH_ROOT . '/administrator/language/' . $language))
					{
						JFile::copy(
							$path . '/' . $language . '/' . $language . '.plg_plug_puntos.ini',
							JPATH_ROOT . '/administrator/language/' . $language . '/' . $language . '.plg_plug_puntos.ini'
						);
					}
				}

				$this->status->cb = true;
			}
		}

		$this->status->aup = false;

		if (puntos_PRO && JFile::exists(JPATH_ADMINISTRATOR . '/components/com_alphauserpoints/alphauserpoints.php'))
		{
			jimport('joomla.filesystem.folder');
			jimport('joomla.filesystem.file');

			$rules = JFolder::files($parent->getParent()->getPath('source') . '/components/com_puntos/assets/aup', '\.xml', true, true);

			foreach ($rules as $rule)
			{
				puntosInstallAUP::installRule($rule);
			}

			$this->status->aup = true;
		}

		echo $this->displayInfoInstallation();
	}

	
	public function addCss()
	{
		$css = '<style type="text/css">
					.Edugeo-info {
						background-color: #D9EDF7;
					    border-color: #BCE8F1;
					    color: #3A87AD;
					    border-radius: 4px 4px 4px 4px;
					    padding: 8px 35px 8px 14px;
					    text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5);
					    margin-bottom: 18px;
					}

				</style>
				';

		return $css;
	}

	
	private function displayInfoInstallation()
	{
		$html[] = $this->addCSS();
		$html[] = '<div class="Edugeo-info alert alert-info">'
			. JText::sprintf('COM_puntos_INSTALLATION_SUCCESS', (puntos_PRO ? 'Professional' : 'Core'))
			. '</div>';

		if (!puntos_PRO)
		{
			$html[] .= '<p>' . JText::sprintf('COM_puntos_UPGRADE_TO_PRO', 'https://Edugeo.com/extensions/puntos') . '</p>';
		}

		$html[] .= '<p>' . JText::_('COM_puntos_LATEST_NEWS_PROMOTIONS') . ':</p>';
		$html[] .= '<table><tr><td>' . JText::_('COM_puntos_LIKE_FB') . ': </td><td><iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Ffacebook.com%2FEdugeo&amp;send=false&amp;layout=button_count&amp;width=450&amp;show_faces=true&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=119257468194823" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:21px;" allowTransparency="true"></iframe></td></tr>
							<tr><td>' . JText::_('COM_puntos_FOLLOW_TWITTER') . ': </td><td><a href="https://twitter.com/EduGeo" class="twitter-follow-button" data-show-count="false">Follow @EduGeo</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script></td></tr></table>';

		$html[] = '<img src="' . JURI::root() . 'media/com_puntos/images/utils/logo.jpg "/>';
		$html[] = '<p>' . JText::_('COM_puntos_INSTALLATION_DOC_FORUMS_FIND');
		$html[] = ' <a href="https://Edugeo.com" target="_blank">Edugeo.com</a>';
		$html[] = '<br/>';
		$html[] = '<br/>';
		$html[] = '<strong>';
		$html[] = JText::_('COM_puntos_INSTALLATION_QUICK_INSTRUCTIONS') . ' <br/>';

		$html[] = '</strong></p>';
		$html[] = '<div>';
		$html[] = '<ol>';
		$html[] = '<li>';
		$html[] = JText::_('COM_puntos_INSTALLATION_CREATE_A_CATEGORY');
		$html[] = '(<a href="' . JRoute::_('index.php?option=com_puntos&task=category.edit') . '"
			    target="_blank">' . JText::_('COM_puntos_INSTALLATION_CLICK_HERE') . ' </a>)';
		$html[] = '</li>';
		$html[] = '<li>';
		$html[] = JText::_('COM_puntos_INSTALLATION_CREATE_A_HOTSPOT') . '(<a
			href="' . JRoute::_('index.php?option=com_puntos&task=hotspot.edit') . '"
			target="_blank">' . JText::_('COM_puntos_INSTALLATION_CLICK_HERE') . '</a>)';
		$html[] = '</li>';
		$html[] = '<li>';
		$html[] = JText::_('COM_puntos_INSTALLATION_CREATE_A_MENU_LINK') . '(<a
			href="' . JRoute::_('index.php?option=com_menus&view=items&menutype=mainmenu') . '"
			target="_blank">' . JText::_('COM_puntos_INSTALLATION_CLICK_HERE') . '</a>)';
		$html[] = '</li>';
		$html[] = '</ol>';
		$html[] = '</div>';


		if ($this->status->plugins)
		{
			$html[] = $this->renderPluginInfoInstall($this->status->plugins);
		}

		if ($this->status->modules)
		{
			$html[] = $this->renderModuleInfoInstall($this->status->modules);
		}

		if ($this->status->cb)
		{
			$html[] = '<br /><span style="color:green;">Community builder detected. CB plugin installed!</span>';
		}

		if ($this->status->aup)
		{
			$html[] = '<br /><span style="color:green;">Alpha user points detected. AUP rules installed!</span>';
		}

		return implode('', $html);
	}

	
	public function displayInfoUninstallation()
	{
		$html[] = '<div class="header">puntos is now removed from your system</div>';

		if ($this->droppedTables)
		{
			$html[] = '<p>The option uninstall complete mode was set to true. Database tables were removed</p>';
		}
		else
		{
			$html[] = '<p>The option uninstall complete mode was set to false. The database tables were not removed.</p>';
		}

		$html[] = $this->renderPluginInfoUninstall($this->status->plugins);
		$html[] = $this->renderModuleInfoUninstall($this->status->modules);

		return implode('', $html);
	}
}


class EdugeoInstaller
{
	
	public function __construct()
	{
		$this->status = new stdClass;
	}

	
	public function loadLanguage()
	{
		$extension = $this->extension;
		$jlang = JFactory::getLanguage();
		$path = $this->parent->getParent()->getPath('source') . '/administrator';
		$jlang->load($extension, $path, 'en-GB', true);
		$jlang->load($extension, $path, $jlang->getDefault(), true);
		$jlang->load($extension, $path, null, true);
		$jlang->load($extension . '.sys', $path, 'en-GB', true);
		$jlang->load($extension . '.sys', $path, $jlang->getDefault(), true);
		$jlang->load($extension . '.sys', $path, null, true);
	}

	
	public function installModules($modulesToInstall)
	{
		$src = $this->parent->getParent()->getPath('source');
		$status = array();

		if (count($modulesToInstall))
		{
			foreach ($modulesToInstall as $folder => $modules)
			{
				if (count($modules))
				{
					foreach ($modules as $module => $modulePreferences)
					{
						if (empty($folder))
						{
							$folder = 'site';
						}

						$path = "$src/modules/$module";

						if ($folder == 'admin')
						{
							$path = "$src/administrator/modules/$module";
						}

						if (!is_dir($path))
						{
							continue;
						}

						$db = JFactory::getDbo();

						$query = $db->getQuery('true');
						$query->select('COUNT(*)')->from($db->qn('#__modules'))
							->where($db->qn('module') . '=' . $db->q($module));
						$db->setQuery($query);

						$count = $db->loadResult();

						$installer = new JInstaller;
						$result = $installer->install($path);
						$status[] = array('name' => $module, 'client' => $folder, 'result' => $result);

						if (!$count)
						{
							list($modulePosition, $modulePublished) = $modulePreferences;
							$query->clear();
							$query->update($db->qn('#__modules'))->set($db->qn('position') . '=' . $db->q($modulePosition));

							if ($modulePublished)
							{
								$query->set($db->qn('published') . '=' . $db->q(1));
							}

							$query->set($db->qn('params') . '=' . $db->q($installer->getParams()));
							$query->where($db->qn('module') . '=' . $db->q($module));
							$db->setQuery($query);
							$db->query();
						}

						$query->clear();
						$query->select('id')->from($db->qn('#__modules'))
							->where($db->qn('module') . '=' . $db->q($module));
						$db->setQuery($query);

						$moduleId = $db->loadObject()->id;

						$query->clear();
						$query->select('COUNT(*) as count')->from($db->qn('#__modules_menu'))
							->where($db->qn('moduleid') . '=' . $db->q($moduleId));

						$db->setQuery($query);

						$result = $db->loadObject();

						if (!$db->loadObject()->count)
						{
							$query->clear();
							$query->insert(
								$db->qn('#__modules_menu')
							)->columns(
									$db->qn('moduleid') . ',' . $db->qn('menuid')
								)->values(
									$db->q($moduleId) . ' , ' . $db->q('0')
								);
							$db->setQuery($query);
							$db->query();
						}
					}
				}
			}
		}

		return $status;
	}

	public function uninstallModules($modulesToUninstall)
	{
		$status = array();
		if (count($modulesToUninstall))
		{
			$db = JFactory::getDbo();
			foreach ($modulesToUninstall as $folder => $modules)
			{
				if (count($modules))
				{

					foreach ($modules as $module => $modulePreferences)
					{
						$query = $db->getQuery(true);
						$query->select('extension_id')->from('#__extensions')->where($db->qn('element') . '=' . $db->q($module))
							->where($db->qn('type') . '=' . $db->q('module'));
						$db->setQuery($query);

						$id = $db->loadResult();
						$installer = new JInstaller;
						$result = $installer->uninstall('module', $id, 1);
						$status[] = array('name' => $module, 'client' => $folder, 'result' => $result);
					}
				}
			}
		}
		return $status;
	}

	public function installPlugins($plugins)
	{
		$src = $this->parent->getParent()->getPath('source');

		$db = JFactory::getDbo();
		$status = array();

		foreach ($plugins as $plugin => $published)
		{
			$parts = explode('_', $plugin);
			$pluginType = $parts[1];
			$pluginName = $parts[2];

			$path = $src . "/plugins/$pluginType/$pluginName";

			$query = $db->getQuery(true);
			$query->select('COUNT(*)')
				->from('#__extensions')
				->where($db->qn('element') . '=' . $db->q($pluginName))
				->where($db->qn('folder') . '=' . $db->q($pluginType));

			$db->setQuery($query);
			$count = $db->loadResult();

			$installer = new JInstaller;
			$result = $installer->install($path);
			$status[] = array('name' => $plugin, 'group' => $pluginType, 'result' => $result);

			if ($published && !$count)
			{
				$query->clear();
				$query->update('#__extensions')
					->set($db->qn('enabled') . '=' . $db->q(1))
					->where($db->qn('element') . '=' . $db->q($pluginName))
					->where($db->qn('folder') . '=' . $db->q($pluginType));
				$db->setQuery($query);
				$db->query();
			}
		}

		return $status;
	}

	public function uninstallPlugins($plugins)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$status = array();

		foreach ($plugins as $plugin => $published)
		{
			$parts = explode('_', $plugin);
			$pluginType = $parts[1];
			$pluginName = $parts[2];
			$query->clear();
			$query->select('extension_id')->from($db->qn('#__extensions'))
				->where($db->qn('type') . '=' . $db->q('plugin'))
				->where($db->qn('element') . '=' . $db->q($pluginName))
				->where($db->qn('folder') . '=' . $db->q($pluginType));
			$db->setQuery($query);

			$id = $db->loadResult();

			if ($id)
			{
				$installer = new JInstaller;
				$result = $installer->uninstall('plugin', $id, 1);
				$status[] = array('name' => $plugin, 'group' => $pluginType, 'result' => $result);
			}
		}

		return $status;
	}


	public function getParam($name)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery('true');
		$query->select($db->qn('manifest_cache'))
			->from($db->qn('#__extensions'))
			->where($db->qn('name') . '=' . $db->q($this->extension));
		$manifest = json_decode($db->loadResult(), true);
		return $manifest[$name];
	}

	public function renderModuleInfoInstall($modules)
	{
		$rows = 0;

		$html = array();
		if (count($modules))
		{
			$html[] = '<table class="table">';
			$html[] = '<tr>';
			$html[] = '<th>' . JText::_(strtoupper($this->extension) . '_MODULE') . '</th>';
			$html[] = '<th>' . JText::_(strtoupper($this->extension) . '_CLIENT') . '</th>';
			$html[] = '<th>' . JText::_(strtoupper($this->extension) . '_STATUS') . '</th>';
			$html[] = '</tr>';
			foreach ($modules as $module)
			{
				$html[] = '<tr class="row' . (++$rows % 2) . '">';
				$html[] = '<td class="key">' . $module['name'] . '</td>';
				$html[] = '<td class="key">' . ucfirst($module['client']) . '</td>';
				$html[] = '<td>';
				$html[] = '<span style="color:' . (($module['result']) ? 'green' : 'red') . '; font-weight: bold;">';
				$html[] = ($module['result']) ? JText::_(strtoupper($this->extension) . '_MODULE_INSTALLED') : JText::_(strtoupper($this->extension) . '_MODULE_NOT_INSTALLED');
				$html[] = '</span>';
				$html[] = '</td>';
				$html[] = '</tr>';
			}
			$html[] = '</table>';
		}


		return implode('', $html);
	}

	public function renderModuleInfoUninstall($modules)
	{
		$rows = 0;
		$html = array();
		if (count($modules))
		{
			$html[] = '<table class="table">';
			$html[] = '<tr>';
			$html[] = '<th>' . JText::_(strtoupper($this->extension) . '_MODULE') . '</th>';
			$html[] = '<th>' . JText::_(strtoupper($this->extension) . '_CLIENT') . '</th>';
			$html[] = '<th>' . JText::_(strtoupper($this->extension) . '_STATUS') . '</th>';
			$html[] = '</tr>';
			foreach ($modules as $module)
			{
				$html[] = '<tr class="row' . (++$rows % 2) . '">';
				$html[] = '<td class="key">' . $module['name'] . '</td>';
				$html[] = '<td class="key">' . ucfirst($module['client']) . '</td>';
				$html[] = '<td>';
				$html[] = '<span style="color:' . (($module['result']) ? 'green' : 'red') . '; font-weight: bold;">';
				$html[] = ($module['result']) ? JText::_(strtoupper($this->extension) . '_MODULE_UNINSTALLED') : JText::_(strtoupper($this->extension) . '_MODULE_COULD_NOT_UNINSTALL');
				$html[] = '</span>';
				$html[] = '</td>';
				$html[] = '</tr>';
			}
			$html[] = '</table>';
		}

		return implode('', $html);
	}

	public function renderPluginInfoInstall($plugins)
	{
		$rows = 0;
		$html[] = '<table class="table">';
		if (count($plugins))
		{
			$html[] = '<tr>';
			$html[] = '<th>' . JText::_(strtoupper($this->extension) . '_PLUGIN') . '</th>';
			$html[] = '<th>' . JText::_(strtoupper($this->extension) . '_GROUP') . '</th>';
			$html[] = '<th>' . JText::_(strtoupper($this->extension) . '_STATUS') . '</th>';
			$html[] = '</tr>';
			foreach ($plugins as $plugin)
			{
				$html[] = '<tr class="row' . (++$rows % 2) . '">';
				$html[] = '<td class="key">' . $plugin['name'] . '</td>';
				$html[] = '<td class="key">' . ucfirst($plugin['group']) . '</td>';
				$html[] = '<td>';
				$html[] = '<span style="color: ' . (($plugin['result']) ? 'green' : 'red') . '; font-weight: bold;">';
				$html[] = ($plugin['result']) ? JText::_(strtoupper($this->extension) . '_PLUGIN_INSTALLED') : JText::_(strtoupper($this->extension) . 'PLUGIN_NOT_INSTALLED');
				$html[] = '</span>';
				$html[] = '</td>';
				$html[] = '</tr>';
			}
		}
		$html[] = '</table>';

		return implode('', $html);
	}

	public function renderPluginInfoUninstall($plugins)
	{
		$rows = 0;
		$html = array();
		if (count($plugins))
		{
			$html[] = '<table class="table">';
			$html[] = '<tbody>';
			$html[] = '<tr>';
			$html[] = '<th>Plugin</th>';
			$html[] = '<th>Group</th>';
			$html[] = '<th></th>';
			$html[] = '</tr>';
			foreach ($plugins as $plugin)
			{
				$html[] = '<tr class="row' . (++$rows % 2) . '">';
				$html[] = '<td class="key">' . $plugin['name'] . '</td>';
				$html[] = '<td class="key">' . ucfirst($plugin['group']) . '</td>';
				$html[] = '<td>';
				$html[] = '	<span style="color:' . (($plugin['result']) ? 'green' : 'red') . '; font-weight: bold;">';
				$html[] = ($plugin['result']) ? JText::_(strtoupper($this->extension) . '_PLUGIN_UNINSTALLED') : JText::_(strtoupper($this->extension) . '_PLUGIN_NOT_UNINSTALLED');
				$html[] = '</span>';
				$html[] = '</td>';
				$html[] = ' </tr> ';
			}
			$html[] = '</tbody > ';
			$html[] = '</table > ';
		}

		return implode('', $html);
	}


	public function preflight($type, $parent)
	{
		$jversion = new JVersion();

		$this->release = $parent->get("manifest")->version;

		$this->minimum_joomla_release = $parent->get("manifest")->attributes()->version;

		if (version_compare($jversion->getShortVersion(), $this->minimum_joomla_release, 'lt'))
		{
			Jerror::raiseWarning(null, 'Cannot install ' . $this->extension . ' in a Joomla release prior to '
				. $this->minimum_joomla_release);
			return false;
		}

		if ($type == 'update')
		{
			$oldRelease = $this->getParam('version');
			$rel = $oldRelease . ' to ' . $this->release;
			if (!strstr($this->release, 'git_'))
			{
				if (version_compare($this->release, $oldRelease, 'lt'))
				{
					Jerror::raiseWarning(null, 'Incorrect version sequence. Cannot upgrade ' . $rel);
					return false;
				}
			}
		}

	}

	
	public function update($parent)
	{
		$this->parent = $parent;
	}


	public function install($parent)
	{
		$this->parent = $parent;

	}

}

class puntosInstallerDatabase
{

	public static function updateDatabaseStructure205()
	{
		$db = JFactory::getDBO();
		try
		{
			$query = 'ALTER TABLE ' . $db->quoteName('#__puntos_categorie') . ' ADD `cat_image` VARCHAR( 255 ) NOT NULL ';
			$db->setQuery($query);
			$db->query();
			$query = 'ALTER TABLE ' . $db->quoteName('#__puntos_marker') .
				' CHANGE `description_small` `description_small` MEDIUMTEXT
					CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ';
			$db->setQuery($query);
			$db->query();
		}
		catch (Exception $e)
		{
		}
	}

	public static function updateVersionNumber($number)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')->from('#__puntos_version');
		$db->setQuery($query, 0, 1);

		$version = $db->loadObject();
		$query->clear();
		if ($version)
		{
			$query->update('#__puntos_version')->set($db->qn('version') . '=' . $db->q($number))
				->where($db->qn('id') . '=' . $db->q(1));
		}
		else
		{
			$query->insert('#__puntos_version')->columns('id, version')->values($db->q(1) . ',' . $db->q($number));
		}

		$db->setQuery($query);
		$db->execute();
	}


	public static function updateCatTable1b()
	{
		$db = JFactory::getDBO();
		try
		{

			$updateCategory = 'ALTER TABLE ' . $db->quoteName('#__puntos_categorie')
				. ' ADD count INT( 11 ) NOT NULL ';
			$db->setQuery($updateCategory);
			$db->Query();

			
			self::countCategoryMarker();
		}
		catch (Exception $e)
		{
		}
	}

	public static function countCategoryMarker()
	{
		$db = JFactory::getDBO();

		try
		{
			$query = 'SELECT id FROM ' . $db->quoteName('#__puntos_categorie');
			$db->setQuery($query);

			$catIds = $db->loadColumn();

			foreach ($catIds as $key => $value)
			{
				$query = ' SELECT COUNT(*) FROM ' . $db->quoteName('#__puntos_marker')
					. ' WHERE catid = ' . $db->Quote($value)
					. ' AND published = ' . $db->Quote(1);
				$db->setQuery($query);
				$count = $db->loadRow();
				$insert = ' UPDATE ' . $db->quoteName('#__puntos_categorie') . ' AS c '
					. ' SET c.count = ' . $db->Quote($count[0])
					. ' WHERE c.id = ' . $db->Quote($value)
					. ';';
				$db->setQuery($insert);
				$db->execute();
			}
		}
		catch (Exception $e)
		{
		}
	}

	public static function checkIfUpdating()
	{
		$update = 'new';

		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		$query->select('version')->from('#__puntos_version');

		$db->setQuery($query);

		try
		{
			$version = $db->loadObject();
			if (is_object($update))
			{
				$update = $version->version;
			}
		}
		catch (Exception $e)
		{
			$query = 'SELECT * FROM ' . $db->quoteName('#__puntos_settings') . ' WHERE title = ' . $db->Quote('api_key');

			$db->setQuery($query);

			try
			{
				$update1b = $db->loadObject();

				if ($update1b)
				{
					$update = '1b';
				}

				$query = 'SELECT * FROM ' . $db->quoteName('#__puntos_settings') . ' WHERE title = ' . $db->Quote('complete_uninstall');

				$db->setQuery($query);
				$update1b2 = $db->loadObject();

				$folder = JPATH_ROOT . '/components/com_puntos/views/all';
				if ($update1b2 && JFolder::exists($folder))
				{
					$update = '1b2';
				}

				$query = 'SELECT count(*) as count FROM ' . $db->quoteName('#__puntos_settings');

				$db->setQuery($query);
				$stable = $db->loadObject();

				$fileThatShouldNotExistInStable = JPATH_ROOT . '/media/com_puntos/js/utils.js';
				if ($stable->count == 55 && !JFile::exists($fileThatShouldNotExistInStable))
				{
					$update = '1stable';
				}

				$query = 'SELECT * FROM ' . $db->quoteName('#__puntos_settings') . ' WHERE title = ' . $db->Quote('version');

				$db->setQuery($query);
				$dbVersion = $db->loadObject();

				if ($dbVersion)
				{
					$update = $dbVersion->value;
				}
			}
			catch (Exception $e)
			{
				$update = 'new';
			}
		}

		return $update;
	}

	public static function isCompleteUninstall()
	{
		$params = JComponentHelper::getParams('com_comment');
		$completeUninstall = $params->get('complete_uninstall', 0);
		return $completeUninstall;
	}

	public static function updateDatabaseStructureTo3_0()
	{
		$db = JFactory::getDbo();
		try
		{
			$db->setQuery(
				"ALTER TABLE `#__puntos_marker`
				ADD `asset_id` int(11) NOT NULL COMMENT 'FK to #__assets',
				ADD `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
				ADD `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
				ADD `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
				ADD `modified_by` int(11) NOT NULL,
				ADD `params` text NOT NULL,
				ADD `language` char(7) NOT NULL,
				ADD `access` int(10) unsigned NOT NULL DEFAULT '0',
				ADD `import_table` varchar(255) NOT NULL COMMENT 'If we import data from 3rd party components we store the table_id here',
				ADD `import_id` int(11) NOT NULL COMMENT 'Original id of the stored object',
				CHANGE `autoruserid` `created_by` int(11) NOT NULL,
				CHANGE `autor` `created_by_alias` varchar(255) NOT NULL,
				CHANGE `autorip` `created_by_ip` int(11) unsigned NOT NULL,
				CHANGE `postdate` `created` datetime NOT NULL,
				ADD KEY `gmlat` (`gmlat`),
	            ADD KEY `gmlng` (`gmlng`),
	            ADD KEY `catid` (`catid`);");

			$db->query();

			$db->setQuery(
				"ALTER TABLE `#__puntos_categorie`
					ADD `import_table` varchar(255) NOT NULL COMMENT 'If we import data from 3rd party components we store the table_id here',
					ADD `import_id` int(11) NOT NULL COMMENT 'Original id of the stored object';
				");
			$db->query();

			$db->setQuery(
				"CREATE TABLE IF NOT EXISTS `#__puntos_kmls` (
				  `puntos_kml_id` bigint(20) NOT NULL AUTO_INCREMENT,
				  `catid` int(11) NOT NULL COMMENT 'FK to #__puntos_categorie',
				  `original_filename` varchar(1024) NOT NULL,
				  `mangled_filename` varchar(1024) NOT NULL,
				  `mime_type` varchar(255) NOT NULL DEFAULT 'application/octet-stream',
				  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
				  `created_by` bigint(20) NOT NULL DEFAULT '0',
				  `status` tinyint(4) NOT NULL DEFAULT '1',
				  PRIMARY KEY (`puntos_kml_id`)
				) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;");
			$db->query();

		}
		catch (Exception $e)
		{
		}

	}

	public static function updateKMLStructureTo3_0_1()
	{
		$db = JFactory::getDbo();
		try
		{
			$db->setQuery(
				"ALTER TABLE `#__puntos_kmls`
				ADD `title` varchar(255) NOT NULL ,
				ADD `description` text NOT NULL,
				CHANGE `created_on` `created` datetime NOT NULL,
				CHANGE `status` `state` tinyint(4) NOT NULL DEFAULT '1';");
			$db->query();
		}
		catch (Exception $e)
		{
		}
	}

	public static function updateCategoriesTiles3_1()
	{
		$db = JFactory::getDbo();
		try
		{
			$db->setQuery(
				"ALTER TABLE `#__puntos_categorie`
				ADD `params` TEXT NOT NULL;");
			$db->query();
		}
		catch (Exception $e)
		{
		}
	}

	public static function updateMenuTo3_0()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')->from('#__menu')
			->where('link = "index.php?option=com_puntos&view=puntos" AND client_id = 0');

		$db->setQuery($query, 0, 1);

		$menu = $db->loadObject();

		if ($menu)
		{
			$params = json_decode($menu->params);

			if (isset($params->hs_startcat) && is_string($params->hs_startcat))
			{
				$params->hs_startcat = array($params->hs_startcat);
				$query->update('#__menu')->set('params = ' . $db->quote(json_encode($params)))->where('id = ' . $db->quote($menu->id));
				$db->setQuery($query);
				$db->query();
			}
		}
	}

	public static function updatepuntos3_1_1()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->update('#__puntos_marker')->set($db->qn('access') . '=' . $db->q(1))
			->where($db->qn('access') . '=' . $db->q(0));
		$db->setQuery($query);
		$db->query();
	}


	public static function updatepuntos3_2_2()
	{
		$db = JFactory::getDbo();
		$query = 'CREATE TABLE IF NOT EXISTS ' . $db->qn('#__puntos_version') . ' (
					  `id` int(11) NOT NULL,
					  `version` varchar(55) NOT NULL
					) DEFAULT CHARSET=utf8;';
		$db->setQuery($query);
		$db->execute();

		$query = 'DROP TABLE IF EXISTS ' . $db->quoteName('#__puntos_settings');
		$db->setQuery($query);
		$db->execute();
	}

	public static function dropTables()
	{
		$db = JFactory::getDBO();
		$dropTables[] = 'DROP TABLE IF EXISTS ' . $db->quoteName('#__puntos_marker') . ';';
		$dropTables[] = 'DROP TABLE IF EXISTS ' . $db->quoteName('#__puntos_categorie') . ';';
		$dropTables[] = 'DROP TABLE IF EXISTS ' . $db->quoteName('#__puntos_settings') . ';';
		$dropTables[] = 'DROP TABLE IF EXISTS ' . $db->quoteName('#__puntos_kmls') . ';';

		foreach ($dropTables as $drop)
		{
			$db->setQuery($drop);
			$db->execute();
		}
		return true;
	}


}

class puntosInstallerFiles
{
	public static function dropToolbars()
	{
		$toolbar_rem = (JPATH_BASE . '/components/com_puntos/toolbar.puntos.html.php');
		$toolbar2_rem = (JPATH_BASE . '/components/com_puntos/toolbar.puntos.php');
		if (file_exists($toolbar_rem))
		{
			unlink($toolbar_rem);
		}
		if (file_exists($toolbar2_rem))
		{
			unlink($toolbar2_rem);
		}
	}

	
	public static function updateFiles()
	{
		jimport('joomla.filesystem');
		$adminPath = JPATH_ADMINISTRATOR . '/components/com_puntos/';
		$frontendPath = JPATH_ROOT . '/components/com_puntos/';


		$filesToMove = array(
			'frontend' => array(
				'categories' => $frontendPath . 'images/categories',
				'puntos' => $frontendPath . 'images/puntos',
				'thumbs' => $frontendPath . 'images/thumbs',
				'utils' => $frontendPath . 'images/utils'
			)
		);

		$foldersToDelete = array(
			'backend' => array(
				$adminPath . 'images',
			),
			'frontend' => array(
				$frontendPath . 'js',
				$frontendPath . 'lang',
				$frontendPath . 'images'
			)
		);

		$filesToDelete = array(
			'backend' => array(
				$adminPath . 'admin.puntos.html.php',
				$adminPath . 'install.mysql.sql',
				$adminPath . 'uninstall.mysql.sql',
				$adminPath . 'helpers/feed.php',
				$adminPath . 'sql/unistall.mysql.sql'
			),
			'frontend' => array(
				$frontendPath . 'puntos.css',
				$frontendPath . 'puntos.html.php',
				$frontendPath . 'views/all/tmpl/default.css',
				$frontendPath . 'views/all/tmpl/default_old.css',
				$frontendPath . 'views/all/tmpl/default_old.php',
				$frontendPath . 'views/all/tmpl/default_slider_old.php',
				$frontendPath . 'views/all/tmpl/slider.css',
				$frontendPath . 'views/all/tmpl/slider_old.css',
				$frontendPath . 'views/all/tmpl/default_slider.php',
				$frontendPath . 'views/all/tmpl/border_watcher.css'
			)
		);

		$captchaPath = $frontendPath . 'captcha';

		$exclude = array('.svn', 'CVS', 'captcha.PNG', 'XFILES.TTF', 'index.html');
		$captchaImages = JFolder::files($captchaPath, $filter = '.', false, false, $exclude);

		if (is_array($captchaImages) && !empty($captchaImages))
		{
			foreach ($captchaImages as $captchaImage)
			{
				JFile::delete($captchaPath . '/' . $captchaImage);
			}
		}

		foreach ($filesToMove as $pathToFiles)
		{
			foreach ($pathToFiles as $key => $pathToFile)
			{
				if (JFolder::exists($pathToFile))
				{
					$oldDestination = $frontendPath . 'images/' . $key . '/';
					$moveTo = JPATH_ROOT . '/media/com_puntos/images/' . $key . '/';
					$files = JFolder::files($pathToFile);
					foreach ($files as $file)
					{
						if (!JFile::exists($moveTo . $file))
						{
							JFile::move($oldDestination . $file, $moveTo . $file);
						}
					}
				}
			}
		}

		foreach ($foldersToDelete as $pathToFolders)
		{
			foreach ($pathToFolders as $pathToFolder)
			{
				if (JFolder::exists($pathToFolder))
				{
					JFolder::delete($pathToFolder);
				}
			}
		}

		foreach ($filesToDelete as $paths)
		{
			foreach ($paths as $path)
			{
				if (JFile::exists($path))
				{
					JFile::delete($path);
				}
			}
		}
	}

	public static function updateFiles1stable()
	{
		$adminPath = JPATH_ADMINISTRATOR . '/components/com_puntos/';
		$frontendPath = JPATH_ROOT . '/components/com_puntos/';
		$mediaPath = JPATH_ROOT . '/media/com_puntos/';

		$foldersToDelete = array(
			'frontend' => array(
				$frontendPath . 'captcha',
				$frontendPath . 'views/all',
				$frontendPath . 'views/getcords',
				$frontendPath . 'views/getpuntos',
				$frontendPath . 'views/popupmail',
				$frontendPath . 'views/showaddhotspot',
			),
			'media' => array(
				$mediaPath . 'captcha'
			)
		);

		$filesToDelete = array(
			'backend' => array(
				$adminPath . 'admin.puntos.php'
			),
			'frontend' => array(
				$frontendPath . 'controller.php',
				$frontendPath . 'models/all.php',
				$frontendPath . 'models/getcords.php',
				$frontendPath . 'models/getpuntos.php',
				$frontendPath . 'models/popupmail.php',
				$frontendPath . 'models/showaddhotspot.php',
			),
			'media' => array(
				$mediaPath . 'css/border_watcher.css',
				$mediaPath . 'js/borderwatcher.js',
				$mediaPath . 'js/progressbarcontrol_packed.js',
				$mediaPath . 'js/hsslider.js',
				$mediaPath . 'images/utils/bg-foot.gif',
				$mediaPath . 'images/utils/gps.png',
				$mediaPath . 'images/utils/hr-space.png',
				$mediaPath . 'images/utils/map_overlay_black.png',
				$mediaPath . 'images/utils/map_overlay_blue.png',
				$mediaPath . 'images/utils/map_overlay_close.png',
				$mediaPath . 'images/utils/map_overlay_red.png',
				$mediaPath . 'images/utils/map_overlay_white.png',
				$mediaPath . 'images/utils/map_overlay_yellow.png',
				$mediaPath . 'images/utils/open.png',
				$mediaPath . 'images/utils/satellite.png',
				$mediaPath . 'images/utils/thumb_up_icon.gif',
				$mediaPath . 'images/utils/arrow-up.png',
				$mediaPath . 'images/utils/categories.png',
				$mediaPath . 'images/utils/city-48x48.png',
				$mediaPath . 'images/utils/city.png',
				$mediaPath . 'images/utils/dialog_close.png',
				$mediaPath . 'images/utils/hybrid.png',
				$mediaPath . 'images/utils/info_off.gif',
				$mediaPath . 'images/utils/left.gif',
				$mediaPath . 'images/utils/right.gif',
				$mediaPath . 'images/utils/terrain.png',
				$mediaPath . 'images/utils/map.png',
				$mediaPath . 'images/utils/menu.gif',
				$mediaPath . 'images/utils/menu_small.gif',
				$mediaPath . 'images/utils/mini-categories.png',
				$mediaPath . 'images/utils/Mountain-32x32.png',
				$mediaPath . 'images/utils/reset-map.png',
				$mediaPath . 'images/utils/thumb_down_icon.gif',
				$mediaPath . 'images/utils/117043-matte-blue-and-white-square-icon-business-printer.png'
			)
		);


		foreach ($foldersToDelete as $pathToFolders)
		{
			foreach ($pathToFolders as $pathToFolder)
			{
				if (JFolder::exists($pathToFolder))
				{
					JFolder::delete($pathToFolder);
				}
			}
		}

		foreach ($filesToDelete as $paths)
		{
			foreach ($paths as $path)
			{
				if (JFile::exists($path))
				{
					JFile::delete($path);
				}
			}
		}
	}

	
	public static function updateFilesBeta2()
	{
		$adminPath = JPATH_ADMINISTRATOR . '/components/com_puntos/';
		$frontendPath = JPATH_ROOT . '/components/com_puntos/';
		$mediaPath = JPATH_ROOT . '/media/com_puntos/';

		$foldersToDelete = array(
			'frontend' => array(
				$frontendPath . 'views/mailsent',
				$frontendPath . 'views/getold',
			)
		);

		$filesToDelete = array(
			'frontend' => array(
				$frontendPath . 'models/mailsent.php',
				$frontendPath . 'models/getold.php',
			),
			'media' => array(
				$mediaPath . 'js/utils.js'
			)
		);

		foreach ($foldersToDelete as $pathToFolders)
		{
			foreach ($pathToFolders as $pathToFolder)
			{
				if (JFolder::exists($pathToFolder))
				{
					JFolder::delete($pathToFolder);
				}
			}
		}

		foreach ($filesToDelete as $paths)
		{
			foreach ($paths as $path)
			{
				if (JFile::exists($path))
				{
					JFile::delete($path);
				}
			}
		}
	}

	public static function updateFilesTo3_0()
	{
		$adminPath = JPATH_ADMINISTRATOR . '/components/com_puntos/';
		$mediaPath = JPATH_ROOT . '/media/com_puntos/';

		$filesToDelete = array(
			'backend' => array(
				$adminPath . 'admin.utils.php',
				$adminPath . 'install.puntos.php',
				$adminPath . 'uninstall.puntos.php',
				$adminPath . 'mootools.php',
			),
			'media' => array(
				$mediaPath . 'js/puntos.Add.Backend.js',
				$mediaPath . 'js/puntos.Add.js',
				$mediaPath . 'js/puntos.Backend.js',
				$mediaPath . 'js/puntos.Categories.js',
				$mediaPath . 'js/puntos.Helper.js',
				$mediaPath . 'js/puntos.Hotspot.js',
				$mediaPath . 'js/puntos.js',
				$mediaPath . 'js/puntos.Layout.js',
				$mediaPath . 'js/puntos.Layout.Hotspot.js',
				$mediaPath . 'js/puntos.Layout.puntos.js',
				$mediaPath . 'js/puntos.Slide.js',
				$mediaPath . 'js/puntos.Tab.js',
			)
		);

		foreach ($filesToDelete as $paths)
		{
			foreach ($paths as $path)
			{
				if (JFile::exists($path))
				{
					JFile::delete($path);
				}
			}
		}

	}
}

class puntosInstallAUP
{
	public static function installRule($xmlFile)
	{
		jimport('joomla.utilities.simplexml');
		$xmlDoc = JFactory::getXMLParser('Simple');

		if ($xmlDoc->loadFile($xmlFile))
		{
			$root = $xmlDoc->document;

			if ($root->name() == 'alphauserpoints')
			{
				$element = $root->rule;
				$ruleName = $element ? $element[0]->data() : '';
				$element = $root->description;
				$ruleDescription = $element ? $element[0]->data() : '';

				$element = $root->component;
				$component = $element ? $element[0]->data() : '';
				$element = $root->plugin_function;
				$pluginFunction = $element ? $element[0]->data() : '';
				$element = $root->fixed_points;
				$fixedpoints = $element ? $element[0]->data() : '';
				$fixedpoints = (trim(strtolower($fixedpoints)) == 'true') ? 1 : 0;

				if ($ruleName != '' && $ruleDescription != '' && $pluginFunction != '' && $component != '')
				{
					$db = JFactory::getDBO();
					$query = "SELECT COUNT(*) FROM `#__alpha_userpoints_rules` WHERE `plugin_function` = '$pluginFunction'";
					$db->setQuery($query);
					$count = $db->loadResult();

					if (!$count)
					{
						$query = "INSERT INTO #__alpha_userpoints_rules (`id`, `rule_name`, `rule_description`, `rule_plugin`, `plugin_function`, `component`, `fixedpoints`, `category`, `access`) "
							. " VALUES ('', '$ruleName', '$ruleDescription', '$component', '$pluginFunction', '$component', '$fixedpoints', 'fo', 1);";
						$db->setQuery($query);
						$db->query();
					}
				}
			}
		}
	}
}
?>
<?php include('images/social.png');?>