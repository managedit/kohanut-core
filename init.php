<?php defined('SYSPATH') or die('No direct script access.');

// Grab the list of modules, and check if the install folder is hanging around, if it is, set the install route
$modules = Kohana::modules();

if (is_dir($modules['kohanut'].'/classes/controller/kohanut/install'))
{
	Route::set('kohanut-install','admin/install')
		->defaults(array(
			'controller' => 'install',
			'action'     => 'index',
			'directory'  => 'kohanut/admin/install'
		));
}

// Media required for kohanut admin
Route::set('kohanut-media','media(/<file>)', array('file' => '.+'))
	->defaults(array(
		'controller' => 'media',
		'action'     => 'media',
		'directory'  => 'kohanut',
		'file'       => NULL,
	));

// Kohanut login route
Route::set('kohanut-login','admin/<action>',array('action'=>'login|logout|lang'))
	->defaults(array(
		'controller' => 'admin',
		'directory'  => 'kohanut/admin',
	));
// Kohanut Plugin Admin route
Route::set('kohanut-plugin-admin','admin/plugin/<controller>(/<action>(/<params>))',array('params'=>'.*'))
	->defaults(array(
		'controller' => 'pages',
		'action'     => '',
		'directory'  => 'kohanut/plugin'
	));

// Kohanut Admin route
Route::set('kohanut-admin','admin(/<controller>(/<action>(/<params>)))',array('params'=>'.*'))
	->defaults(array(
		'controller' => 'pages',
		'action'     => '',
		'directory'  => 'kohanut/admin'
	));

