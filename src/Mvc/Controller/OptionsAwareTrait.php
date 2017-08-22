<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\CPanel\Mvc\Controller;

use Zend\Config\Config;

/**
 * Trait OptionsAwareTrait
 * @package MSBios\CPanel\Mvc\Controller
 */
trait OptionsAwareTrait
{
    /** @var Config */
    protected $options;

    /**
     * @return Config
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param Config $options
     * @return $this
     */
    public function setOptions(Config $options)
    {
        $this->options = $options;
        return $this;
    }
}
