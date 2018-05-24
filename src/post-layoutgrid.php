<div class="col-md-4 col-sm-6 col-xs-12">
    <div class="card card-plain card-blog">
        <div class="card-image">
            <a href="<?= get_the_permalink(); ?>" class="d-xs-block">
                <?= \wp\WPUtils::getThumbnail(\wp\WPImages::THUMB,
                    ["class" => "img img-raised", "alt" => get_the_title(), "title" => get_the_title()]); ?>
            </a>
        </div>
        <div class="card-content">
            <?php //TODO Here If current category is same as post Show the Tags instead or Disable link  ?>
            <h6 class="category">
                <?php
                foreach ((get_the_category()) as $category): ?>
                    <a href="<?= get_term_link($category->cat_ID); ?>" class="text-info">
                        <?= $category->cat_name; ?>
                    </a>
                <?php endforeach; ?>
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
            </div>
        </div>
    </div>
</div>