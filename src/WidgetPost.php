<?php
/**
 * Author: Vitali Lupu <vitaliix@gmail.com>
 * Date: 3/5/18
 * Time: 6:32 PM
 */

namespace wp;
final class WidgetPost extends Widget
{
    function __construct()
    {
        parent::__construct(__('Post'));
    }

    function initFields()
    {
        parent::initFields();
    }

    function widget($args, $instance)
    {
        $content = '';
        $customTitle = '';
        if (is_singular()) {
            $customTitle .= get_the_title();
            $textPostedOn = __('Posted on');
            $textTime = get_the_time('d M Y');
            $textIn = __('in');
            $textCategoryList = get_the_category_list(', ');
            $dateTitle = "<small class='text-center'>{$textPostedOn} {$textTime} {$textIn} {$textCategoryList}</small>";
            $pageContent = get_the_content();
            $pageContent = apply_filters('the_content', $pageContent);
            $pageContent = str_replace(']]>', ']]&gt;', $pageContent);
            $pageClass = implode(' ', get_post_class('', get_the_ID()));
            $content .= "<div class='$pageClass'>$pageContent</div>";
        }
        $instance[Widget::CUSTOM_TITLE] = $customTitle;
        $args[WPSidebar::CONTENT] = $content;
        parent::widget($args, $instance);
    }
}