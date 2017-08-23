<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\CPanel\Widget;


use MSBios\Widget\AbstractRendererWidget;

/**
 * Class AreYouSureDropWidget
 * @package MSBios\CPanel\Widget
 */
class AreYouSureDropWidget extends AbstractRendererWidget
{
    /**
     * @param null $data
     * @return string
     */
    public function output($data = null)
    {
        return $this->render('are-you-sure-drop.phtml', $data);
    }
}