<?php
/**
 * Created by PhpStorm.
 * User: Izaac
 * Date: 6/12/2015
 * Time: 2:35 PM
 */

namespace Izz0ware;

class Helpers {
	public static function is_plugin_installed($plugin_dir) {
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$plugins = \get_plugins($plugin_dir);
		if ($plugins) return true;
		return false;
	}

	public static function startsWith($haystack, $needle) {
		return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
	}
}