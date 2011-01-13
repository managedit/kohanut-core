<?php defined('SYSPATH') or die('No direct script access.');
/**
 *
 *
 * @package    Kohanut
 * @license    http://kohanut.com/license
 */
class Kohanut_Plugin {
	protected static $_registered_plugins = array();
	protected static $_installed_plugins = array();
	protected static $_known_plugins = array();

	public static function register($name, $version = NULL)
	{
		if (empty(Kohanut_Plugin::$_known_plugins))
		{
			try
			{
				$plugins = Sprig::factory('kohanut_plugin')->load(NULL, FALSE);

				foreach ($plugins as $plugin)
				{
					if ($plugin->installed)
					{
						Kohanut_Plugin::$_installed_plugins[$plugin->name] = $plugin->name;
					}

					Kohanut_Plugin::$_known_plugins[$plugin->name] = $plugin->name;
				}
			}
			catch (Database_Exception $e)
			{
				return;
			}
		}

		if ( ! isset(Kohanut_Plugin::$_known_plugins[$name]))
		{
			$plugin = Sprig::factory('kohanut_plugin');
			$plugin->name = $name;
			$plugin->create();
		}
		
		Kohanut_Plugin::$_registered_plugins[$name] = array('name'=> $name, 'version' => $version);

		Event::run('kohanut_plugin_registered', Kohanut_Plugin::$_registered_plugins[$name]);

		if (isset(Kohanut_Plugin::$_installed_plugins[$name]))
		{
			$class = "Kohanut_Plugin_".$name;
			call_user_func(array($class, 'init'));
		}
	}

	public static function install($name)
	{
		if ( ! isset(Kohanut_Plugin::$_registered_plugins[$name]))
		{
			// TODO: Alert the user somehow
			return FALSE;
		}

		$result = FALSE;

		// Give the plugin a chance to get itself ready to go.
		$class = "Kohanut_Plugin_".$name;

		if (method_exists($class, 'install'))
		{
			if ( ! call_user_func(array($class, 'install')))
			{
				unset(Kohanut_Plugin::$_registered_plugins[$name]);
				// TODO: Alert the user somehow
				return FALSE;
			}
		}

		$plugin = Sprig::factory('kohanut_plugin');
		$plugin->name = $name;
		$plugin->load();

		if ($plugin->loaded())
		{
			$plugin->installed = 1;
			$plugin->update();
		}

		Event::run('kohanut_plugin_installed', Kohanut_Plugin::$_registered_plugins[$name]);

		return TRUE;
	}

	public static function uninstall($name)
	{
		if ( ! isset(Kohanut_Plugin::$_installed_plugins[$name]))
		{
			// TODO: Alert the user somehow
			return FALSE;
		}

		$result = FALSE;

		// Give the plugin a chance to get itself ready to be disabled.
		$class = "Kohanut_Plugin_".$name;

		if (method_exists($class, 'uninstall'))
		{
			if ( ! call_user_func(array($class, 'uninstall')))
			{
				// TODO: Alert the user somehow
				return FALSE;
			}
		}

		$plugin = Sprig::factory('kohanut_plugin');
		$plugin->name = $name;
		$plugin->load();

		if ($plugin->loaded())
		{
			$plugin->installed = 0;
			$plugin->update();
		}

		Event::run('kohanut_plugin_uninstalled', Kohanut_Plugin::$_registered_plugins[$name]);

		return TRUE;
	}

	/**
	 * Used to check if a plugin is both installed and active.
	 *
	 * Currently - plugins cant be de-activated so this simply checks installed.
	 *
	 * @param string $name
	 * @return boolean
	 */
	public static function active($name)
	{
		return isset(Kohanut_Plugin::$_installed_plugins[$name]);
	}
}
