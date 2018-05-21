<div class="col-md-4 col-sm-6 col-xs-12">
    <div class="card card-plain card-blog">
        <div class="card-image">
            <a href="<?= get_the_permalink(); ?>">
                <?php echo \wp\WPUtils::getThumbnail(\wp\WPImages::THUMB,
                    ["class" => "img img-raised", "alt" => get_the_title(), "title" => get_the_title()]); ?>
            </a>
            <div class="ripple-container"></div>
        </div>
        <div class="card-content">
            <h6 class="category text-info">
                <?php foreach ((get_the_category()) as $category) {
                    echo $category->cat_name . ' ';
                } ?>
            </h6>
            <h5 class="card-title text-hide-overflow">
                <a href="<?= get_the_permalink(); ?>">
                    <?= get_the_title(); ?>
                </a>
            </h5>
            <div class="card-description">
                <time datetime="<?= get_the_modified_time('c'); ?>">
                    <?= get_the_modified_time('d M Y'); ?>
                </time>
                <a href="<?= get_the_permalink(); ?>">
                    <span class="pull-right">
                        <?= __('Know More', 'wptheme'); ?>
                    </span>
                </a>
            </div>
        </div>
    </div>
</div>