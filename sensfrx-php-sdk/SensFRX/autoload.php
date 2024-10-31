<?php
/**
 * An example of a project-specific implementation.
 *
 * After registering this autoload function with SPL, the following line
 * would cause the function to attempt to load the \Foo\Bar\Baz\Qux class
 * from /path/to/project/src/Baz/Qux.php:
 *
 *      new \Foo\Bar\Baz\Qux;
 *
 * @param string $class The fully-qualified class name.
 * @return void
 */
spl_autoload_register(function ($class) {
	// project-specific namespace prefix
	$prefix = 'SensFRX\\';
	// base directory for the namespace prefix
	$base_dir = __DIR__ . '/';
	// if (isset($_SERVER['SDK_ENV'])) {
	// 	// Sanitize the input
	// 	$sdk_env = sanitize_text_field($_SERVER['SDK_ENV']);
	// 	if (!defined('SENSFRX_ENV')) {
	// 		switch (dirname(__FILE__)) {
	// 			case '/var/www/html/sensfrx-php-sdk/SensFRX':
	// 				$sdk_env = 'development';
	// 				break;
				
	// 			default:
	// 				$sdk_env = 'production';
	// 				break;
	// 		}
	// 		define('SENSFRX_ENV', isset($sdk_env) ? $sdk_env : 'development');
	// 	}
	// } else {
	// 	if (!defined('SENSFRX_ENV')) {
	// 		switch (dirname(__FILE__)) {
	// 			case '/var/www/html/sensfrx-php-sdk/SensFRX':
	// 				$_SERVER['SDK_ENV'] = 'development';
	// 				break;
				
	// 			default:
	// 				$_SERVER['SDK_ENV'] = 'production';
	// 				break;
	// 		}
	// 		// Function to validate and set a default SENSFRX_ENV if $_SERVER['SDK_ENV'] is not set or invalid
	// 		if ( isset($_SERVER['SDK_ENV']) ) {
	// 			$env_value = sanitize_text_field( $_SERVER['SDK_ENV'] ); 
	// 			$env_check = null !== $env_value ? $env_value : 'development';
	// 			define('SENSFRX_ENV', $env_check);
	// 		} else {
	// 			define('SENSFRX_ENV', 'development');
	// 		}
	// 	}
	// }
	// does the class use the namespace prefix?
	$len = strlen($prefix);
	if (strncmp($prefix, $class, $len) !== 0) {
		// no, move to the next registered autoloader
		return;
	}
	// get the relative class name
	$relative_class = substr($class, $len);
	// replace the namespace prefix with the base directory, replace namespace
	// separators with directory separators in the relative class name, append
	// with .php
	$file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
	// if the file exists, require it
	if (file_exists($file)) {
		require $file;
	}
});
