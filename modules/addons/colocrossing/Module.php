<?php

if(!defined('WHMCS')) {
    die('This file cannot be accessed directly');
}

require 'Router.php';
require 'Controller.php';
require 'Utilities.php';
require 'Model.php';
require 'API.php';

/**
 * ColoCrossing Module for WHMCS Module
 * Handles Module Configuration, Activation, and Deactivation
 */
class ColoCrossing_Module {

	/**
	 * The Singleton Module Instance
	 * @var ColoCrossing_Module
	 */
	private static $instance;

	/**
	 * The Client and Admin Routers for this
	 * @var array<ColoCrossing_Router>
	 */
	private $routers;

	/**
	 * Private Constructor to Prevent Instantiation Outside of this Class
	 */
	private function __construct() {}

	/**
	 * Activates the ColoCrossing Module.
	 * Makes a required database changes for this module.
	 *
	 * @return array The result
	 */
	public function activate() {
		// Create Devices Table
	    $query = "CREATE TABLE `mod_colocrossing_devices_services` (" .
	                "`service_id` int(10) NOT NULL," .
	                "`device_id` int(10) NOT NULL," .
	                "PRIMARY KEY (`service_id`, `device_id`)" .
	             ");";
	    full_query($query);

	    // Create Events Table
	    $query = "CREATE TABLE `mod_colocrossing_events` (" .
	                "`id` int(10) NOT NULL AUTO_INCREMENT," .
	                "`user_type` tinyint(1) NOT NULL," .
	                "`user_id` int(10) NOT NULL," .
	                "`description` varchar(255) NOT NULL," .
	                "`time` int(10) NOT NULL," .
	                "PRIMARY KEY (`id`)" .
	             ");";
	    full_query($query);

	    // Return Result
	    return array(
	        'status' => 'success',
	        'description' => 'Specify your API Key in the module configuration to get started.'
	    );
	}

	/**
	 * Deactivates the ColoCrossing Module.
	 * Removes database changes made by this module.
	 *
	 * @return array The result
	 */
	public function deactivate() {
	    // Remove Devices Table
	    $query = "DROP TABLE `mod_colocrossing_devices_services`";
	    $result = full_query($query);

	    // Remove Events Table
	    $query = "DROP TABLE `mod_colocrossing_events`";
	    $result = full_query($query);

	    // Return Result
	    return array(
	        'status' => 'success'
	    );
	}

	/**
	 * Gets the Default Configuration for this Module
	 *
	 * @return array The Default Config
	 */
	public function getDefaultConfiguration() {
	    return array(
	    	'name' => 'ColoCrossing Portal',
	    	'description' => 'This is an addon module that can be used to interact with the ColoCrossing Portal.',
	    	'version' => '1.0',
	    	'author' => 'ColoCrossing',
	    	'language' => 'english',
	    	'fields' => $this->getConfigurationFields()
	    );
	}

	/**
	 * Get the configuration fields available
	 *
	 * @return array The Fields
	 */
	public function getConfigurationFields() {
	    return array(
        	'api_key' => array(
        		'FriendlyName' => 'API Key',
        		'Type' => 'text',
        		'Size' => '50'
        	)
   	    );
	}

	/**
	 * Get configuration of the module
	 *
	 * @return array The Config
	 */
	public function getConfiguration() {
		$configuration = $this->getDefaultConfiguration();

	    $results = select_query('tbladdonmodules', 'setting,value', array('module' => 'colocrossing'));

	    while ($data = mysql_fetch_array($results)) {
	    	$configuration[$data['setting']] = $data['value'];
		}

		return $configuration;
	}

	/**
	 * @return string The Base Admin URL
	 */
	public function getBaseAdminUrl() {
		return BASE_ADMIN_URL;
	}

	/**
	 * @return string The Base Client URL
	 */
	public function getBaseClientUrl() {
		return BASE_CLIENT_URL;
	}

	/**
	 * Get the router of the specified type
	 *
	 * @return ColoCrossing_Admins_Router
	 */
	public function getRouter($type = 'admin') {
		if(isset($this->routers[$type])) {
			return $this->routers[$type];
		}

		switch ($type) {
			case 'admin':
				return $this->routers[$type] = new ColoCrossing_Admins_Router();
			case 'client':
				return $this->routers[$type] = new ColoCrossing_Clients_Router();
		}

		throw new Exception('Unkown router type specified.');
	}

	/**
	 * Dispatch a Request to the Controller
	 *
	 * @param string  $type 	The type of router to send request to.
	 * @param  array  $params 	The Parameters to pass to router
	 * @return mixed  			The Result of the Action
	 */
	public function dispatchRequest($type, array $params = array()) {
		$router = $this->getRouter($type);
		$params = array_merge($_POST, $_GET, $params);

		return $router->dispatch($params);
	}

	/**
	 * Dispatch a Request to the Controller and Action Specified
	 *
	 * @param string  $type 		The type of router to send request to.
	 * @param string  $controller 	The contoller
	 * @param string  $action 		The action
	 * @param  array  $params 		The Parameters to pass to router
	 * @return mixed  				The Result of the Action
	 */
	public function dispatchRequestTo($type, $controller, $action, array $params = array()) {
		$params['controller'] = $controller;
		$params['action'] = $action;

		return $this->dispatchRequest($type, $params);
	}

	/**
	 * Get the API Key
	 *
	 * @return string The API Key
	 */
	public function getAPIKey() {
		$configuration = $this->getConfiguration();

	    return isset($configuration['api_key']) ? $configuration['api_key'] : null;
	}

	/**
	 * Returns the Singleton Instance of this class.
	 *
	 * @return ColoCrossing_Module The Instance
	 */
    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new ColoCrossing_Module();
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
