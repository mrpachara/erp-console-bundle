<?php

namespace Erp\Bundle\ConsoleBundle\Infrastructure;

use Erp\Bundle\ConsoleBundle\Domain\Service\DynamicConnectionService;

/**
 * Dynamic Database Name Connection Service
 */
class DynamicDbnameConnectionService implements DynamicConnectionService{
  /** @var string $dbname */
  private $dbname;

  /**
   * Constructor
   */
  public function __construct(){
    $this->dbname = null;
  }

  function setConnectionId(string $connectionId){
    $this->dbname = $connectionId;
  }

  public function extendParams(array $params){
    $dbname = $this->getDbname();
    // TODO: change to specific Exception
    if(empty($dbname)) throw new \Exception("Unspecific Database name!!!");

    $params['dbname'] = $dbname;

    return $params;
  }

  /**
   * get database Name
   *
   * @return string
   */
  function getDbname(){
    return $this->dbname;
  }
}
