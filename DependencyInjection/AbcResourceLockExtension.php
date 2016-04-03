<?php

namespace Abc\Bundle\ResourceLockBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

/**
 * @author Wojciech Ciolko <w.ciolko@gmail.com>
 */
class AbcResourceLockExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config/services'));

        if ('custom' !== $config['db_driver']) {
            $loader->load(sprintf('%s.xml', $config['db_driver']));
        }

        $this->remapParametersNamespaces($config, $container, array(
            '' => array(
                'model_manager_name' => 'abc.resource_lock.model_manager_name'
            )
        ));

        if (!empty($config['resource_lock'])) {
            $this->loadResourceLock($config['resource_lock'], $container, $loader, $config['db_driver']);
        }
    }

    private function loadResourceLock(array $config, ContainerBuilder $container, XmlFileLoader $loader, $dbDriver)
    {
        if ('custom' !== $dbDriver) {
            $loader->load(sprintf('%s_resource_lock.xml', $dbDriver));
        }

        $container->setAlias('abc.resource_lock.lock_manager', $config['lock_manager']);

        $this->remapParametersNamespaces($config, $container, array(
            '' => array(
                'resource_lock_class' => 'abc.resource_lock.model.resource_lock.class',
            )
        ));

        $loader->load('services.xml');
    }

    protected function remapParameters(array $config, ContainerBuilder $container, array $map)
    {
        foreach ($map as $name => $paramName) {
            if (array_key_exists($name, $config)) {
                $container->setParameter($paramName, $config[$name]);
            }
        }
    }

    protected function remapParametersNamespaces(array $config, ContainerBuilder $container, array $namespaces)
    {
        foreach ($namespaces as $ns => $map) {
            if ($ns) {
                if (!array_key_exists($ns, $config)) {
                    continue;
                }
                $namespaceConfig = $config[$ns];
            } else {
                $namespaceConfig = $config;
            }
            if (is_array($map)) {
                $this->remapParameters($namespaceConfig, $container, $map);
            } else {
                foreach ($namespaceConfig as $name => $value) {
                    $container->setParameter(sprintf($map, $name), $value);
                }
            }
        }
    }
} 