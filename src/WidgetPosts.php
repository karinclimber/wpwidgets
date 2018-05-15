<?php
/**
 * Author: Vitali Lupu <vitaliix@gmail.com>
 * Date: 3/5/18
 * Time: 6:32 PM
 */

namespace wp;
final class WidgetPosts extends WidgetPostBase
{
    function __construct()
    {
        parent::__construct(__('Posts', 'wptheme'));
    }

    function widget($args, $instance)
    {
        $content = "";
        if (is_singular()) {
            $instance[Widget::CUSTOM_TITLE] = get_the_title();
            $textPostedOn = __('Posted on');
            $textTime = get_the_time('d M Y');
            $textIn = __('in');
            $textCategoryList = get_the_category_list(', ');
            $dateTitle = "<small class='text-center'>{$textPostedOn} {$textTime} {$textIn} {$textCategoryList}</small>";
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
        } else {
            $postsCount = intval(self::getInstanceValue($instance, QueryPost::PER_PAGE, $this));
            if (is_archive()){
                //TODO Add a widget options to spcify when make this auto changes for case when on sam category page want to display some tiles of post Ex. Recent
                $instance[Widget::CUSTOM_TITLE] = single_term_title('',false);
                $postsCount = -1;
            }
            $sortCriteria = self::getInstanceValue($instance, self::SORT_CRITERIA, $this);
            $queryArgs = [
                QueryPost::TYPE => WPostTypes::POST,
                QueryPost::PER_PAGE => $postsCount,
                QueryPost::ORDER_BY => $this->getPostOrderBy($sortCriteria, false),
                QueryPost::ORDER => $this->getPostOrder($sortCriteria, false),
            ];
            $layoutType = self::getInstanceValue($instance, self::LAYOUT, $this);
            if ($postsCount > 0) {
                $linkToDefaultCategory = get_category_link(get_option('default_category'));
                $textViewAll = __("See All");
                $this->titleAddition = "<a href='{$linkToDefaultCategory}' class='pull-right small'>{$textViewAll}</a>";
            }
            $content = WPUtils::renderTemplate($queryArgs, PostBase::TYPE, $layoutType);
        }

        $args[WPSidebar::CONTENT] = $content;
        parent::widget($args, $instance);
    }
}