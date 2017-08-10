<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\CPanel\Config;

use Zend\Config\Config as DefaultConfig;

/**
 * Class Config
 * @package MSBios\CPanel\Config
 */
class Config extends DefaultConfig
{
    /** @const CONTROLLER */
    const CONTROLLER = 'controller';

    /**
     * @return mixed
     */
    public function getController()
    {
        return $this->get(self::CONTROLLER);
    }
}
