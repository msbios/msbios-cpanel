<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\CPanel\Initializer;

use Zend\Form\FormInterface;

/**
 * Trait LazyControllerAwareTrait
 * @package MSBios\CPanel\Initializer
 */
trait LazyControllerAwareTrait
{
    /** @var Object */
    protected $objectPrototype;

    /** @var FormInterface */
    protected $form;

    /**
     * @return Object
     */
    public function getObjectPrototype()
    {
        return $this->objectPrototype;
    }

    /**
     * @param $objectPrototype
     * @return $this
     */
    public function setObjectPrototype($objectPrototype)
    {
        $this->objectPrototype = $objectPrototype;
        return $this;
    }

    /**
     * @return FormInterface
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @param FormInterface $form
     * @return $this
     */
    public function setForm(FormInterface $form)
    {
        $this->form = $form;
        return $this;
    }
}