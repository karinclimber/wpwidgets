<?php
/**
 * Created by IntelliJ IDEA.
 * User: lvis
 * Date: 5/16/18
 * Time: 6:02 PM
 */

namespace wp;


class WidgetPostsProduct extends WidgetPostBase
{

    function __construct()
    {
        parent::__construct(__('Products', 'woocommerce'));
    }

    function widget($args, $instance)
    {
        $content = '';
        if ( ! function_exists( 'woocommerce_page_title' ) ) {
            $instance[Widget::CUSTOM_TITLE] = woocommerce_page_title(false);
            /**
             * Hook: woocommerce_archive_description.
             * @hooked woocommerce_taxonomy_archive_description - 10
             * @hooked woocommerce_product_archive_description - 10
             */
            do_action('woocommerce_archive_description');
            ob_start();
            if (have_posts()) {
                /**
                 * Hook: woocommerce_before_shop_loop.
                 * @hooked wc_print_notices - 10
                 * @hooked woocommerce_result_count - 20
                 * @hooked woocommerce_catalog_ordering - 30
                 */
                do_action('woocommerce_before_shop_loop');
                woocommerce_product_loop_start(false);
                if (wc_get_loop_prop('total')) {
                    while (have_posts()) {
                        the_post();
                        /**
                         * Hook: woocommerce_shop_loop.
                         * @hooked WC_Structured_Data::generate_product_data() - 10
                         */
                        do_action('woocommerce_shop_loop');

                        wc_get_template_part('content', 'product');
                    }
                }
                woocommerce_product_loop_end();
                /**
                 * Hook: woocommerce_after_shop_loop.
                 * @hooked woocommerce_pagination - 10
                 */
                do_action('woocommerce_after_shop_loop');
            } else {
                /**
                 * Hook: woocommerce_no_products_found.
                 * @hooked wc_no_products_found - 10
                 */
                do_action('woocommerce_no_products_found');
            }
            $content = ob_get_clean();
        }
        $args[WPSidebar::CONTENT] = $content;
        parent::widget($args, $instance);
    }
}