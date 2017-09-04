<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\CPanel\Initializer;

use Interop\Container\ContainerInterface;
use Zend\Form\FormElementManager\FormElementManagerV3Polyfill;
use Zend\ServiceManager\Initializer\InitializerInterface;

/**
 * Class LazyControllerInitializer
 * @package MSBios\CPanel\Initializer
 */
class LazyControllerInitializer implements InitializerInterface
{
    /**
     * Initialize the given instance
     *
     * @param  ContainerInterface $container
     * @param  object $instance
     * @return void
     */
    public function __invoke(ContainerInterface $container, $instance)
    {
        if ($instance instanceof LazyControllerAwareInterface) {

            /** @var string $controllerName */
            $controllerName = get_class($instance);

            /** @var FormElementManagerV3Polyfill $formElementManager */
            $formElementManager = $container->get('FormElementManager');

            if ($formElementManager->has($controllerName)) {
                $instance->setForm(
                    $formElementManager->get($controllerName)
                );
            }
        }
    }
}