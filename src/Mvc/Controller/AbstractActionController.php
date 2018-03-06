<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\CPanel\Mvc\Controller;

use MSBios\Guard\GuardInterface;
use Zend\Mvc\Controller\AbstractActionController as DefaultAbstractActionController;
use Zend\Permissions\Acl\Resource\ResourceInterface;

/**
 * Class AbstractActionController
 * @package MSBios\CPanel\Mvc\Controller
 */
abstract class AbstractActionController extends DefaultAbstractActionController implements
    ActionControllerInterface,
    GuardInterface,
    ResourceInterface
{
    /**
     * @return string
     */
    public function getResourceId()
    {
        return get_called_class();
    }

    /**
     *
     */
    public function indexAction()
    {
        // Show list
    }

    public function addAction()
    {
        // Show create form
    }

    public function editAction()
    {
        // Show edit form
    }

    public function dropAction()
    {
        // Drop record
    }
}