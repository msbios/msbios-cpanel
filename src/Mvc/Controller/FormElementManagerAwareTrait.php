<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\CPanel\Mvc\Controller;

use Zend\Form\FormElementManager\FormElementManagerV3Polyfill;

/**
 * Trait FormElementManagerAwareTrait
 * @package MSBios\CPanel\Mvc\Controller
 */
trait FormElementManagerAwareTrait
{
    /** @var FormElementManagerV3Polyfill */
    protected $formElementManager;

    /**
     * @return FormElementManagerV3Polyfill
     */
    public function getFormElementManager()
    {
        return $this->formElementManager;
    }

    /**
     * @param FormElementManagerV3Polyfill $formElementManager
     * @return $this
     */
    public function setFormElementManager(FormElementManagerV3Polyfill $formElementManager)
    {
        $this->formElementManager = $formElementManager;
        return $this;
    }
}