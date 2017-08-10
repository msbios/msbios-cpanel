<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\CPanel\Factory;

use MSBios\CPanel\Navigation\Sidebar;
use Zend\Navigation\Service\AbstractNavigationFactory;

/**
 * Class NavigationFactory
 * @package MSBios\CPanel\Factory
 */
class NavigationFactory extends AbstractNavigationFactory
{
    /**
     * @return mixed
     */
    protected function getName()
    {
        return Sidebar::class;
    }
}
