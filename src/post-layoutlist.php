<div class="card card-plain card-blog">
    <div class="col-md-4">
        <div class="card-image">
            <a href="<?= get_the_permalink(); ?>">
                <?= \wp\WPUtils::getThumbnail(\wp\WPImages::THUMB,
                    ["class" => "img img-raised", "alt" => get_the_title(), "title" => get_the_title()]); ?>
            </a>
            <div class="ripple-container"></div>
        </div>
    </div>
    <div class="col-md-8">
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
        <p class="card-description">
            <?= get_the_excerpt(); ?>
            <a href="<?= get_the_permalink(); ?>">
                    <span>
                        <?= __('Know More', 'wptheme'); ?>
                    </span>
            </a>
        </p>
        <p class="card-author">
            <time datetime="<?= get_the_modified_time('c'); ?>">
                <?=\wp\WPUtils::getPostAuthorAndDate();?>
            </time>
        </p>
    </div>
</div>