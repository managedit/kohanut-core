<?php

class Route extends Kohana_Route {
	public static function url($name, array $params = NULL, $protocol = TRUE)
	{
		// Create a URI with the route and convert it to a URL
		return URL::site(Route::get($name)->uri($params), $protocol);
	}
}