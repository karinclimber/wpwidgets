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
    const BOOL_OPTIONS = 'sliderBoolOptions';
    /** @const Show direction arrows navigation. */
    const NAV_ARROWS_SHOW = 'sliderNavArrowsShow';
    /** @const Auto hide showed arrows navigation. */
    const NAV_ARROWS_AUTO_HIDE = 'sliderNavArrowsAutoHide';
    /** @const Hides arrows on touch devices. */
    const NAV_ARROWS_HIDE_ON_TOUCH = 'sliderNavArrowsHideOnTouch';
    /** @const Navigate slider with keyboard left and right arrows. */
    const NAV_WITH_KEYBOARD = 'sliderNavWithKeyboard';
    /** @const Navigates forward by clicking on slide. */
    const NAVIGATE_BY_CLICK = 'sliderNavigateByClick';
    /** @const Mouse drag navigation over slider. */
    const NAVIGATE_BY_DRAG = 'sliderNavigateByDrag';
    /** @const Touch navigation of slider. */
    const NAVIGATE_BY_TOUCH = 'sliderNavigateByTouch';
    /** @const Makes slider to go from last slide to first. */
    const LOOP = 'sliderLoop';
    /** @const If set to true adds arrows and FullScreen button inside rsOverflow container,
     * otherwise inside root slider container. */
    const CONTROLS_INSIDE = 'sliderControlsInside';
    /** @const Automatically updates slider height based on base width. */
    const AUTO_SCALE_SLIDER = 'sliderAutoScaleSlider';
    /** @const Automatically updates slider height based on base width and Image height. */
    const AUTO_SCALE_HEIGHT = 'sliderAutoScaleHeight';
    /** Scales and animates height based on current slide.
     *  Please note: if you have images in slide that don't have rsImg class or don't have fixed size,
     *  use $(window).load() instead of $(document).ready() before initializing slider.
     *  AutoHeight doesn't work with properties like autoScaleSlider, imageScaleMode and imageAlignCenter.
     */
    const AUTO_HEIGHT = 'sliderAutoHeight';
    /** @const Fades in slide after it's loaded. */
    const FADEIN_LOADED = 'sliderFadeIdLoaded';
    /** @const Base slider width. Slider will autocalculate the ratio based on these values. */
    const AUTO_SCALE_VALUE_WIDTH = 'sliderAutoScaleWidthValue';
    /** @const Base slider height */
    const AUTO_SCALE_VALUE_HEIGHT = 'sliderAutoScaleHeightValue';
    /** @const Start slide index*/
    const START_SLIDE_ID = 'sliderStartSlideId';
    /** @const Number of slides to preload on sides.
     * If you set it to 0, only one slide will be kept in the display list at once.*/
    const IMAGES_TO_PRELOAD = 'sliderImagesToPreload';
    /** @const Spacing between slides in pixels.*/
    const SLIDES_SPACING = 'sliderSlidesSpacing';
    /** @const Minimum distance in pixels to show next slide while dragging.*/
    const MIN_SLIDES_OFFSET = 'sliderMinSlidesOffset';
    /** @const Slider transition speed, in ms.*/
    const TRANSITION_SPEED = 'sliderTransitionSpeed';
    /** @const Distance between image and edge of slide (doesn't work with 'fill' scale mode).*/
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
        wp_enqueue_style('royalslider-skin', "{$uriToDirLibs}/royalslider/skins/minimal-white/rs-minimal-white.css");
        wp_enqueue_script('royalslider', "{$uriToDirLibs}/royalslider/jquery.royalslider.min.js", ['jquery'], null, true);
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
        $this->addField(new WidgetField(WidgetField::CHECKBOX_MULTIPLE, self::BOOL_OPTIONS, __("Gallery Options"), [
            self::NAV_ARROWS_SHOW => __("Navigation Arrows Show"),
            self::NAV_ARROWS_AUTO_HIDE => __("Navigation Arrows Auto hide"),
            self::NAV_ARROWS_HIDE_ON_TOUCH => __("Navigation Arrows Hide on touch"),

            self::AUTO_HEIGHT => __("Auto Height based on Image"),
            self::AUTO_SCALE_SLIDER => __("Auto Height based on Width"),
            self::AUTO_SCALE_HEIGHT => __("Auto Height based on Width and Image"),

            self::NAVIGATE_BY_CLICK => __("Navigate by Click"),
            self::NAVIGATE_BY_DRAG => __("Navigate by Drag"),
            self::NAVIGATE_BY_TOUCH => __("Navigate by Touch"),
            self::NAV_WITH_KEYBOARD => __("Navigation with keyboard"),
            self::LOOP => __("Cycle slides"),
            self::FADEIN_LOADED => __("Fade in loaded Slide"),
            self::CONTROLS_INSIDE => __("Controls inside Slider")
        ], [
            self::NAVIGATE_BY_CLICK,
            self::NAVIGATE_BY_DRAG,
            self::NAVIGATE_BY_TOUCH,
            self::NAV_ARROWS_SHOW,
            self::NAV_ARROWS_AUTO_HIDE,
            self::AUTO_SCALE_HEIGHT,
            self::FADEIN_LOADED,
            self::CONTROLS_INSIDE
        ]));
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
            $boolOptions = self::getInstanceValue($instance, self::BOOL_OPTIONS, $this);


            $autoScaleSlider = isset($boolOptions[self::AUTO_SCALE_SLIDER]) ? 'true' : 'false';
            $autoScaleHeight = isset($boolOptions[self::AUTO_SCALE_HEIGHT]) ? 'true' : 'false';
            $autoHeight = isset($boolOptions[self::AUTO_HEIGHT]) ? 'true' : 'false';

            $arrowsNav = isset($boolOptions[self::NAV_ARROWS_SHOW]) ? 'true' : 'false';
            $arrowsNavAutoHide = isset($boolOptions[self::NAV_ARROWS_AUTO_HIDE]) ? 'true' : 'false';
            $arrowsNavHideOnTouch = isset($boolOptions[self::NAV_ARROWS_HIDE_ON_TOUCH]) ? 'true' : 'false';

            $navigateByClick = isset($boolOptions[self::NAVIGATE_BY_CLICK]) ? 'true' : 'false';
            $keyboardNavEnabled = isset($boolOptions[self::NAV_WITH_KEYBOARD]) ? 'true' : 'false';
            $sliderDrag = isset($boolOptions[self::NAVIGATE_BY_DRAG]) ? 'true' : 'false';
            $sliderTouch = isset($boolOptions[self::NAVIGATE_BY_TOUCH]) ? 'true' : 'false';

            $controlsInside = isset($boolOptions[self::CONTROLS_INSIDE]) ? 'true' : 'false';
            $fadeinLoadedSlide = isset($boolOptions[self::FADEIN_LOADED]) ? 'true' : 'false';

            $sliderLoop = isset($boolOptions[self::LOOP]) ? 'true' : 'false';

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
                    autoHeight: $autoHeight,
                    
                    arrowsNav: $arrowsNav,
                    arrowsNavAutoHide: $arrowsNavAutoHide,
                    arrowsNavHideOnTouch: $arrowsNavHideOnTouch,
                    
                    navigateByClick: $navigateByClick,
                    keyboardNavEnabled: $keyboardNavEnabled,
                    sliderDrag: $sliderDrag,
                    sliderTouch: $sliderTouch,
                    controlsInside: $controlsInside,
                    loop: $sliderLoop,
                    loopRewind: $sliderLoop,
                    
                    fadeinLoadedSlide: $fadeinLoadedSlide,
                    fadeInAfterLoaded: $fadeinLoadedSlide,
                    imageAlignCenter: true,
                    randomizeSlides: false,
                    usePreloader: true,
                    globalCaption: false,
                    
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