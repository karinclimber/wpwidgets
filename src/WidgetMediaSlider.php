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
    const NAV_ARROWS_SHOW = 'sliderNavArrowsShow';
    const NAV_ARROWS_AUTO_HIDE = 'sliderNavArrowsAutoHide';
    const NAV_ARROWS_HIDE_ON_TOUCH = 'sliderNavArrowsHideOnTouch';
    const NAV_WITH_KEYBOARD = 'sliderNavWithKeyboard';
    const NAVIGATE_BY_CLICK = 'sliderNavigateByClick';
    const LOOP = 'sliderLoop';
    const AUTO_SCALE = 'sliderAutoScale';
    const FADEIN_LOADED = 'sliderFadeIdLoaded';

    const ORIENTATION = 'sliderOrientation';
    const ORIENTATION_HORIZONTAL = 'sliderOrientationHorizontal';
    const ORIENTATION_VERTICAL = 'sliderOrientationVertical';

    const TRANSITION = 'sliderTransition';
    const TRANSITION_MOVE = 'sliderTransitionMove';
    const TRANSITION_FADE = 'sliderTransitionFade';

    const NAVIGATION = 'sliderNavigation';
    const NAVIGATION_NONE = 'sliderNavigationNone';
    const NAVIGATION_BULLETS = 'sliderNavigationBullets';
    const NAVIGATION_TABS = 'sliderNavigationTabs';
    const NAVIGATION_THUMBNAILS = 'sliderNavigationThumbnails';

    const IMAGE_SCALE = 'sliderImageScale';
    const IMAGE_SCALE_FILL = 'sliderImageScaleFill';
    const IMAGE_SCALE_FIT = 'sliderImageScaleFit';
    const IMAGE_SCALE_FIT_IF_SMALLER = 'sliderImageScaleFitIfSmaller';
    const IMAGE_SCALE_NONE = 'sliderImageScaleNode';

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
        $this->addField(new WidgetField(WidgetField::IMAGES_WITH_URL, self::IMAGES, Widget::addIconToLabel("picture-o", __("Images"))));
        $this->addField(new WidgetField(WidgetField::SELECT, self::IMAGE_SCALE, __('Image Scale'),[
            self::IMAGE_SCALE_FIT_IF_SMALLER => __('Fit if Smaller'),
            self::IMAGE_SCALE_FIT => __('Fit'),
            self::IMAGE_SCALE_FILL=> __('Fill'),
            self::IMAGE_SCALE_NONE=> __('Node')
        ], self::IMAGE_SCALE_FIT_IF_SMALLER));
        $this->addField(new WidgetField(WidgetField::SELECT, self::NAVIGATION, __('Navigation'),[
            self::NAVIGATION_BULLETS => __('Bullets'),
            self::NAVIGATION_THUMBNAILS => __('Thumbnails'),
            self::NAVIGATION_TABS => __('Tabs'),
            self::NAVIGATION_NONE => __('Node')
        ], self::NAVIGATION_BULLETS));
        $this->addField(new WidgetField(WidgetField::RADIO, self::ORIENTATION, __('Orientation'),[
            self::ORIENTATION_HORIZONTAL => __('Horizontal'),
            self::ORIENTATION_VERTICAL => __('Vertical')
        ], self::ORIENTATION_HORIZONTAL));
        $this->addField(new WidgetField(WidgetField::RADIO, self::TRANSITION, __('Transition'),[
            self::TRANSITION_MOVE => __('Move'),
            self::TRANSITION_FADE => __('Fade')
        ], self::TRANSITION_MOVE));
        $this->addField(new WidgetField(WidgetField::CHECKBOX_MULTIPLE, self::BOOL_OPTIONS, __("Gallery Options"),[
            self::NAVIGATE_BY_CLICK => __("Navigate by Click"),
            self::NAV_ARROWS_SHOW => __("Navigation Arrows Show"),
            self::NAV_ARROWS_AUTO_HIDE => __("Navigation Arrows Auto hide"),
            self::NAV_ARROWS_HIDE_ON_TOUCH => __("Navigation Arrows Hide on touch"),
            self::NAV_WITH_KEYBOARD => __("Navigation with keyboard"),
            self::LOOP => __("Loop Slides"),
            self::AUTO_SCALE => __("Auto Scale"),
            self::FADEIN_LOADED => __("Fade in loaded Slide")
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
            $content = "<div id='{$galleryId}' class='royalSlider rsMinW'>{$content}</div>
            <script type='text/javascript'>(function ($) {
            $(document).ready(function () {
            if ($().royalSlider) {
                $('#{$galleryId}').royalSlider({                   
                    imageScaleMode: $imageScaleMode,
                    controlNavigation: 'bullets',
                    slidesOrientation: 'horizontal',
                    transitionType: 'move',
                    autoScaleSliderWidth: 1170,
                    autoScaleSliderHeight: 425,
                    autoScaleSlider: false,
                    autoScaleHeight: true,
                    autoHeight: false,
                    arrowsNav: true,
                    arrowsNavAutoHide: true,
                    arrowsNavHideOnTouch: false,
                    controlsInside: true,
                    keyboardNavEnabled: false,
                    navigateByClick: true,
                    loop: false,
                    loopRewind: false,
                    fadeinLoadedSlide: true,
                    fadeInAfterLoaded: true,
                    randomizeSlides: false,
                    sliderDrag: true,
                    sliderTouch: true,
                    allowCSS3: true,
                    allowCSS3OnWebkit: true,
                    addActiveClass: false,
                    imageAlignCenter: true,
                    usePreloader: true,
                    globalCaption: false,
                    startSlideId: 0,
                    numImagesToPreload: 4,
                    slidesSpacing: 8,
                    minSlideOffset: 10,
                    transitionSpeed: 600,
                    imageScalePadding: 4,
                    slidesDiff: 2
                });
            }});
            })(jQuery);</script>";
        }

        $args[WPSidebar::CONTENT] = $content;
        parent::widget($args, $instance);
    }
}