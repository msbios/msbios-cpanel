<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\CPanel;

use MSBios\Guard\GuardManager;
use Zend\EventManager\Event;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManager;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\Mvc\ApplicationInterface;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class Module
 * @package MSBios\CPanel
 */
class Module extends \MSBios\Module implements BootstrapListenerInterface
{
    /** @const VERSION */
    const VERSION = '1.0.51';

    /**
     * @inheritdoc
     *
     * @return string
     */
    protected function getDir()
    {
        return __DIR__;
    }

    /**
     * @inheritdoc
     *
     * @return string
     */
    protected function getNamespace()
    {
        return __NAMESPACE__;
    }

    /**
     * @inheritdoc
     *
     * @param EventInterface $e
     * @return array|void
     */
    public function onBootstrap(EventInterface $e)
    {
        /** @var ApplicationInterface $target */
        $target = $e->getTarget();

        /** @var ServiceLocatorInterface $serviceManager */
        $serviceManager = $target->getServiceManager();

        /** @var EventManager $eventManager */
        $eventManager = $target->getEventManager();

        /**
         * @param EventInterface $event
         */
        $onDispatch = function (EventInterface $event) use ($serviceManager) {

            if (is_null($event->getParam('page')->getResource())) {
                return;
            }

            /** @var GuardManager $guardManager */
            $guardManager = $serviceManager->get(GuardManager::class);
            $event->stopPropagation(true);
            return $guardManager->isAllowed($event->getParam('page')->getResource());
        };

        // $eventManager
        //     ->getSharedManager()
        //     ->attach(\Zend\View\Helper\Navigation\AbstractHelper::class, 'isAllowed', $onDispatch);
    }
}
