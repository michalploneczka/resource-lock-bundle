<?php
/*
* This file is part of the resource-lock-bundle package.
*
* (c) Wojciech Ciolko <wojciech.ciolko@aboutcoders.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Abc\Bundle\ResourceLockBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Wojciech Ciolko <w.ciolko@aboutcoders.com>>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('abc_resource_lock');

        $supportedDrivers = array('orm', 'custom');
        $rootNode
            ->children()
            ->scalarNode('db_driver')
            ->validate()
            ->ifNotInArray($supportedDrivers)
            ->thenInvalid('The driver %s is not supported. Please choose one of ' . json_encode($supportedDrivers))
            ->end()
            ->cannotBeOverwritten()
            ->isRequired()
            ->cannotBeEmpty()
            ->end()
            ->scalarNode('model_manager_name')->defaultNull()->end()
            ->end();

        $this->addResourceLockSection($rootNode);
        $this->addCustomManagersSection($rootNode);

        return $treeBuilder;
    }


    private function addResourceLockSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
            ->arrayNode('resource_lock')
            ->addDefaultsIfNotSet()
            ->canBeUnset()
            ->children()
                ->scalarNode('lock_manager')->defaultValue('abc.resource_lock.lock_manager.default')->end()
            ->end()
            ->end()
            ->end();
    }

    private function addCustomManagersSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('managers')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('prefix')
                            ->info('This value is used for prefix lock for custom manager.')
                            ->isRequired()
                            ->cannotBeEmpty()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

}
