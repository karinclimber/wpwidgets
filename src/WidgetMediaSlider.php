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

    const AUTO_SCALE = 'sliderAutoScale';
    /** @const Automatically updates slider height based on base width. */
    const AUTO_SCALE_SLIDER = 'sliderAutoScaleSlider';
    /** @const Automatically updates slider height based on base width and Image height. */
    const AUTO_SCALE_HEIGHT = 'sliderAutoScaleHeight';

    const ARROWS_OPTIONS = 'sliderArrowsOptions';
    /** @const Show direction arrows navigation. */
    const NAV_ARROWS_SHOW = 'sliderNavArrowsShow';
    /** @const Auto hide showed arrows navigation. */
    const NAV_ARROWS_AUTO_HIDE = 'sliderNavArrowsAutoHide';
    /** @const Hides arrows on touch devices. */
    const NAV_ARROWS_HIDE_ON_TOUCH = 'sliderNavArrowsHideOnTouch';

    const NAVIGATE_OPTIONS = 'sliderNavigateOptions';
    /** @const Navigates forward by clicking on slide. */
    const NAVIGATE_BY_CLICK = 'sliderNavigateByClick';
    /** @const Mouse drag navigation over slider. */
    const NAVIGATE_BY_DRAG = 'sliderNavigateByDrag';
    /** @const Touch navigation of slider. */
    const NAVIGATE_BY_TOUCH = 'sliderNavigateByTouch';
    /** @const Navigate slider with keyboard left and right arrows. */
    const NAV_WITH_KEYBOARD = 'sliderNavWithKeyboard';

    const SLIDE_OPTIONS = 'sliderSlideOptions';
    /** @const Makes slider to go from last slide to first. */
    const LOOP = 'sliderLoop';
    /** @const If set to true adds arrows and FullScreen button inside rsOverflow container,
     * otherwise inside root slider container. */
    const CONTROLS_INSIDE = 'sliderControlsInside';
    /** @const Aligns image to center of slide. Can be function with one argument - slide object that is being resized. */
    const IMAGE_ALIGN_CENTER = 'sliderImageAlignCenter';
    /** @const Randomizes all slides at start. */
    const RANDOMIZE_SLIDES = 'sliderRandomizeSlides';
    /** @const Enables spinning pre-loader, you may style it via CSS (class rsPreloader). */
    const USE_PRELOADER = 'sliderUsePreloader';
    /** @const Adds global caption element to slider. Grab an image caption from alt or element with (class rsCaption) */
    const GLOBAL_CAPTION = 'sliderGlobalCaption';

    /*imageAlignCenter: true,
    randomizeSlides: false,
    usePreloader: true,
    globalCaption: false,*/

    /** @const Fades in slide after it's loaded. */
    const FADEIN_LOADED = 'sliderFadeIdLoaded';
    /** @const Base slider width. Slider will autocalculate the ratio based on these values. */
    const AUTO_SCALE_VALUE_WIDTH = 'sliderAutoScaleWidthValue';
    /** @const Base slider height */
    const AUTO_SCALE_VALUE_HEIGHT = 'sliderAutoScaleHeightValue';
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

    function __construct()
    {
        parent::__construct(__('Media Slider', 'wptheme'), __('Add images from Media Library'));
    }

    function enqueueScriptsTheme()
    {
        $uriToDirLibs = WPUtils::getUriToLibsDir(__FILE__);
        // Royal Slider
        wp_enqueue_style('royalslider', "{$uriToDirLibs}/royalslider/royalslider.css");
        wp_enqueue_style('royalslider-skin', "{$uriToDirLibs}/royalslider/rs-minimal-white.css");
        wp_enqueue_script('royalslider', "{$uriToDirLibs}/royalslider/jquery.royalslider.min.js", ['jquery'],
            null, true);
    }

    function enqueueScriptsAdmin()
    {
        wp_enqueue_script('media-upload');
        wp_enqueue_media();
    }

    function initFields()
    {
        $this->addField(new WidgetField(WidgetField::NUMBER, self::AUTO_SCALE_VALUE_WIDTH,
            __("Auto scale width"), [], 1170));
        $this->addField(new WidgetField(WidgetField::NUMBER, self::AUTO_SCALE_VALUE_HEIGHT,
            __("Auto scale height"), [], 450));
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
            __("Show slides with:"), [
                self::LOOP => __("Cycle"),
                self::RANDOMIZE_SLIDES => __("Random order"),
                self::GLOBAL_CAPTION => __("Caption"),
                self::USE_PRELOADER => __("Preloader"),
                self::FADEIN_LOADED => __("Fade in"),
                self::CONTROLS_INSIDE => __("Controls inside"),
                self::IMAGE_ALIGN_CENTER => __("Image aligned to center"),

            ], [self::USE_PRELOADER, self::FADEIN_LOADED, self::CONTROLS_INSIDE, self::IMAGE_ALIGN_CENTER]));
        $this->addField(new WidgetField(WidgetField::CHECKBOX_MULTIPLE, self::AUTO_SCALE,
            __("Change height based on:"), [
                self::AUTO_SCALE_SLIDER => __("Auto scale width and height"),
                self::AUTO_SCALE_HEIGHT => __("Image width and height")
            ], [self::AUTO_SCALE_SLIDER]));
        $this->addField(new WidgetField(WidgetField::CHECKBOX_MULTIPLE, self::NAVIGATE_OPTIONS,
            __("Change slide with:"), [
                self::NAVIGATE_BY_CLICK => __("Click"),
                self::NAVIGATE_BY_DRAG => __("Drag"),
                self::NAVIGATE_BY_TOUCH => __("Touch"),
                self::NAV_WITH_KEYBOARD => __("Keyboard left and right arrow"),
            ], [
                self::NAVIGATE_BY_CLICK,
                self::NAVIGATE_BY_DRAG,
                self::NAVIGATE_BY_TOUCH
            ]));
        $this->addField(new WidgetField(WidgetField::CHECKBOX_MULTIPLE, self::ARROWS_OPTIONS,
            __("Arrows for slide change:"), [
                self::NAV_ARROWS_SHOW => __("Show"),
                self::NAV_ARROWS_AUTO_HIDE => __("Auto-hide"),
                self::NAV_ARROWS_HIDE_ON_TOUCH => __("Hide on Touch"),
            ], [
                self::NAV_ARROWS_SHOW,
                self::NAV_ARROWS_AUTO_HIDE
            ]));
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
        $content = "";
        $galleryValue = self::getInstanceValue($instance, self::IMAGES, $this);
        if (isset($galleryValue)) {
            $attachmentIds = (array)$galleryValue;
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
            $galleryId = uniqid("widgetGallery");
            $imageScaleMode = self::getInstanceValue($instance, self::IMAGE_SCALE, $this);
            $controlNavigation = self::getInstanceValue($instance, self::NAVIGATION, $this);
            $slidesOrientation = self::getInstanceValue($instance, self::ORIENTATION, $this);
            $transitionType = self::getInstanceValue($instance, self::TRANSITION, $this);
            //AutoScale
            $autoScaleOptions = self::getInstanceValue($instance, self::AUTO_SCALE, $this);
            $autoScaleSlider = isset($autoScaleOptions[self::AUTO_SCALE_SLIDER]) ? 'true' : 'false';
            $autoScaleHeight = isset($autoScaleOptions[self::AUTO_SCALE_HEIGHT]) ? 'true' : 'false';
            //Arrows
            $arrowsOptions = self::getInstanceValue($instance, self::ARROWS_OPTIONS, $this);
            $arrowsNav = isset($arrowsOptions[self::NAV_ARROWS_SHOW]) ? 'true' : 'false';
            $arrowsNavAutoHide = isset($arrowsOptions[self::NAV_ARROWS_AUTO_HIDE]) ? 'true' : 'false';
            $arrowsNavHideOnTouch = isset($arrowsOptions[self::NAV_ARROWS_HIDE_ON_TOUCH]) ? 'true' : 'false';
            //Navigation
            $navigateOptions = self::getInstanceValue($instance, self::NAVIGATE_OPTIONS, $this);
            $navigateByClick = isset($navigateOptions[self::NAVIGATE_BY_CLICK]) ? 'true' : 'false';
            $keyboardNavEnabled = isset($navigateOptions[self::NAV_WITH_KEYBOARD]) ? 'true' : 'false';
            $sliderDrag = isset($navigateOptions[self::NAVIGATE_BY_DRAG]) ? 'true' : 'false';
            $sliderTouch = isset($navigateOptions[self::NAVIGATE_BY_TOUCH]) ? 'true' : 'false';
            //Options
            $slideOptions = self::getInstanceValue($instance, self::SLIDE_OPTIONS, $this);
            $sliderLoop = isset($slideOptions[self::LOOP]) ? 'true' : 'false';
            $randomizeSlides = isset($slideOptions[self::RANDOMIZE_SLIDES]) ? 'true' : 'false';
            $globalCaption = isset($slideOptions[self::GLOBAL_CAPTION]) ? 'true' : 'false';
            $usePreloader = isset($slideOptions[self::USE_PRELOADER]) ? 'true' : 'false';
            $fadeinLoadedSlide = isset($slideOptions[self::FADEIN_LOADED]) ? 'true' : 'false';
            $controlsInside = isset($slideOptions[self::CONTROLS_INSIDE]) ? 'true' : 'false';
            $imageAlignCenter = isset($slideOptions[self::IMAGE_ALIGN_CENTER]) ? 'true' : 'false';

            $autoScaleSliderWidth = self::getInstanceValue($instance, self::AUTO_SCALE_VALUE_WIDTH, $this);
            $autoScaleSliderHeight = self::getInstanceValue($instance, self::AUTO_SCALE_VALUE_HEIGHT, $this);
            $startSlideId = self::getInstanceValue($instance, self::START_SLIDE_ID, $this);
            $numImagesToPreload = self::getInstanceValue($instance, self::IMAGES_TO_PRELOAD, $this);
            $slidesSpacing = self::getInstanceValue($instance, self::SLIDES_SPACING, $this);
            $minSlideOffset = self::getInstanceValue($instance, self::MIN_SLIDES_OFFSET, $this);
            $transitionSpeed = self::getInstanceValue($instance, self::TRANSITION_SPEED, $this);
            $imageScalePadding = self::getInstanceValue($instance, self::IMAGE_SCALE_PADDING, $this);

            $content = "<div id='{$galleryId}' class='royalSlider rsMinW'>{$content}</div>
            <script type='text/javascript'>(function ($) {
            $(document).ready(function () {
            if ($().royalSlider) {
                $('#{$galleryId}').royalSlider({                   
                    imageScaleMode: '$imageScaleMode',
                    controlNavigation: '$controlNavigation',
                    slidesOrientation: '$slidesOrientation',
                    transitionType: '$transitionType',
                    
                    autoScaleSlider: $autoScaleSlider,
                    autoScaleHeight: $autoScaleHeight,
                    autoHeight: false,
                    
                    arrowsNav: $arrowsNav,
                    arrowsNavAutoHide: $arrowsNavAutoHide,
                    arrowsNavHideOnTouch: $arrowsNavHideOnTouch,
                    
                    navigateByClick: $navigateByClick,
                    sliderTouch: $sliderTouch,
                    sliderDrag: $sliderDrag,
                    keyboardNavEnabled: $keyboardNavEnabled,
                    
                    controlsInside: $controlsInside,
                    loop: $sliderLoop,
                    loopRewind: $sliderLoop,                    
                    fadeinLoadedSlide: $fadeinLoadedSlide,
                    fadeInAfterLoaded: $fadeinLoadedSlide,
                    
                    imageAlignCenter: $imageAlignCenter,
                    randomizeSlides: $randomizeSlides,
                    usePreloader: $usePreloader,
                    globalCaption: $globalCaption,
                    
                    autoScaleSliderWidth: '$autoScaleSliderWidth',
                    autoScaleSliderHeight: '$autoScaleSliderHeight',
                    startSlideId: '$startSlideId',
                    numImagesToPreload: '$numImagesToPreload',
                    slidesSpacing: '$slidesSpacing',
                    minSlideOffset: '$minSlideOffset',
                    transitionSpeed: '$transitionSpeed',
                    imageScalePadding: '$imageScalePadding',
                    slidesDiff: 2
                });
            }});
            })(jQuery);</script>";
        }

        $args[WPSidebar::CONTENT] = $content;
        parent::widget($args, $instance);
    }
}