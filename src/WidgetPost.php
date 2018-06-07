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
        $titleAddition = '';
        //TODO Add Tags, Next /Previous Post, Featured Image, Gallery Image, Options to choose that to display
        if (is_single()) {
            $customTitle .= get_the_title();
            $textPublishDate = WPUtils::getPostAuthorAndDate(false);
            $textCategoryList = get_the_category_list(', ');
            $textCategory = '';
            if ($textCategoryList){
                $textCategory = sprintf(__('Category: %s'),$textCategoryList);
            }
            $titleAddition = "<p class='no-gap'><small class='col-xs-5'>{$textCategory}</small><small class='col-xs-7 text-xs-right'>{$textPublishDate}</small></p>";
            if (have_posts()) {
                while (have_posts()) {
                    the_post();
                    $pageContent = get_the_content();
                    $pageContent = apply_filters('the_content', $pageContent);
                    $pageContent = str_replace(']]>', ']]&gt;', $pageContent);
                    $pageClass = implode(' ', get_post_class('', get_the_ID()));
                    $content .= "<div class='$pageClass'>$pageContent</div>";
                }
            }
        }
        $instance[Widget::CUSTOM_TITLE] = $customTitle;
        $args[WPSidebar::AFTER_TITLE_ADDITION] = $titleAddition;
        $args[WPSidebar::CONTENT] = $content;
        parent::widget($args, $instance);
    }
}