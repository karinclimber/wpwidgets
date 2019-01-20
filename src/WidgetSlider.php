<?php
/**
 * Author: Vitali Lupu <vitaliix@gmail.com>
 * Date: 3/5/18
 * Time: 6:32 PM
 */

namespace wp;
class WidgetSlider extends Widget
{
    const SLIDER_LIST = "widgetSliderList";

    function __construct()
    {
        parent::__construct(__('Media Slider', 'LayerSlider'), __('Insert sliders with the Widget', 'LayerSlider'));
    }

    function initFields()
    {
        $sliders = \LS_Sliders::find(['limit' => 100]);
        $this->addField(new WidgetField(WidgetField::SELECT, self::SLIDER_LIST,
            __('Choose a slider:', 'LayerSlider'), $sliders, 0));
    }

    function widget($args, $instance)
    {
        $content = "";
        $currentSlider = self::getInstanceValue($instance, self::SLIDER_LIST, $this);
        if (is_int($currentSlider)){
            $content = do_shortcode("[layerslider id='{$currentSlider}']");
        }
        $args[WPSidebar::CONTENT] = $content;
        parent::widget($args, $instance);
    }
}