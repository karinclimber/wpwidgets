<?php
/**
 * Author: Vitali Lupu <vitaliix@gmail.com>
 * Date: 3/5/18
 * Time: 6:32 PM
 */

namespace wp;
final class WidgetMediaSlider extends Widget
{
    const IMAGES = 'sliderImages';
    //Slider Size
    /** @const Automatically updates slider height based on base width. */
    const AUTO_SCALE = 'sliderAutoScale';
    /** @const  Base slider Width. Slider will auto calculate the ratio based on these value. */
    const AUTO_SCALE_WIDTH = 'sliderAutoScaleWidth';
    /** @const  Base slider Height. Slider will auto calculate the ratio based on these value. */
    const AUTO_SCALE_HEIGHT = 'sliderAutoScaleHeight';
    //Arrows
    const ARROWS_OPTIONS = 'sliderArrowsOptions';
    /** @const Show direction arrows navigation. */
    const NAV_ARROWS_SHOW = 'sliderNavArrowsShow';
    /** @const Auto hide showed arrows navigation. */
    const NAV_ARROWS_AUTO_HIDE = 'sliderNavArrowsAutoHide';
    /** @const Hides arrows on touch devices. */
    const NAV_ARROWS_HIDE_ON_TOUCH = 'sliderNavArrowsHideOnTouch';
    //Navigation
    const NAVIGATE_OPTIONS = 'sliderNavigateOptions';
    /** @const Navigates forward by clicking on slide. */
    const NAVIGATE_BY_CLICK = 'sliderNavigateByClick';
    /** @const Mouse drag navigation over slider. */
    const NAVIGATE_BY_DRAG = 'sliderNavigateByDrag';
    /** @const Touch navigation of slider. */
    const NAVIGATE_BY_TOUCH = 'sliderNavigateByTouch';
    /** @const Navigate slider with keyboard left and right arrows. */
    const NAV_WITH_KEYBOARD = 'sliderNavWithKeyboard';
    //Slide
    const SLIDE_OPTIONS = 'sliderSlideOptions';
    /** @const Makes slider to go from last slide to first. */
    const LOOP = 'sliderLoop';
    /** @const Randomizes all slides at start. */
    const RANDOMIZE_SLIDES = 'sliderRandomizeSlides';
    /** @const Enables spinning pre-loader, you may style it via CSS (class rsPreloader). */
    const USE_PRELOADER = 'sliderUsePreloader';
    /** @const Adds global caption element to slider. Grab an image caption from alt or element with (class rsCaption) */
    const GLOBAL_CAPTION = 'sliderGlobalCaption';
    //Values
    /** @const Start slide index */
    const START_SLIDE_ID = 'sliderStartSlideId';
    /** @const Number of slides to preload on sides.
     * If you set it to 0, only one slide will be kept in the display list at once.*/
    const IMAGES_TO_PRELOAD = 'sliderImagesToPreload';
    /** @const Spacing between slides in pixels. */
    const SLIDES_SPACING = 'sliderSlidesSpacing';
    /** @const Minimum distance in pixels to show next slide while dragging. */
    const MIN_SLIDES_OFFSET = 'sliderMinSlidesOffset';
    /** @const Slider transition speed, in ms. */
    const TRANSITION_SPEED = 'sliderTransitionSpeed';
    /** @const Distance between image and edge of slide (doesn't work with 'fill' scale mode). */
    const IMAGE_SCALE_PADDING = 'sliderImageScalePadding';

    const ORIENTATION = 'sliderOrientation';
    const ORIENTATION_HORIZONTAL = 'horizontal';
    const ORIENTATION_VERTICAL = 'vertical';

    const TRANSITION = 'sliderTransition';
    const TRANSITION_MOVE = 'move';
    const TRANSITION_FADE = 'fade';

    const NAVIGATION = 'sliderNavigation';
    const NAVIGATION_BULLETS = 'bullets';
    const NAVIGATION_THUMBNAILS = 'thumbnails';
    const NAVIGATION_TABS = 'tabs';
    const NAVIGATION_NONE = 'none';

    const SKIN = 'sliderSkin';
    const SKIN_DEFAULT = 'rsDefault';
    const SKIN_MINIMAL = 'rsMinimal';
    const SKIN_INVERTED = 'rsInverted';
    const SKIN_UNIVERSAL = 'rsUniversal';

