<?php
namespace Igdr\Bundle\ResourceBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Yaml\Yaml;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class IgdrLayoutComponentExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        //load configuration
        $configuration = $this->getConfiguration($configs, $container);
        $config        = $this->processConfiguration($configuration, $configs);

        $container->setParameter('igdr_layout_component.config', $config);

        //load resource configuration
        $this->loadBundlesConfiguration($container);
    }

    /**
     * @param ContainerBuilder $container
     */
    private function loadBundlesConfiguration(ContainerBuilder $container)
    {
        $configuredComponents = array();

        foreach ($container->getParameter('kernel.bundles') as $bundle) {
            $reflection = new \ReflectionClass($bundle);
            if (is_file($file = dirname($reflection->getFilename()) . '/Resources/config/layout.yml')) {
                $bundleConfig = Yaml::parse(realpath($file));
                if (is_array($bundleConfig)) {
                    $configuredComponents = array_replace_recursive($configuredComponents, $bundleConfig);
                }
            }
        }

        // validate menu configurations
        $configuration        = new ComponentConfiguration();
        $configuredComponents = $this->processConfiguration($configuration, array('resources' => $configuredComponents));

        if (!empty($configuredComponents)) {
            $container->getDefinition('igdr_layout_component.manager.component')->createConfiguraion($configuredComponents);
        }
    }
}
