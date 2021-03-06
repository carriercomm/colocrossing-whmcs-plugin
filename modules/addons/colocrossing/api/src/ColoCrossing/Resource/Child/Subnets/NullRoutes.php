<?php

/**
 * Handles retrieving data from the API's subnet null routes sub-resource
 *
 * @category   ColoCrossing
 * @package    ColoCrossing_Resource
 * @subpackage ColoCrossing_Resource_Child_Subnets
 */
class ColoCrossing_Resource_Child_Subnets_NullRoutes extends ColoCrossing_Resource_Child_Abstract
{

	/**
	 * @param ColoCrossing_Client $client The API Client
	 */
	public function __construct(ColoCrossing_Client $client)
	{
		parent::__construct($client->subnets, $client, 'null_route', '/null-routes');
	}

	/**
	 * Retrieve a Collection of Null Routes on the provided Ip Address.
	 * @param  int 		$parent_id 		The Parent Id
	 * @param  string 	$ip_address 	The Ip Address. If invalid empty array is returned.
	 * @param  array 	$options    	The Options for the page and sort.
	 * @return array|ColoCrossing_Collection<ColoCrossing_Object_NullRoute> The Null Routes
	 */
	public function findAllByIpAddress($parent_id, $ip_address, array $options = null)
	{
		if (!filter_var($ip_address, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
		{
			return array();
		}

		$options = isset($options) && is_array($options) ? $options : array();
		$options['filters'] = array(
			'ip_address' => $ip_address
		);

		return $this->findAll($options, $parent_id);
	}

}