    const IMAGE_SCALE = 'sliderImageScale';
    const IMAGE_SCALE_FIT_IF_SMALLER = 'fit-if-smaller';
    const IMAGE_SCALE_FIT = 'fit';
    const IMAGE_SCALE_FILL = 'fill';
    const IMAGE_SCALE_NONE = 'none';
    private $uriToDirLibs = '';

    function __construct()
    {
        parent::__construct(__('Media Slider', 'wptheme'), __('Add images from Media Library'));
    }

    function enqueueScriptsTheme()
    {
        $this->uriToDirLibs = WPUtils::getUriToLibsDir(__FILE__);
        $enqueueStyle = 'wp_register_style';
        $enqueueScript = 'wp_register_script';
        if (is_customize_preview()) {
            $enqueueStyle = 'wp_enqueue_style';
            $enqueueScript = 'wp_enqueue_script';
        }
        $enqueueStyle(self::SKIN_DEFAULT, "{$this->uriToDirLibs}/rslider/rsSkinDefault.css", ['rs']);
        $enqueueStyle(self::SKIN_MINIMAL, "{$this->uriToDirLibs}/rslider/rsSkinMinimal.css", ['rs']);
        $enqueueStyle(self::SKIN_INVERTED, "{$this->uriToDirLibs}/rslider/rsSkinInverted.css", ['rs']);
        $enqueueStyle(self::SKIN_UNIVERSAL, "{$this->uriToDirLibs}/rslider/rsSkinUniversal.css", ['rs']);
        wp_enqueue_style('rs', "{$this->uriToDirLibs}/rslider/rs.css");
        wp_enqueue_script('rs', "{$this->uriToDirLibs}/rslider/rs.js", ['jquery'], null, true);
        $enqueueScript('rsautohidenav', "{$this->uriToDirLibs}/rslider/rsAutoHideNav.js", ['rs'], null, true);
        $enqueueScript('rsbullets', "{$this->uriToDirLibs}/rslider/rsBullets.js", ['rs'], null, true);
        $enqueueScript('rsthumbnails', "{$this->uriToDirLibs}/rslider/rsThumbnails.js", ['rs'], null, true);
        $enqueueScript('rstabs', "{$this->uriToDirLibs}/rslider/rsTabs.js", ['rs'], null, true);
    }

    function enqueueScriptsAdmin()
    {
        wp_enqueue_script('media-upload');
        wp_enqueue_media();
    }

