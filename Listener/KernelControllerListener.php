<?php
namespace TFox\DiToolsBundle\Listener;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use TFox\DiToolsBundle\Controller\DiToolsInjectableInterface;
use TFox\DiToolsBundle\Annotation\Inject;

class KernelControllerListener {

	/**
	 * @var \Symfony\Component\DependencyInjection\Container
	 */
	private $container;
	
	/**
	 * @var \Doctrine\Common\Annotations\FileCacheReader
	 */
	private $annotationReader;
	
	public function __construct(Container $container)
	{
		$this->container = $container;
		$this->annotationReader = $this->container->get('annotation_reader');	
	}
	
	public function onKernelController(FilterControllerEvent $event)
	{
		$controllerData = $event->getController();
		if(count($controllerData) < 2)
			throw new \Exception('DiToolsBundle: cannot detect controller or method!');
		$controller = $controllerData[0];
		
		if($controller instanceof DiToolsInjectableInterface) {
			//Iterate through controller properties
			$reflection = new \ReflectionClass($controller);
			foreach($reflection->getProperties() as $property) {
				/* @var $property \ReflectionProperty */
				$propertyAnnotations = $this->annotationReader->getPropertyAnnotations($property);
				foreach($propertyAnnotations as $propertyAnnotation) {
					// \TFox\DiToolsBundle\Annotation\Inject : inject a specified service here
					if($propertyAnnotation instanceof Inject) {
						$serviceName = $propertyAnnotation->value;						
						$service = $this->container->get($serviceName);
						
						$property->setAccessible(true);
						$property->setValue($controller, $service);
						$property->setAccessible(false);
					}
				}
			}
		}
		

	}
}
