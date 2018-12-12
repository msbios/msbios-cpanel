<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\CPanel\Form;

use Zend\Form\Element\Search;
use Zend\Form\Form;

/**
 * Class SearchForm
 * @package MSBios\CPanel\Form
 */
class SearchForm extends Form
{
    /**
     * SearchForm constructor.
     *
     * @param null $name
     * @param array $options
     */
    public function __construct($name = null, array $options = [])
    {
        parent::__construct($name, $options);
        $this->init();
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->add([
            'type' => Search::class,
            'name' => 'q'
        ]);
    }
}