<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\CPanel\Config;

/**
 * Interface ConfigControllerInterface
 * @package MSBios\CPanel\Config
 */
interface ControllerInterface
{
    /**
     * @return mixed
     */
    public function getResourceClass();

    /**
     * @return mixed
     */
    public function getRouteName();

    /**
     * @return mixed
     */
    public function getFormElement();

    /**
     * @return mixed
     */
    public function getItemCountPerPage();
}
