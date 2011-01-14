<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Products Admin Controller
 *
 * @package    Kohanut
 * @author     Kiall Mac Innes
 * @copyright  (c) 2011 Managed I.T.
 * @license
 */
class Controller_Kohanut_Override extends Controller {

	protected $_layout = 'Default';
	protected $_element_areas = array();
	protected $_nav_url = '';

	public function before()
	{
		parent::before();

//		if ( ! class_exists('Twig_Autoloader'))
//		{
//			// Load the Twig class autoloader
//			require Kohana::find_file('vendor', 'Twig/lib/Twig/Autoloader');
//
//			// Register the Twig class autoloader
//			Twig_Autoloader::register();
//		}
//
//		// Include Markdown Extra
//		if ( ! function_exists('Markdown'))
//		{
//			require Kohana::find_file('vendor','Markdown/markdown');
//		}
	}

	public function after()
	{
		$this->request->response = Kohanut::override($this->_layout, $this->_nav_url, $this->_element_areas);

		parent::after();
	}
}