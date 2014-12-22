<?php

if(!defined('WHMCS')) {
    die('This file cannot be accessed directly');
}

/**
 * ColoCrossing Controller for the Admin WHMCS Module.
 */
class ColoCrossing_Admins_Controller extends ColoCrossing_Controller {

    /**
     * @return string The Base Url
     */
    protected function getBaseUrl() {
        return $this->module->getBaseAdminUrl();
    }

    /**
     * Retrieves the View Directory Path
     *
     * @return string The Path
     */
    protected function getViewDirectoryPath() {
        return implode(DIRECTORY_SEPARATOR, array(dirname(__FILE__), 'views'));
    }

}