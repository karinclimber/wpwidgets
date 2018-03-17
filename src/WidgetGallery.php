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
//        wp_enqueue_script('widget-gallery', "{$uriToDirLibs}/WidgetGallery.js", ['royalslider'], null, true);
    }
    function enqueueScriptsAdmin()
    {
        wp_enqueue_script('media-upload');
        wp_enqueue_media();
    }

    function initFields()
    {
        $this->addField(new WidgetField(WidgetField::IMAGES_WITH_URL, self::GALLERY_IMAGES, Widget::addIconToLabel("picture-o", __("Images"))));
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
                    arrowsNav: true,
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