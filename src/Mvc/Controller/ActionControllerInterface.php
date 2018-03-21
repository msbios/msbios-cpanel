<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\CPanel\Mvc\Controller;

use MSBios\Guard\GuardInterface;
use Zend\Permissions\Acl\Resource\ResourceInterface;

/**
 * Interface ActionControllerInterface
 * @package MSBios\CPanel\Mvc\Controller
 */
interface ActionControllerInterface extends GuardInterface, ResourceInterface
{
    // ...
}
