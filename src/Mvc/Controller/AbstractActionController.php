<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\CPanel\Mvc\Controller;

use MSBios\Guard\GuardAwareInterface;
use Zend\Mvc\Controller\AbstractActionController as DefaultAbstractActionController;

/**
 * Class AbstractActionController
 * @package MSBios\CPanel\Mvc\Controller
 */
class AbstractActionController extends DefaultAbstractActionController implements
    ActionControllerInterface,
    GuardAwareInterface
{

}
