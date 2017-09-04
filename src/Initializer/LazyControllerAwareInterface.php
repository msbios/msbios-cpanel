<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\CPanel\Initializer;

use Zend\Config\Config;
use Zend\Form\FormInterface;

/**
 * Interface LazyControllerAwareInterface
 * @package MSBios\CPanel\Initializer
 */
interface LazyControllerAwareInterface
{
    /**
     * @return Object
     */
    public function getObjectPrototype();

    /**
     * @param $objectPrototype
     * @return $this
     */
    public function setObjectPrototype($objectPrototype);

    /**
     * @return FormInterface
     */
    public function getForm();

    /**
     * @param FormInterface $form
     * @return $this
     */
    public function setForm(FormInterface $form);
}