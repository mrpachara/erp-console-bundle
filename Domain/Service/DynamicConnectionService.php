<?php

namespace Erp\Bundle\ConsoleBundle\Domain\Service;

/**
 * Database Connection Service Interface
 */
interface DynamicConnectionService{
  /**
   * Set Connection Id
   *
   * @param string $connectionId connection id
   */
  function setConnectionId(string $connectionId);

  /**
   * Extend existed params
   *
   * @param array $params exsited params
   *
   * @return array
   */
  function extendParams(array $params);
}
