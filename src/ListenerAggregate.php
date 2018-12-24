<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\CPanel;

use MSBios\CPanel\Mvc\Controller\ActionControllerInterface;
use MSBios\Guard\GuardManager;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\MvcEvent;
use Zend\Permissions\Acl\AclInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Model\ModelInterface;

/**
 * Class ListenerAggregate
 * @package MSBios\CPanel
 */
class ListenerAggregate extends AbstractListenerAggregate
{
    /**
     * @inheritdoc
     *
     * @param EventManagerInterface $events
     * @param int $priority
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events
            ->attach(MvcEvent::EVENT_RENDER, [$this, 'onRender'], $priority);
        $this->listeners[] = $events
            ->attach(MvcEvent::EVENT_DISPATCH_ERROR, [$this, 'onDispatchError'], $priority);
    }

    /**
     * @param EventInterface $e
     */
    public function onRender(EventInterface $e)
    {
        // Get service manager
        $serviceManager = $e->getApplication()->getServiceManager();

        // Get view helper plugin manager
        /** @var \Zend\View\HelperPluginManager $helperPluginManager */
        $helperPluginManager = $serviceManager->get('ViewHelperManager');

        // Get navigation plugin
        /** @var \Zend\View\Helper\Navigation $plugin */
        $plugin = $helperPluginManager->get('navigation');

        // Fetch ACL and role from service manager or identity (authentication service)
        // …

        /** @var GuardManager $guardManager */
        $guardManager = $serviceManager->get(GuardManager::class);

        /** @var AclInterface $acl */
        $acl = $guardManager->getAcl();

        $plugin->setAcl($acl);

        // var_dump($guardManager->getIdentityProvider()->getIdentityRoles()); die();

        foreach ($guardManager->getIdentityProvider()->getIdentityRoles() as $role) {
            $plugin->setRole($role);
        }
    }

    /**
     * @param EventInterface $e
     */
    public function onDispatchError(EventInterface $e)
    {
        /** @var string $error */
        $error = $e->getError();

        if (empty($error)) {
            return;
        }

        /** @var ModelInterface $viewModel */
        $viewModel = $e->getViewModel();

        /** @var ServiceLocatorInterface $serviceManager */
        $serviceManager = $e->getApplication()
            ->getServiceManager();

        if ($e->getTarget() instanceof ActionControllerInterface) {
            $viewModel->setTemplate(
                $serviceManager->get(Module::class)['default_layout_authorized']
            );
        }
    }
}
