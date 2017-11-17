<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 *
 */
namespace MSBios\CPanel\Controller;

use MSBios\CPanel\Mvc\Controller\ActionControllerInterface;
use MSBios\Guard\GuardInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Class IndexController
 * @package MSBios\CPanel\Controller
 */
class IndexController extends AbstractActionController implements
    ActionControllerInterface,
    GuardInterface
{
    /**
     * @return ViewModel
     */
    public function indexAction()
    {
        return new ViewModel([]);
    }

    /**
     * @return ViewModel
     */
    public function loginAction()
    {
        return new ViewModel([]);
    }
}
