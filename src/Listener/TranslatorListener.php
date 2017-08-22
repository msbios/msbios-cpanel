<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\CPanel\Listener;

use Zend\EventManager\EventInterface;
use Zend\I18n\Translator\TranslatorInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class TranslatorListener
 * @package MSBios\CPanel\Listener
 */
class TranslatorListener
{
    /**
     * @param EventInterface $e
     */
    public function onDispatch(EventInterface $e)
    {
        /** @var ServiceLocatorInterface $serviceLocator */
        $serviceLocator = $e->getTarget()
            ->getServiceManager();

        /** @var TranslatorInterface $translator */
        $translator = $serviceLocator->get(TranslatorInterface::class);
        $translator->setLocale($e->getRouteMatch()->getParam('locale'));
        $e->getRouter()
            ->setDefaultParam('locale', $translator->getLocale());
    }
}
