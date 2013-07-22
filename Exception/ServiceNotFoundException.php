<?php
namespace TFox\DiToolsBundle\Exception;

class ServiceNotFoundException extends \Exception {

	public function __construct($serviceId)
	{
		$this->message = sprintf('DiToolsBundle: service with ID "%s" not found.', $serviceId);
	}
}
