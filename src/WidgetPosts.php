<?php
/**
 * Author: Vitali Lupu <vitaliix@gmail.com>
 * Date: 3/5/18
 * Time: 6:32 PM
 */

namespace wp;
final class WidgetPosts extends WidgetPostBase
{
    const CHANGE_CONTENT_BY_PAGE = 'widgetPostsChangeContentByPage';
    const TYPE = 'widgetPostsType';

    function __construct()
    {
        parent::__construct(__('Posts'));
    }

    function initFields()
    {
        $this->addField(new WidgetField(WidgetField::CHECKBOX, self::CHANGE_CONTENT_BY_PAGE,
            __('Change content by page type'), [], false));
        $posts = Widget::getPagesOfPosts();
        $this->addField(new WidgetField(WidgetField::SELECT, self::TYPE,
            __('Content Type'), $posts, PostBase::TYPE));
        parent::initFields();
    }

    function widget($args, $instance)
    {
        $content = '';
        $customTitle = '';
        $changeContentByPage = intval(self::getInstanceValue($instance, self::CHANGE_CONTENT_BY_PAGE, $this));
        if ($changeContentByPage && is_singular()) {
            /*$customTitle .= get_the_title();
            $textPostedOn = __('Posted on');
            $textTime = get_the_time('d M Y');
            $textIn = __('in');
            $textCategoryList = get_the_category_list(', ');
            $dateTitle = "<small class='text-center'>{$textPostedOn} {$textTime} {$textIn} {$textCategoryList}</small>";*/
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
            $customTitle .= self::getInstanceValue($instance, self::CUSTOM_TITLE, $this);
            $postType = self::getInstanceValue($instance, self::TYPE, $this);
            $sortCriteria = self::getInstanceValue($instance, self::SORT_CRITERIA, $this);
            $queryArgs = [
                QueryPost::TYPE => $postType,
                QueryPost::ORDER_BY => $this->getPostOrderBy($sortCriteria, false),
                QueryPost::ORDER => $this->getPostOrder($sortCriteria, false),
            ];
            if ($postType == WPostTypes::ATTACHMENT){
                $queryArgs [QueryPost::STATUS] = WPostStatus::INHERIT;
            }
            $postsCount = intval(self::getInstanceValue($instance, QueryPost::PER_PAGE, $this));
            if ($changeContentByPage && is_archive()) {
                $customTitle = single_term_title('', false);
                $postsCount = -1;
                /** @var $currentTax \WP_Term */
                $currentTax = get_queried_object();
                if ($currentTax->term_id > 0) {
                    $queryArgs[QueryTaxonomy::DEFINITION] = [QueryTaxonomy::RELATION => QueryRelations::_AND,
                        [
                            QueryTaxonomy::NAME => $currentTax->taxonomy,
                            QueryTaxonomy::TERMS => $currentTax->term_id
                        ]];
                }
            } else if ($customTitle == '') {
                $currentPostType = get_post_type_object($postType);
                $customTitle = $currentPostType->labels->name;
            }
            $queryArgs[QueryPost::PER_PAGE] = $postsCount;
            $layoutType = self::getInstanceValue($instance, self::LAYOUT, $this);
            $templatePath = WPUtils::locatePostTemplate(strtolower($postType), $layoutType, __DIR__);
            $content .= WPUtils::renderTemplate($queryArgs, $templatePath, $postsCountResult);
            $queryArgs[QueryPost::PER_PAGE] = -1;
            $postsQuery = new \WP_Query($queryArgs);
            if ($postsCount > 0 && $postsCountResult < $postsQuery->post_count) {
                $linkToCategory = get_post_type_archive_link($postType);
                if (!empty($linkToCategory)) {
                    $textViewAll = __('See All');
                    $args[WPSidebar::BEFORE_TITLE_ADDITION] = "<a href='{$linkToCategory}' title='{$textViewAll}'>";
                    $args[WPSidebar::AFTER_TITLE_ADDITION] = "</a>";
                }
            }
        }
        $instance[Widget::CUSTOM_TITLE] = $customTitle;
        $args[WPSidebar::CONTENT] = $content;
        parent::widget($args, $instance);
    }
}