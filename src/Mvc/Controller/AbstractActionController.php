<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\CPanel\Mvc\Controller;

use MSBios\Guard\GuardAwareInterface;
use Zend\Mvc\Controller\AbstractActionController as DefaultAbstractActionController;
use Zend\Permissions\Acl\Resource\ResourceInterface;

/**
 * Class AbstractActionController
 * @package MSBios\CPanel\Mvc\Controller
 */
abstract class AbstractActionController extends DefaultAbstractActionController implements
    ActionControllerInterface,
    GuardAwareInterface,
    ResourceInterface
{
    /**
     * @return string
     */
    public function getResourceId()
    {
        return get_called_class();
    }
}
