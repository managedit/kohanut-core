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
	protected $_page = '';

	public function after()
	{
		$this->request->response = Kohanut::override($this->_layout, $this->_page, $this->_element_areas);

		parent::after();
	}
}
