<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\CPanel\Mvc\Controller;

use Zend\View\Model\ViewModel;

/**
 * Interface ActionControllerInterface
 * @package MSBios\CPanel\Mvc\Controller
 */
interface ActionControllerInterface
{
    /**
     * @return ViewModel
     */
    public function indexAction();

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function addAction();

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function editAction();

    /**
     * @return \Zend\Http\Response
     */
    public function dropAction();
}
