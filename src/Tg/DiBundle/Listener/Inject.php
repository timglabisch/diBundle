<?php

namespace Tg\DiBundle\Listener;

use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Tg\DiBundle\Annotation\Inject as AnnotationInject;

class Inject {

    /**
     * @var AnnotationReader
     */
    protected $annotationReader;
    /**
     * @var ContainerInterface
     */
    protected $container;

    function __construct($annotationReader, $container) {
        $this->annotationReader = $annotationReader;
        $this->container = $container;
    }

    protected function getServicePrefix() {
        return 'service';
    }

    protected function getServiceName($paramName) {
        return lcfirst(substr($paramName, strlen($this->getServicePrefix())));
    }

    function onCall(FilterControllerEvent $controllerEvent) {

        $controller = $controllerEvent->getController();

        if(!is_array($controller))
            return;

        $reflectionMethod = new \ReflectionMethod($controller[0], $controller[1]);

        if(!$this->annotationReader->getMethodAnnotation($reflectionMethod, 'Tg\DiBundle\Annotation\Inject'))
            return;

        foreach($reflectionMethod->getParameters() as $param)
            $this->injectParam($controllerEvent, $param);
    }

    protected function injectParam(FilterControllerEvent $controllerEvent, $param) {
        if(strpos($param->getName(), $this->getServicePrefix()) !== 0)
            return;

        $serviceName = $this->getServiceName($param->getName());
        $controllerEvent->getRequest()->attributes->set($param->getName(), $this->container->get($serviceName));
    }

}