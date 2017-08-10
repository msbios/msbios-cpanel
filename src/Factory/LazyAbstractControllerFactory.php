<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\CPanel\Factory;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use MSBios\CPanel\Config\Config;
use MSBios\CPanel\Config\Controller;
use MSBios\CPanel\Module;
use MSBios\CPanel\Mvc\Controller\LazyActionControllerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class LazyAbstractControllerFactory
 * @package MSBios\CPanel\Factory
 */
class LazyAbstractControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return mixed
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var LazyAbstractActionController $controller */
        $controller = new $requestedName();

        if ($controller instanceof LazyActionControllerInterface) {
            $controller->setEntityManager($container->get(EntityManager::class))
                ->setFormElement($container->get('FormElementManager'))
                ->setOptions(new Controller(
                    $container->get(Module::class)
                        ->getController()
                        ->get($requestedName)
                ));
        }

        return $controller;
    }
}
