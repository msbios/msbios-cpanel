<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\CPanel\Mvc\Controller;

use Zend\Form\FormElementManager\FormElementManagerV3Polyfill;

/**
 * Interface FormElementManagerAwareInterface
 * @package MSBios\CPanel\Mvc\Controller
 */
interface FormElementManagerAwareInterface
{
    /**
     * @return FormElementManagerV3Polyfill
     */
    public function getFormElementManager();

    /**
     * @param FormElementManagerV3Polyfill $formElementManager
     * @return $this
     */
    public function setFormElementManager(FormElementManagerV3Polyfill $formElementManager);
}
