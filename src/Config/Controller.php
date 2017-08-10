<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\CPanel\Config;

/**
 * Class Controller
 * @package MSBios\CPanel\Config
 */
class Controller implements ControllerInterface
{
    /** @var Config */
    protected $config;

    /** @const RESOURCE_CLASS */
    const RESOURCE_CLASS = 'resource_class';

    /** @const ROUTE_NAME */
    const ROUTE_NAME = 'route_name';

    /** @const FORM_ELEMENT */
    const FORM_ELEMENT = 'form_element';

    /** @const ITEM_COUNT_PER_PAGE */
    const ITEM_COUNT_PER_PAGE = 'item_count_per_page';

    /** @const DEFAULT_ITEM_COUNT_PER_PAGE */
    const DEFAULT_ITEM_COUNT_PER_PAGE = 10;

    /**
     * Controller constructor.
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @return mixed
     */
    public function getResourceClass()
    {
        return $this->config->get(self::RESOURCE_CLASS);
    }

    /**
     * @return mixed
     */
    public function getRouteName()
    {
        return $this->config->get(self::ROUTE_NAME);
    }

    /**
     * @return mixed
     */
    public function getFormElement()
    {
        return $this->config->get(self::FORM_ELEMENT);
    }

    /**
     * @return mixed
     */
    public function getItemCountPerPage()
    {
        return $this->config->get(self::ITEM_COUNT_PER_PAGE, self::DEFAULT_ITEM_COUNT_PER_PAGE);
    }
}
