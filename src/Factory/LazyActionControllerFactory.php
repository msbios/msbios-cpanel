<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\CPanel\Factory;

use Interop\Container\ContainerInterface;
use MSBios\CPanel\Module;
use MSBios\CPanel\Mvc\Controller\FormElementManagerAwareInterface;
use MSBios\CPanel\Mvc\Controller\LazyActionControllerInterface;
use MSBios\CPanel\Mvc\Controller\OptionsAwareInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class LazyActionControllerFactory
 * @package MSBios\CPanel\Factory
 */
class LazyActionControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return mixed
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var LazyActionControllerInterface $controller */
        $controller = new $requestedName();

        if ($controller instanceof FormElementManagerAwareInterface) {
            $controller->setFormElementManager(
                $container->get('FormElementManager')
            );
        }

        if ($controller instanceof OptionsAwareInterface) {
            $controller->setOptions(
                $container->get(Module::class)->get('controllers')->get($requestedName)
            );
        }

        return $controller;
    }
}
