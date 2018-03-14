<?php
/**
 * Author: Vitali Lupu <vitaliix@gmail.com>
 * Date: 3/5/18
 * Time: 6:32 PM
 */

namespace wp;
final class WidgetPostContent extends Widget
{
    function __construct()
    {
        parent::__construct(__('Post Content', 'wptheme'));
    }

    function widget($args, $instance)
    {
        $content = "";
        $instance[Widget::CUSTOM_TITLE] = get_the_title();
        $instance[Widget::SHOW_DEFAULT_TITLE] = false;
        $dateTitle = sprintf('<small class="text-center">%s %s %s %s</small>',
            __('Posted on', 'wptheme'),
            get_the_time('d M Y'),
            __('in', 'wptheme'),
            get_the_category_list(', '));
        if (have_posts()) : while (have_posts()) : the_post();
            $pageContent = get_the_content();
            $pageContent = apply_filters('the_content', $pageContent);
            $pageContent = str_replace(']]>', ']]&gt;', $pageContent);
            $pageClass = implode(' ', get_post_class('', get_the_ID()));
            $content .= "<div class='$pageClass'>$pageContent</div>";
        endwhile; endif;
        $args[WPSidebar::CONTENT] = $content;
        parent::widget($args, $instance);
    }
}