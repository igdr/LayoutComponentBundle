<?php
namespace Igdr\Bundle\LayoutComponentBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
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
        $configuredPlaces = array();

        foreach ($container->getParameter('kernel.bundles') as $bundle) {
            $reflection = new \ReflectionClass($bundle);
            if (is_file($file = dirname($reflection->getFilename()) . '/Resources/config/layout.yml')) {
                $bundleConfig = Yaml::parse(realpath($file));
                if (is_array($bundleConfig)) {
                    $configuredPlaces = array_replace_recursive($configuredPlaces, $bundleConfig);
                }
            }
        }

        // validate menu configurations
        $configuration    = new ComponentConfiguration();
        $configuredPlaces = $this->processConfiguration($configuration, array('places' => $configuredPlaces));

        $places = !empty($configuredPlaces['places']) ? $configuredPlaces['places'] : array();
        $container->setParameter('igdr_layout_component.places', $places);
    }
}
