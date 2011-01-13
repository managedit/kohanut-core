<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Temporary Kohanut Installer. This is not intended to be permenant.
 *
 * @package    Kohanut
 * @author     Michael Peters
 * @copyright  (c) Michael Peters
 * @license    http://kohanut.com/license
 */
class Controller_Kohanut_Admin_Install extends Controller {
	public function action_index()
	{		
		$models = array(
			'kohanut_block' => 'sprig',
			'kohanut_elementtype' => 'sprig',
			'kohanut_layout' => 'sprig',
			'kohanut_page' => 'sprig',
			'kohanut_plugin' => 'sprig',
			'kohanut_redirect' => 'sprig',
			'kohanut_user' => 'sprig',
			'content' => 'kohanut_element',
			'request' => 'kohanut_element',
			'snippet' => 'kohanut_element',
			'spacer' => 'kohanut_element',
		);

		foreach ($models as $name => $type)
		{
			Migration::factory($name, $type)->sync();
		}

		// Great - Lets start getting content in!

		$users = Sprig::factory('kohanut_user')->load(NULL, FALSE);
		
		// If no users exist yet...
		if (count($users) < 1)
		{
			$admin = Sprig::factory('kohanut_user', array(
				'username' => 'admin',
				'password' => 'admin',
				'password_confirm' => 'admin',
				'last_login' => 0,
			));
			
			$admin->create();
		}

		$pages = Sprig::factory('kohanut_page')->load(NULL, FALSE);

		// If no users exist yet...
		if (count($pages) < 1)
		{
			$home = Sprig::factory('kohanut_page', array(
				'url' => '',
				'name' => 'Home',
				'layout' => 1,
				'islink' => 0,
				'showmap' => 1,
				'shownav' => 1,
				'title' => 'Home',
				'metadesc' => '',
				'metakw' => '',
			));

			$home->insert_as_new_root(1);
		}

		$element_types = array(
			'content' => array('name' => 'content', 'display_name' => 'Content'),
			'request' => array('name' => 'request', 'display_name' => 'Request'),
			'snippet' => array('name' => 'snippet', 'display_name' => 'Snipper'),
			'spacer' => array('name' => 'spacer', 'display_name' => 'Spacer'),
		);

		foreach ($element_types as $name => $model)
		{
			if ( ! $model instanceof Sprig)
			{
				$model = Sprig::factory('kohanut_elementtype', $model)->load();

				if ( ! $model->loaded())
				{
					$model->create();
				}

				// Store for easy retrieval of ID's (which may not always be the same) later.
				$element_types[$name] = $model;
			}
		}

		// Sample data is done away with. This should be an optional step.

		echo "done";
	}
}