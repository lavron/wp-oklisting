<div class="vcex-module wpex-social-btns vcex-social-btns">

	<?php if ( $url = get_field( 'website' ) ) { ?>
        <a href="<?php echo $url ?>"
           class="wpex-social-btn wpex-social-btn-minimal wpex-social-color-hover wpex-semi-rounded btn-website"
           target="_blank">
            <span class="fa fa-link" aria-hidden="true"></span>
            Website
            <span class="screen-reader-text">Website</span>


        </a>
	<?php } ?>

	<?php
	$icons = ok_get_social_icons_fa_classes();
	foreach ( $icons as $title => $icon ) {
		if ( $network = get_field( $icon['icon'] ) ) { ?>
            <a href="<?php echo $network ?>"
               class="wpex-social-btn wpex-social-btn-minimal wpex-social-color-hover wpex-semi-rounded wpex-<?php echo $icon['icon'] ?>"
               target="_blank"
               rel="nofollow"
               title="<?php echo $title ?>">
                <span class="fab fa-<?php echo $icon['icon'] ?>" aria-hidden="true"></span>
                <span class="screen-reader-text"><?php echo $title ?></span>
            </a>
		<?php }
	} ?>

</div>