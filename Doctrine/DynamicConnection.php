<?php

namespace Erp\Bundle\ConsoleBundle\Doctrine;

use Doctrine\DBAL\Connection as DoctrineConnection;
use Erp\Bundle\ConsoleBundle\Domain\Service\DynamicConnectionService;

class DynamicConnection extends DoctrineConnection{
  /**
   * Dynamic Database connection service
   *
   * @var DynamicConnectionService
   */
  private $_service;

  public function connect(){
    if($this->isConnected()) return false;

    // TODO: change to specific Exception
    if(null === $this->_service) throw new \Exception("Dynamic connection service is not specific.");

    $reflectionClass = new \ReflectionClass('Doctrine\DBAL\Connection');
    /** @var \ReflectionProperty */
    $reflectionProperty = $reflectionClass->getProperty('_params');
    $reflectionProperty->setAccessible(true);

    /** @var array */
    $params = $this->_service->extendParams($reflectionProperty->getValue($this));
    $reflectionProperty->setValue($this, $params);

    $reflectionProperty->setAccessible(false);

    return parent::connect();
  }

  /**
   * Set Dynamic Database connection service
   *
   * @param DynamicConnectionService $service
   */
  public function setService(DynamicConnectionService $service){
    $this->_service = $service;
  }
}
