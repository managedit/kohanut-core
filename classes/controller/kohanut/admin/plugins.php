<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Plugins Controller
 *
 * @package    Kohanut
 * @author     Michael Peters
 * @copyright  (c) Michael Peters
 * @license    http://kohanut.com/license
 */
class Controller_Kohanut_Admin_Plugins extends Controller_Kohanut_Admin {

	public function action_index()
	{
		$plugins = Sprig::factory('kohanut_plugin')->load(NULL,FALSE);
		
		$this->view->title = "Plugins";
		$this->view->body = View::factory('kohanut/plugins/list', array('plugins' => $plugins));
	}
	
	public function action_install($name)
	{
		Kohanut_Plugin::install($name);

		$this->request->redirect(Route::get('kohanut-admin')->uri(array('controller' => 'plugins')));
	}

	public function action_uninstall($name)
	{
		Kohanut_Plugin::uninstall($name);

		$this->request->redirect(Route::get('kohanut-admin')->uri(array('controller' => 'plugins')));
	}
	
	public function action_edit($id)
	{
		// Sanitize
		$id = (int) $id;
		
		// Find the redirect
		$redirect = Sprig::factory('kohanut_redirect',array('id'=>$id))->load();
		
		if ( ! $redirect->loaded())
		{
			return $this->admin_error("Could not find redirect with id <strong>$id</strong>.");
		}
		
		$errors = false;
		$success = false;
		
		if ($_POST)
		{
			try
			{
				$redirect->values($_POST);
				$redirect->update();
				$success = "Updated Successfully";
			}
			catch (Validate_Exception $e)
			{
				$errors = $e->array->errors('redirect');
			}
		}
		
		$this->view->title = "Editing Redirect";
		$this->view->body = new View('kohanut/redirects/edit');
	
		$this->view->body->redirect = $redirect;
		$this->view->body->errors = $errors;
		$this->view->body->success = $success;
	}
	
	public function action_delete($id)
	{
		
		// Sanitize
		$id = (int) $id;
		
		// Find the redirect
		$redirect = Sprig::factory('kohanut_redirect',array('id'=>$id))->load();
		
		if ( ! $redirect->loaded())
		{
			return $this->admin_error("Could not find redirect with id <strong>$id</strong>.");
		}
		
		$errors = false;

		if ($_POST)
		{
			try
			{
				$redirect->delete();
				$this->request->redirect(Route::get('kohanut-admin')->uri(array('controller'=>'redirects')));
			}
			catch (Validate_Exception $e)
			{
				$errors = array('submit'=>"Delete failed!");
			}
			
		}

		$this->view->title = "Delete Redirect";
		$this->view->body = View::factory('/kohanut/redirects/delete',array('redirect'=>$redirect));
		
		$this->view->body->redirect = $redirect;
		$this->view->body->errors = $errors;
		
	}
}