    function initFields()
    {
        $this->addField(new WidgetField(WidgetField::CHECKBOX, self::AUTO_SCALE,
            __("Auto Scale using Width and Height"), [], false));
        $this->addField(new WidgetField(WidgetField::NUMBER, self::AUTO_SCALE_WIDTH,
            __("Width")));
        $this->addField(new WidgetField(WidgetField::NUMBER, self::AUTO_SCALE_HEIGHT,
            __("Height")));
        $this->addField(new WidgetField(WidgetField::SELECT, self::SKIN, __('Skin'), [
            self::SKIN_DEFAULT => __('Default'),
            self::SKIN_MINIMAL => __('Minimal'),
            self::SKIN_INVERTED => __('Inverted'),
            self::SKIN_UNIVERSAL => __('Universal')
        ], self::SKIN_DEFAULT));
        $this->addField(new WidgetField(WidgetField::IMAGES_WITH_URL, self::IMAGES, __("Images")));
        $this->addField(new WidgetField(WidgetField::SELECT, self::IMAGE_SCALE, __('Image Scale'), [
            self::IMAGE_SCALE_FIT => __('Fit'),
            self::IMAGE_SCALE_FIT_IF_SMALLER => __('Fit if Smaller'),
            self::IMAGE_SCALE_FILL => __('Fill'),
            self::IMAGE_SCALE_NONE => __('None')
        ], self::IMAGE_SCALE_FIT_IF_SMALLER));
        $this->addField(new WidgetField(WidgetField::SELECT, self::NAVIGATION, __('Navigation'), [
            self::NAVIGATION_NONE => __('None'),
            self::NAVIGATION_BULLETS => __('Bullets'),
            self::NAVIGATION_THUMBNAILS => __('Thumbnails'),
            self::NAVIGATION_TABS => __('Tabs')
        ], self::NAVIGATION_NONE));
        $this->addField(new WidgetField(WidgetField::RADIO, self::ORIENTATION, __('Orientation'), [
            self::ORIENTATION_HORIZONTAL => __('Horizontal'),
            self::ORIENTATION_VERTICAL => __('Vertical')
        ], self::ORIENTATION_HORIZONTAL));
        $this->addField(new WidgetField(WidgetField::RADIO, self::TRANSITION, __('Transition'), [
            self::TRANSITION_MOVE => __('Move'),
            self::TRANSITION_FADE => __('Fade')
        ], self::TRANSITION_MOVE));
        $this->addField(new WidgetField(WidgetField::CHECKBOX_MULTIPLE, self::SLIDE_OPTIONS,
            __("Show slides:"), [
                self::LOOP => __("In Cycle"),
                self::RANDOMIZE_SLIDES => __("In Random order"),
                self::GLOBAL_CAPTION => __("With Caption"),
                self::USE_PRELOADER => __("With Preloader")

            ], [self::USE_PRELOADER]));
        $this->addField(new WidgetField(WidgetField::CHECKBOX_MULTIPLE, self::NAVIGATE_OPTIONS,
            __("Change slide with:"), [
                self::NAVIGATE_BY_CLICK => __("Click"),
                self::NAVIGATE_BY_DRAG => __("Drag"),
                self::NAVIGATE_BY_TOUCH => __("Touch"),
                self::NAV_WITH_KEYBOARD => __("Keyboard Arrows"),
            ], [self::NAVIGATE_BY_CLICK, self::NAVIGATE_BY_DRAG, self::NAVIGATE_BY_TOUCH]));
        $this->addField(new WidgetField(WidgetField::CHECKBOX_MULTIPLE, self::ARROWS_OPTIONS,
            __("Arrows for slide change:"), [
                self::NAV_ARROWS_SHOW => __("Show"),
                self::NAV_ARROWS_AUTO_HIDE => __("Auto-hide"),
                self::NAV_ARROWS_HIDE_ON_TOUCH => __("Hide on Touch"),
            ], [self::NAV_ARROWS_SHOW, self::NAV_ARROWS_AUTO_HIDE]));
        //NAVIGATE_OPTIONS
        $this->addField(new WidgetField(WidgetField::NUMBER, self::IMAGES_TO_PRELOAD,
            __("Images to preload"), [], 4));
        $this->addField(new WidgetField(WidgetField::NUMBER, self::SLIDES_SPACING,
            __("Spacing between slides"), [], 8));
        $this->addField(new WidgetField(WidgetField::NUMBER, self::MIN_SLIDES_OFFSET,
            __("Drag offset"), [], 10));
        $this->addField(new WidgetField(WidgetField::NUMBER, self::TRANSITION_SPEED,
            __("Transition speed"), [], 600));
        $this->addField(new WidgetField(WidgetField::NUMBER, self::IMAGE_SCALE_PADDING,
            __("Image padding"), [], 0));
        $this->addField(new WidgetField(WidgetField::NUMBER, self::START_SLIDE_ID,
            __("First slide index"), [], 0));
        parent::initFields();
    }

