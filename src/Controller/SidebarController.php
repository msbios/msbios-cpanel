<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 *
 */
namespace MSBios\CPanel\Controller;

use MSBios\CPanel\Mvc\Controller\ActionControllerInterface;
use MSBios\Navigation\NavigationAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Navigation\Navigation;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use Zend\View\Model\JsonModel;

/**
 * Class SidebarController
 * @package MSBios\CPanel\Controller
 */
class SidebarController extends AbstractActionController implements ActionControllerInterface, ResourceInterface
{
    use NavigationAwareTrait;

    /**
     * SidebarController constructor.
     * @param Navigation $navigation
     */
    public function __construct(Navigation $navigation)
    {
        $this->setNavigation($navigation);
    }

    /**
     * @return JsonModel|\Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        return new JsonModel([
            'data' => $this->getNavigation()->toArray()
        ]);
    }

    /**
     * @inheritdoc
     *
     * @return string
     */
    public function getResourceId()
    {
        return self::class;
    }
}
