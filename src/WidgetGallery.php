<?php
/**
 * Author: Vitali Lupu <vitaliix@gmail.com>
 * Date: 3/5/18
 * Time: 6:32 PM
 */

namespace wp;
final class WidgetGallery extends Widget
{
    const GALLERY_IMAGES = "gallery";
    const GALLERY_BOOL_OPTIONS = "galleryBoolOptions";
    const GALLERY_NAV_ARROWS_SHOW = "galleryNavArrowsShow";
    const GALLERY_NAV_ARROWS_AUTO_HIDE = "galleryNavArrowsAutoHide";
    const GALLERY_NAV_ARROWS_HIDE_ON_TOUCH = "galleryNavArrowsHideOnTouch";
    const GALLERY_NAV_WITH_KEYBOARD = "galleryNavWithKeyboard";
    const GALLERY_NAVIGATE_BY_CLICK = "galleryNavigateByClick";
    const GALLERY_SLIDES_LOOP = "gallerySlidesLoop";
    const GALLERY_SLIDE_AUTO_SCALE = "gallerySlideAutoScale";
    const GALLERY_SLIDE_FADEIN_LOADED = "gallerySlideFadeIdLoaded";

    function __construct()
    {
        parent::__construct(__('Media Slider', 'wptheme'), __("Add images from Media Library"));
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
        $this->addField(new WidgetField(WidgetField::IMAGES_WITH_URL, self::GALLERY_IMAGES, Widget::addIconToLabel("picture-o", __("Images"))));
        $this->addField(new WidgetField(WidgetField::CHECKBOX_MULTIPLE, self::GALLERY_BOOL_OPTIONS, __("Gallery Options"),[
            self::GALLERY_NAVIGATE_BY_CLICK => __("Navigate by Click"),
            self::GALLERY_NAV_ARROWS_SHOW => __("Navigation Arrows Show"),
            self::GALLERY_NAV_ARROWS_AUTO_HIDE => __("Navigation Arrows Auto hide"),
            self::GALLERY_NAV_ARROWS_HIDE_ON_TOUCH => __("Navigation Arrows Hide on touch"),
            self::GALLERY_NAV_WITH_KEYBOARD => __("Navigation with keyboard"),
            self::GALLERY_SLIDES_LOOP => __("Loop Slides"),
            self::GALLERY_SLIDE_AUTO_SCALE => __("Auto Scale"),
            self::GALLERY_SLIDE_FADEIN_LOADED => __("Fade in loaded Slide")
        ]));
        parent::initFields();
    }

    function widget($args, $instance)
    {
        $content = "";
        $galleryValue = self::getInstanceValue($instance, self::GALLERY_IMAGES, $this);
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
            $galleryBoolOptions = self::getInstanceValue($instance, self::GALLERY_BOOL_OPTIONS);
            $content = "<div id='{$galleryId}' class='royalSlider rsMinW'>{$content}</div>";
            $content .= "<script type='text/javascript'>(function ($) {
            $(document).ready(function () {
            if ($().royalSlider) {
                $('#{$galleryId}').royalSlider({
                    autoScaleSlider: true,
                    autoScaleSliderWidth: 1170,
                    autoScaleSliderHeight: 425,
                    fadeinLoadedSlide: true,
                    loop: true,
                    arrowsNav: {$galleryBoolOptions[self::GALLERY_NAV_ARROWS_SHOW]},
                    arrowsNavAutoHide: true,
                    arrowsNavHideOnTouch: true,
                    navigateByClick: true,
                    keyboardNavEnabled: true,
                    numImagesToPreload: 2,
                    imageScaleMode: 'fill'
                });
            }});
            })(jQuery);</script>";
        }

        $args[WPSidebar::CONTENT] = $content;
        parent::widget($args, $instance);
    }
}