<?php
/**
 * Author: Vitali Lupu <vitaliix@gmail.com>
 * Date: 3/5/18
 * Time: 6:32 PM
 */

namespace wp;
final class WidgetSiteBranding extends Widget
{
    function __construct()
    {
        parent::__construct(__('Site Brand', 'wptheme'),
            __('This widget displays a Site Brand.', 'wptheme'));
        add_filter('get_custom_logo', [$this, 'handleCustomLogo'], 10, 0);
    }

    function handleCustomLogo()
    {
        $siteTitle = get_bloginfo('name', 'display');
        $siteDescription = get_bloginfo('description', 'display');
        $siteHomeUrl = esc_url(home_url('/'));
        $siteLogoId = get_theme_mod(CustomizerSetting::SITE_LOGO);
        $siteNameStyle = "";
        $imageMarkup = "";
        if ($siteLogoId) {
            $image = wp_get_attachment_image_src($siteLogoId, WPImages::FULL);
            if ( $image ) {
                list($src, $width, $height) = $image;
                $hwstring = image_hwstring($width, $height);
                $imageMarkup = "<img $hwstring src='$src' class='custom-logo' alt='$siteTitle' />
                <map name='custom-logo'><area shape='rect' coords='0,0,$width,$height' href='$siteHomeUrl' alt='$siteTitle'></map>";
                $siteNameStyle = "style='display:none;'";
            }
        }
        return "<figure style='display: inline-block;' rel='home'>
        $imageMarkup
        <figcaption $siteNameStyle><a href='$siteHomeUrl'>
            <span class='site-title'>$siteTitle</span><br>
            <small class='site-description hidden-xs'>$siteDescription</small>
        </a></figcaption>
       </figure>";
    }

    function widget($args, $instance)
    {
        $args[WPSidebar::CONTENT] = get_custom_logo();
        parent::widget($args, $instance);
    }
}