    function widget($args, $instance)
    {
        $content = "";
        $images = self::getInstanceValue($instance, self::IMAGES, $this);
        if (isset($images) && is_array($attachmentIds = (array)$images)) {
            $controlNavigation = self::getInstanceValue($instance, self::NAVIGATION, $this);
            if (!is_customize_preview() && $controlNavigation !== self::NAVIGATION_NONE) {
                wp_enqueue_script('rs' . $controlNavigation);
            }
            $showThumbnails = ($controlNavigation == self::NAVIGATION_TABS || $controlNavigation == self::NAVIGATION_THUMBNAILS);
            $imgWidths = [];
            $imgHeights = [];
            foreach ($attachmentIds as $attachmentId => $attachmentLink) {
                $imgInfo = image_downsize($attachmentId, WPImages::FULL);
                if (isset($imgInfo['0'])) {
                    $imgWidths [] = $imgWidth = $imgInfo['1'];
                    $imgHeights [] = $imgHeight = $imgInfo['2'];
                    $content .= "<a class='rsImg' href='{$imgInfo['0']}' data-rsw='{$imgWidth}' data-rsh='{$imgHeight}' data-href='$attachmentLink'>";
                    if ($showThumbnails) {
                        $imgInfo = image_downsize($attachmentId, WPImages::THUMB);
                        $content .= "<img src={$imgInfo['0']} width='96' height='72' class='rsTmb' />";
                    }
                    $content .= '</a>';
                }
            }
            //Skin
            $skin = self::getInstanceValue($instance, self::SKIN, $this);
            if (!is_customize_preview()) {
                wp_enqueue_style($skin);
            }
            //Arrows
            $arrowsOptions = self::getInstanceValue($instance, self::ARROWS_OPTIONS, $this);
            $arrowsNavAutoHide = in_array(self::NAV_ARROWS_AUTO_HIDE, $arrowsOptions);
            if (!is_customize_preview() && $arrowsNavAutoHide) {
                wp_enqueue_script('rsAutoHideNav');
            }
            //Navigation
            $navigateOptions = self::getInstanceValue($instance, self::NAVIGATE_OPTIONS, $this);
            //Options
            $slideOptions = self::getInstanceValue($instance, self::SLIDE_OPTIONS, $this);
            //Values
            $sliderHeight = self::getInstanceValue($instance, self::AUTO_SCALE_HEIGHT, $this);
            //Content
            $sliderOptions = [
                'autoScaleSlider' => self::getInstanceValue($instance, self::AUTO_SCALE, $this),
                'imageScaleMode' => self::getInstanceValue($instance, self::IMAGE_SCALE, $this),
                'controlNavigation' => $controlNavigation,
                'slidesOrientation' => self::getInstanceValue($instance, self::ORIENTATION, $this),
                'transitionType' => self::getInstanceValue($instance, self::TRANSITION, $this),

                'arrowsNav' => in_array(self::NAV_ARROWS_SHOW, $arrowsOptions),
                'arrowsNavAutoHide' => $arrowsNavAutoHide,
                'arrowsNavHideOnTouch' => in_array(self::NAV_ARROWS_HIDE_ON_TOUCH, $arrowsOptions),

                'navigateByClick' => in_array(self::NAVIGATE_BY_CLICK, $navigateOptions),
                'sliderTouch' => in_array(self::NAVIGATE_BY_TOUCH, $navigateOptions),
                'sliderDrag' => in_array(self::NAVIGATE_BY_DRAG, $navigateOptions),
                'keyboardNavEnabled' => in_array(self::NAV_WITH_KEYBOARD, $navigateOptions),


                'loop' => in_array(self::LOOP, $slideOptions),
                'loopRewind' => in_array(self::LOOP, $slideOptions),

                'randomizeSlides' => in_array(self::RANDOMIZE_SLIDES, $slideOptions),
                'usePreloader' => in_array(self::USE_PRELOADER, $slideOptions),
                'globalCaption' => in_array(self::GLOBAL_CAPTION, $slideOptions),

                'startSlideId' => (int)self::getInstanceValue($instance, self::START_SLIDE_ID, $this),
                'numImagesToPreload' => (int)self::getInstanceValue($instance, self::IMAGES_TO_PRELOAD, $this),
                'slidesSpacing' => (int)self::getInstanceValue($instance, self::SLIDES_SPACING, $this),
                'minSlideOffset' => (int)self::getInstanceValue($instance, self::MIN_SLIDES_OFFSET, $this),
                'transitionSpeed' => (int)self::getInstanceValue($instance, self::TRANSITION_SPEED, $this),
                'imageScalePadding' => (int)self::getInstanceValue($instance, self::IMAGE_SCALE_PADDING, $this)
            ];
            //Size
            $autoScaleSliderWidth = (int)self::getInstanceValue($instance, self::AUTO_SCALE_WIDTH, $this);
            if (!$autoScaleSliderWidth && count($imgWidths)) {
                $sliderOptions['autoScaleSliderWidth'] = max($imgWidths);
            }
            $autoScaleSliderHeight = (int)self::getInstanceValue($instance, self::AUTO_SCALE_HEIGHT, $this);
            if (!$autoScaleSliderHeight && count($imgHeights)) {
                $sliderOptions['autoScaleSliderHeight'] = max($imgHeights);
            }
            $sliderOptionsEncoded = json_encode($sliderOptions);
            $optionsName = $this->id_base . $this->number;
            $sliderId = "#{$this->id} > .rs";
            $content = "<div class='rs $skin' style='height:$sliderHeight;'>{$content}</div>
            <script>var $optionsName = $sliderOptionsEncoded;
            if (typeof jQuery === 'undefined'){
                window.addEventListener('DOMContentLoaded', function() { jQuery('$sliderId').rs($optionsName);});
            } else {
                if (jQuery.rs){
                    jQuery('$sliderId').rs($optionsName);
                } else {
                    jQuery(document).ready(function () { jQuery('$sliderId').rs($optionsName); });   
                }
            }</script>";
        }
        $args[WPSidebar::CONTENT] = $content;
        parent::widget($args, $instance);
    }
}