<?php
/**
 * Hook: woocommerce_shop_loop.
 * @hooked WC_Structured_Data::generate_product_data() - 10
 */
do_action('woocommerce_shop_loop');
/**@var $product WC_Product */
global $product;
$htmlProductSale = '';
if ($product->is_on_sale()) {
    $textProductSale = __('Sale!', 'woocommerce');
    $htmlProductSale = "<span class='onsale'>{$textProductSale}</span>";
    $htmlProductSale = apply_filters('woocommerce_sale_flash', $htmlProductSale, $post, $product);
}
$productLink = esc_url(apply_filters('woocommerce_loop_product_link', get_the_permalink(), $product));
$getProductThumb = 'woocommerce_get_product_thumbnail';
if (function_exists($getProductThumb)) {
    $productThumb = $getProductThumb();
} else {
    $productThumb = \wp\WPUtils::getThumbnail(\wp\WPImages::THUMB,
        ["class" => "img img-raised", "alt" => get_the_title(), "title" => get_the_title()]);
}
//[Rating]
$htmlRating = '';
if (get_option('woocommerce_enable_review_rating') !== 'no') {
    $ratingWidth = (($product->get_average_rating() / 5) * 100);
    $htmlRating = "<div class='star-rating' style='display: inline-block'><span style='width:{$ratingWidth}%'></span></div>";
}
//[Price]
if ($htmlPrice = $product->get_price_html()) {
    $htmlPrice = "<h5>{$htmlPrice}</h5>";
}
//[Add To Cart]
$getProductAddToCart = 'woocommerce_template_loop_add_to_cart';
$htmlAddToCart = '';
if (function_exists($getProductAddToCart)) {
    ob_start();
    $getProductAddToCart();
    $htmlAddToCart = ob_get_clean();
}
$htmlProductCategories = '';
$productCategories = get_the_category(get_the_ID());
foreach ($productCategories as $category) {
    $categoryLink = get_term_link($category->cat_ID);
    $htmlProductCategories .= "<a href='{$categoryLink}' class='text-info'>{$category->cat_name}</a>";
}
?>
<div class="col-lg-3 col-md-4 col-xs-6 woocommerce product">
    <div class="card card-product">
        <?= $htmlProductSale; ?>
        <div class="card-image">
            <a href="<?= $productLink; ?>" class="d-xs-block">
                <?= $productThumb; ?>
            </a>
        </div>
        <div class="card-content">
            <?php //TODO Here If current category is same as post Show the Tags instead or Disable link  ?>
            <h5 class="card-title text-hide-overflow">
                <a href="<?= $productLink; ?>">
                    <?= get_the_title(); ?>
                </a>
            </h5>
            <h6 class="category"><?= $htmlProductCategories; ?></h6>
            <div class='text-xs-center'><?= $htmlRating . $htmlPrice; ?><p><?= $htmlAddToCart; ?></p></div>
        </div>
    </div>
</div>