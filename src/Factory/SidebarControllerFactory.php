<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\CPanel\Factory;

use Interop\Container\ContainerInterface;
use MSBios\CPanel\Navigation\Sidebar;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class SidebarControllerFactory
 * @package MSBios\CPanel\Factory
 */
class SidebarControllerFactory implements FactoryInterface
{
    /**
     * @inheritdoc
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return mixed|object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new $requestedName(
            $container->get(Sidebar::class)
        );
    }
}
