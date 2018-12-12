<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\CPanel\Form;

use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\Form\Element\Search;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

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

        /** @var InputFilter $inputFilter */
        $inputFilter = new InputFilter;
        $inputFilter->add([
            'name' => 'q',
            'filters' => [
                [
                    'name' => StringTrim::class
                ], [
                    'name' => StripTags::class
                ]
            ]
        ]);

        $this->setInputFilter($inputFilter);

        $this->add([
            'type' => Search::class,
            'name' => 'q'
        ]);
    }
}
