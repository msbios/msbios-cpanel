<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\CPanel\Mvc\Controller;

use Zend\Config\Config;

/**
 * Interface OptionsAwareInterface
 * @package MSBios\CPanel\Mvc\Controller
 */
interface OptionsAwareInterface
{
    /**
     * @return Config
     */
    public function getOptions();

    /**
     * @param Config $options
     * @return $this
     */
    public function setOptions(Config $options);
}
