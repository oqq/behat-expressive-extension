<?php declare(strict_types = 1);

namespace Oqq\ZendExpressiveExtension\ServiceContainer;

use Behat\Behat\Context\ServiceContainer\ContextExtension;
use Behat\Testwork\ServiceContainer\Extension;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Interop\Container\ContainerInterface;
use Oqq\ZendExpressiveExtension\Context\Initializer\ApplicationAwareInitializer;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Zend\Expressive\Application;

final class ZendExpressiveExtension implements Extension
{
    const CONFIG_KEY = 'zend_expressive';

    public function process(ContainerBuilder $container)
    {
    }

    public function getConfigKey(): string
    {
        return self::CONFIG_KEY;
    }

    public function initialize(ExtensionManager $extensionManager): void
    {
    }

    public function configure(ArrayNodeDefinition $builder): void
    {
        $builder
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('container-file')
                    ->defaultValue(getcwd() . '/config/container.php')
                    ->end()
                ->end()
            ->end()
        ;
    }

    public function load(ContainerBuilder $containerBuilder, array $config)
    {
        $containerFile = $config['container-file'];

        if (!file_exists($containerFile) || !is_readable($containerFile)) {
            throw new \RuntimeException('Could not read file: ' . $containerFile);
        }

        $container = include $containerFile;

        if (! $container instanceof ContainerInterface) {
            throw new \RuntimeException('File responses not with ContainerInterface: ' . $containerFile);
        }

        $definition = new Definition(ApplicationAwareInitializer::class, [$container->get(Application::class)]);
        $definition->addTag(ContextExtension::INITIALIZER_TAG);

        $containerBuilder->setDefinition(self::CONFIG_KEY . '.context_initializer', $definition);
    }
}
