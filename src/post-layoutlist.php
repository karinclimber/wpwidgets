<a href="<?= get_the_permalink(); ?>" title="<?= __( 'Know More', 'wptheme' ); ?>" class="media">
    <div class="media-left">
        <?php echo \wp\WPUtils::getThumbnail([ 95, 64 ],
            ["class" => "img img-raised", "alt" => get_the_title(), "title" => get_the_title()]); ?>
    </div>
    <div class="media-body">
        <h5 class="media-heading"><?= get_the_title(); ?></h5>
        <h5>
            <time datetime="<?= get_the_modified_time( 'c' ) ?>">
				<?= get_the_modified_time( 'd M Y' ); ?>
            </time>
        </h5>
    </div>
</a>