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
                <?php
                $author = get_the_author_meta('display_name');
                $byAuthor = sprintf(__('By %s'), "<strong>$author</strong>");
                $authorId = get_the_author_meta('ID');
                $authorAvatar = "";
                echo sprintf(
                /* translators: post revision title: 1: author avatar, 2: author name, 3: time ago, 4: date */
                    __('%1$s %2$s, %3$s ago (%4$s)'),
                    $authorAvatar,
                    $byAuthor,
                    human_time_diff(get_the_modified_time('U'), current_time('timestamp')),
                    get_the_modified_time('d M Y')
                ); ?>
            </time>
        </p>
    </div>
</div>