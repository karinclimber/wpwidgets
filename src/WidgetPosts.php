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
            $this->titleAddition = sprintf("<span class='pull-right marker-bottom'><a href='%s' class='text-capitalize'>%s</a></span>", $linkToDefaultCategory, __("View all", 'wptheme'));
        }
        $args[WPSidebar::CONTENT] = WPUtils::renderTemplate($queryArgs, PostBase::TYPE, $layoutType);
        parent::widget($args, $instance);
    }
}