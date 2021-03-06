<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Kohanut User Model
 *
 * @package    Kohanut
 * @author     Michael Peters
 * @copyright  (c) Michael Peters
 * @license    http://kohanut.com/license
 */
class Model_Kohanut_User extends Sprig_Model_User {

	protected function _init()
	{
		parent::_init();

		$this->_fields['last_login']->null = TRUE;
	}

} // End User