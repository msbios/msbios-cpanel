<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\CPanel\Factory;

use Interop\Container\ContainerInterface;
use MSBios\CPanel\Exception\ServiceNotCreatedException;
use MSBios\Db\TablePluginManager;
use MSBios\Resource\RecordRepositoryInterface;
use Zend\Form\FormElementManager\FormElementManagerV3Polyfill;
use Zend\Form\FormInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class ControllerFactory
 * @package MSBios\CPanel\Factory
 */
class ControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return mixed|object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        /** @var TablePluginManager $tableManager */
        $tableManager = $container
            ->get(TablePluginManager::class);

        if (! $tableManager->has($requestedName)) {
            throw new ServiceNotCreatedException(
                sprintf("You need to define a repository alias for the controller %s.", $requestedName)
            );
        }

        /** @var RecordRepositoryInterface $repository */
        $repository = $tableManager
            ->get($requestedName);

        /** @var FormElementManagerV3Polyfill $formElementManager */
        $formElementManager = $container
            ->get('FormElementManager');

        if (! $formElementManager->has($requestedName)) {
            throw new ServiceNotCreatedException(
                sprintf("You need to define a form alias for the controller %s.", $requestedName)
            );
        }

        /** @var FormInterface $form */
        $form = $formElementManager
            ->get($requestedName);

        return new $requestedName($repository, $form);
    }
}
