<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Kohanut Admin controller. This handles login and logout, ensures that the admin is logged in, does some auto-rendering and templating.
 *
 * @package    Kohanut
 * @author     Michael Peters
 * @copyright  (c) Michael Peters
 * @license    http://kohanut.com/license
 */
class Controller_Kohanut_Admin_Admin extends Controller_Kohanut_Admin {
	// admin pages require login
	protected $requires_login = FALSE;
	
	public function before()
	{	
		// Do not require login for login/logout
		if ($this->request->action === 'login' OR $this->request->action === 'logout')
		{
			$this->requires_login = FALSE;
		}
	}
	
	public function __call($method,$args)
	{
		$this->admin_error("Could not find the url you requested.");
	}
	
	public function action_login()
	{
		// If the user is logged in, redirect them
		if ($this->user)
		{
			$this->request->redirect('admin/pages');
		}
		
		// Overide default view and bind with $user and $errors
		$this->view = View::factory('kohanut/login')
			->bind('user', $user)
			->bind('errors', $errors);
		
		$this->view->title = "Login";
		
		// Load an empty user
		$user = Sprig::factory('kohanut_user');
		
		// Load rules defined in sprig model into validation factory    
		$post = Validate::factory($_POST)
			->rules('username', $user->field('username')->rules)
			->rules('password', $user->field('password')->rules);
		
		// Validate the post    
		if ($_POST)
		{
			$user->values($post->as_array());
			
			if ($post->check())
			{
				// Load the user by username and password
				$user->values($post->as_array())->load();
		
				if ($user->loaded())
				{
					// Store the user id
					Cookie::set('user', $user->id);
		
					// Redirect to the home page
					$this->request->redirect(Route::get('kohanut-admin')->uri(array('controller'=>'pages')));
				}
				else
				{
					$post->error('username', 'invalid');
				}
			}
		}
		
		$errors = $post->errors('kohanut',TRUE);
	}
	
	public function action_logout()
	{
		// Delete the user cookie
		Cookie::delete('user');
			
		// Redirect to the login
		$this->request->redirect(Route::get('kohanut-login')->uri(array('action'=>'login')));
	}
}
