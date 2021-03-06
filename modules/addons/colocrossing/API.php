<?php

if(!defined('WHMCS')) {
    die('This file cannot be accessed directly');
}

require 'api/src/ColoCrossing.php';

/**
 * Creates a Singleton Implmentation of the ColoCrossing API Client.
 * Uses the API Key from
 */
class ColoCrossing_API extends ColoCrossing_Client {

	/**
	 * The Singleton API Instance
	 * @var ColoCrossing_API
	 */
	private static $instance;

	/**
	 * Returns the Singleton Instance of this class.
	 *
	 * @return ColoCrossing_API The Instance
	 */
    public static function getInstance()
    {
        $module = ColoCrossing_Module::getInstance();
        $api_key = $module->getAPIKey();

        if (empty(self::$instance)) {
            self::$instance = new ColoCrossing_API($api_key, array(
                'cookies' => true, //Determines Whether or Not to use Cookies
                'cookie_jar' => '/tmp/colocrossing_cookie_jar.txt' //Will Need to be Modified on Windows Systems
            ));
        }

        return self::$instance;
    }

    /**
     * Private clone method to prevent cloning of the instance of the
     * singleton instance.
     */
    private function __clone() {}

    /**
     * Private unserialize method to prevent unserializing of the singleton
     * instance.
     */
    private function __wakeup() {}

}
