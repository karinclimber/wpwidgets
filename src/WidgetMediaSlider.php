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
    /** @const Base slider width. Slider will auto-calculate the ratio based on these values. */
    const WIDTH = 'sliderAutoScaleWidthValue';
    /** @const Base slider height */
    const HEIGHT = 'sliderAutoScaleHeightValue';
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
        wp_register_style('rslider', "{$this->uriToDirLibs}/rslider/rslider.css");
        wp_register_style('rslider-caption', "{$this->uriToDirLibs}/rslider/rslider-caption.css");
        wp_register_style('rslider-skin', "{$this->uriToDirLibs}/rslider/rs-minimal.css");
        wp_register_script('rslider', "{$this->uriToDirLibs}/rslider/rslider.js", ['jquery'], null, true);
        wp_register_script("rsliderinit{$this->id}", "{$this->uriToDirLibs}/rslider/rsliderinit.js", ['rslider'], null, true);
    }

    function enqueueScriptsAdmin()
    {
        wp_enqueue_script('media-upload');
        wp_enqueue_media();
    }

    function initFields()
    {
        $this->addField(new WidgetField(WidgetField::TEXT, self::WIDTH,
            __("Width"), [], '100%'));
        $this->addField(new WidgetField(WidgetField::TEXT, self::HEIGHT,
            __("Height"), [], '400px'));
        $this->addField(new WidgetField(WidgetField::IMAGES_WITH_URL, self::IMAGES, __("Images")));
        $this->addField(new WidgetField(WidgetField::SELECT, self::IMAGE_SCALE, __('Image Scale'), [
            self::IMAGE_SCALE_FIT_IF_SMALLER => __('Fit if Smaller'),
            self::IMAGE_SCALE_FIT => __('Fit'),
            self::IMAGE_SCALE_FILL => __('Fill'),
            self::IMAGE_SCALE_NONE => __('None')
        ], self::IMAGE_SCALE_FIT_IF_SMALLER));
        $this->addField(new WidgetField(WidgetField::SELECT, self::NAVIGATION, __('Navigation'), [
            self::NAVIGATION_BULLETS => __('Bullets'),
            self::NAVIGATION_THUMBNAILS => __('Thumbnails'),
            self::NAVIGATION_TABS => __('Tabs'),
            self::NAVIGATION_NONE => __('None')
        ], self::NAVIGATION_BULLETS));
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
                self::NAV_WITH_KEYBOARD => __("Keyboard left and right arrow"),
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
            __("Image padding"), [], 4));
        $this->addField(new WidgetField(WidgetField::NUMBER, self::START_SLIDE_ID,
            __("First slide index"), [], 0));
        parent::initFields();
    }

    function widget($args, $instance)
    {
        wp_enqueue_style('rslider');
        wp_enqueue_style('rslider-caption');
        wp_enqueue_style('rslider-skin');
        wp_enqueue_script('rslider');
        $content = "";
        $images = self::getInstanceValue($instance, self::IMAGES, $this);
        if (isset($images)) {
            $attachmentIds = (array)$images;
            if (is_array($attachmentIds)) {
                //TODO Export slide change delay to configuration for slide switching and for switching effect
                foreach ($attachmentIds as $attachmentId => $attachmentLink) {
                    $attachmentUrl = wp_get_attachment_image_url($attachmentId, WPImages::FULL);
                    $content .= "<div class='rsContent'><a class='rsImg' href='$attachmentUrl' data-href='$attachmentLink'></a>";
                    if ($attachmentLink) {
                        $content .= "<a class='rsLink' href='$attachmentLink'></a>";
                    }
                    $content .= "</div>";
                }
            }
            //Arrows
            $arrowsOptions = self::getInstanceValue($instance, self::ARROWS_OPTIONS, $this);
            //Navigation
            $navigateOptions = self::getInstanceValue($instance, self::NAVIGATE_OPTIONS, $this);
            //Options
            $slideOptions = self::getInstanceValue($instance, self::SLIDE_OPTIONS, $this);
            //Values
            $sliderWidth = self::getInstanceValue($instance, self::WIDTH, $this);
            $sliderHeight = self::getInstanceValue($instance, self::HEIGHT, $this);
            //Content
            $sliderOptions = [
                'imageScaleMode' => self::getInstanceValue($instance, self::IMAGE_SCALE, $this),
                'controlNavigation' => self::getInstanceValue($instance, self::NAVIGATION, $this),
                'slidesOrientation' => self::getInstanceValue($instance, self::ORIENTATION, $this),
                'transitionType' => self::getInstanceValue($instance, self::TRANSITION, $this),

                'arrowsNav' => in_array(self::NAV_ARROWS_SHOW, $arrowsOptions),
                'arrowsNavAutoHide' => in_array(self::NAV_ARROWS_AUTO_HIDE, $arrowsOptions),
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
            $content = "<style type='text/css'>#{$this->id} > .royalSlider{width:$sliderWidth;height:$sliderHeight;}</style>
            <div class='royalSlider rsMinW'>{$content}</div>
            <script defer>
                jQuery('#{$this->id}.royalSlider').royalSlider({${json_encode($sliderOptions)}});
            </script>";
            /*$scriptName = "rsliderinit{$this->id}";
            wp_localize_script($scriptName, 'slider', ['id' => "#{$this->id}.royalSlider", 'options' => $slideOptions]);
            wp_enqueue_script($scriptName);*/
        }
        $args[WPSidebar::CONTENT] = $content;
        parent::widget($args, $instance);
    }
}