<?php
namespace Erp\Bundle\ConsoleBundle\Listener;

use Erp\Bundle\ConsoleBundle\Domain\Service\DynamicConnectionService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\HttpKernel\KernelEvents;

use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * Database Connection Subcriber
 */
class ErpConnectionSubscriber implements EventSubscriberInterface{
  /** @var DynamicConnectionService */
  private $_service;

  /**
   * Constructor
   *
   * @param DynamicConnectionService $service
   */
  public function __construct(DynamicConnectionService $service){
    $this->_service = $service;
  }

  public static function getSubscribedEvents(){
    return array(
      ConsoleEvents::COMMAND => array(array('onCommand', 0)),
      KernelEvents::REQUEST => array(array('onKernelRequest', 31))
    );
  }

  /**
   * on Command
   *
   * @param ConsoleCommandEvent $event
   */
  public function onCommand(ConsoleCommandEvent $event){
    $definition = $event->getCommand()->getDefinition();
    $input = $event->getInput();

    $definition->addOption(
        new InputOption('erpdb', null, InputOption::VALUE_OPTIONAL, 'The specification of company database', null)
    );
    $input->bind($definition);

    if($input->hasOption('erpdb') && !empty($input->getOption('erpdb'))){
      $this->_service->setConnectionId($input->getOption('erpdb'));
    }
  }

  /**
   * on Kennel Request
   *
   * @param GetResponseEvent $event
   */
  public function onKernelRequest(GetResponseEvent $event){
    if(!empty($event->getRequest()->attributes->get('_ERP_DB_'))){
      $this->_service->setConnectionId($event->getRequest()->attributes->get('_ERP_DB_'));
    }
  }
}
