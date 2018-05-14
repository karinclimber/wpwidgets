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
            $dateTitle = sprintf('<small class="text-center">%s %s %s %s</small>',
                __('Posted on', 'wptheme'),
                get_the_time('d M Y'),
                __('in', 'wptheme'),
                get_the_category_list(', '));
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
                $this->titleAddition = "<small class='pull-right'><a href='{$linkToDefaultCategory}'>{$textViewAll}</a></small>";
            }
            $content = WPUtils::renderTemplate($queryArgs, PostBase::TYPE, $layoutType);
        }

        $args[WPSidebar::CONTENT] = $content;
        parent::widget($args, $instance);
    }
}