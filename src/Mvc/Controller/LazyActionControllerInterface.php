<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\CPanel\Mvc\Controller;

use Doctrine\ORM\EntityManager;
use MSBios\CPanel\Config\ControllerInterface as ControllerOptions;
use Zend\Form\FormElementManager\FormElementManagerV3Polyfill;

/**
 * Interface LazyActionControllerInterface
 * @package MSBios\CPanel\Mvc\Controller
 */
interface LazyActionControllerInterface extends ActionControllerInterface
{
    /**
     * @param EntityManager $dem
     * @return mixed
     */
    public function setEntityManager(EntityManager $dem);

    /**
     * @param FormElementManagerV3Polyfill $formElementManager
     * @return mixed
     */
    public function setFormElement(FormElementManagerV3Polyfill $formElementManager);

    /**
     * @param ControllerOptions $options
     * @return mixed
     */
    public function setOptions(ControllerOptions $options);
}
