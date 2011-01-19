<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Sprig file field.
 *
 * @package    Sprig
 * @author     Woody Gilk
 * @author     Kelvin Luck
 * @copyright  (c) 2009 Woody Gilk
 * @license    MIT
 */
class Sprig_Field_File extends Sprig_Field_Char {

	/**
	 * @var  string  directory where the image will be loaded from
	 */
	public $directory;

	/**
	 * @var  array   types of images to accept
	 */
	public $types = array('jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx');

	public function __construct(array $options = NULL)
	{
		if (empty($options['directory']) OR ! is_dir($options['directory']))
		{
			$options['directory'] = Upload::$default_directory;
		}

		// Normalize the directory path
		$options['directory'] = rtrim(str_replace(array('\\', '/'), '/', $options['directory']), '/').'/';

		parent::__construct($options);

		// Handle uploads
		$this->callbacks[] = array($this, '_upload_file');
	}

	public function input($name, $value, array $attr = NULL)
	{
		return Form::file($name, $attr);
	}

	public function verbose($value)
	{
		return $this->directory.$value;
	}

	public function _upload_file(Validate $array, $input)
	{
		if ($array->errors())
		{
			// Don't bother uploading
			return;
		}

		// Get the image from the array
		$file = $array[$input];

		unset($array[$input]);

		if ( ! Upload::valid($file) OR ! Upload::not_empty($file))
		{
			// No need to do anything right now
			return;
		}

		if (Upload::valid($file) AND  Upload::type($file, $this->types))
		{
			$filename = strtolower(Text::random('alnum', 5)).'.'.strtolower($file['name']);

			if ($filename2 = Upload::save($file, $filename, $this->directory))
			{
				if ($this->_process_file($filename2))
				{
					$array[$input] = $filename;
				}
				else
				{
					$array->error($input, 'process');
				}
			}
			else
			{
				$array->error($input, 'failed');
			}
		}
		else
		{
			$array->error($input, 'valid');
		}
	}

	protected function _process_file($file)
	{
		return TRUE;
	}

} // End Sprig_Field_File
