<?php

namespace Erp\Bundle\ConsoleBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Definition;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @see http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class ErpConsoleExtension extends Extension implements CompilerPassInterface{
  /** @var array */
  private $config = null;

  public function load(array $configs, ContainerBuilder $container){
    $configuration = new Configuration();
    /** @var array */
    $config = $this->processConfiguration($configuration, $configs);

    $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
    $loader->load('services.yml');

    $this->config = $config;
  }

  public function process(ContainerBuilder $container){
    foreach($this->config['connections'] as $connection => $service){
      /** @var Definition */
      $definition = $container->getDefinition('doctrine.dbal.'.$connection.'_connection');

      $serviceReference = new Reference($service);
      $definition->addMethodCall('setService', array($serviceReference));
    }
  }
}
