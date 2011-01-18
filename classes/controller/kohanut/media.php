<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Kohanut Admin controller. This handles login and logout, ensures that the admin is logged in, does some auto-rendering and templating.
 *
 * @package    Kohanut
 * @author     Michael Peters
 * @copyright  (c) Michael Peters
 * @license    http://kohanut.com/license
 */
class Controller_Kohanut_Media extends Controller {

	protected $auto_render = FALSE;
	
	public function action_media()
	{
		// Get the file path from the request
		$file = $this->request->param('file');
		
		// Find the file extension
		$ext = pathinfo($file, PATHINFO_EXTENSION);
		
		// Remove the extension from the filename
		$file = substr($file, 0, -(strlen($ext) + 1));
		
		// Find the file
		$file = Kohana::find_file('media', $file, $ext);
		
		// If it wasn't found, send a 404
		if ( ! $file )
		{
			// Return a 404 status
			$this->request->status = 404;
			return;
		}
		
		// If the browser sent a "if modified since" header, and the file hasn't changed, send a 304
		if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) AND strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == filemtime($file))
		{
			$this->request->status = 304;
			return;
		}
		
		// Send the file content as the response, and send some basic headers
		$this->request->response = file_get_contents($file);

		if ($ext == 'css')
		{
			$this->request->response = str_replace('%URL_BASE%', url::base(), $this->request->response);
		}
		
		$this->request->headers['Content-Type'] = File::mime_by_ext($ext);
		$this->request->headers['Content-Length'] = filesize($file);
		
		// Tell browsers to cache the file for an hour. Chrome especially seems to not want to cache things
		$cachefor = 3600;
		$this->request->headers['Cache-Control'] = 'max-age='.$cachefor.', must-revalidate, public';
		$this->request->headers['Expires'] = gmdate('D, d M Y H:i:s',time() + $cachefor).'GMT';
		$this->request->headers['Last-Modified'] = gmdate('D, d M Y H:i:s',filemtime($file)).' GMT';
	}
}