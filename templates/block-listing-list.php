<div class="single-listing-list<?php $oklisting->print_list_class() ?>" <?php $oklisting->print_listing_attributes() ?>>

    <div class="vc_row wpb_row vc_row-fluid vc_row-o-content-middle vc_row-flex">

		<?php //photo ?>
        <div class="wpb_column vc_column_container vc_col-sm-2 vc_col-xs-3">
            <div class="vc_column-inner ">
                <div class="wpb_wrapper">
                    <div class="listing-thumbnail">
                        <a href="<?php echo $oklisting->url(); ?>">
							<?php echo $oklisting->get_thumbnail( 'small' ) ?>
                        </a>
                    </div>
                </div>

            </div>
        </div>


		<?php //title, subtitle ?>
        <div class="wpb_column vc_column_container vc_col-sm-6 vc_col-xs-9">
            <div class="vc_column-inner ">
                <div class="wpb_wrapper">
                    <div class="listing-title">
                        <h2>
                            <a href="<?php echo $oklisting->url(); ?>"
                               title="<?php echo $oklisting->get_title(); ?>">
								<?php echo $oklisting->get_title(); ?>
                            </a>
                        </h2>

						<?php echo $oklisting->get_verified_badge() ?>

                    </div>
                    <div class="listing-additional-info">
						<?php echo $oklisting->get_subtitle() ?>
                    </div>

					<?php if ( $oklisting->get_region() ) { ?>
                        <div class="listing-additional-info">
                            <div class="listing-additional-info-item">
                                <i class="fa fa-map-marker"></i> <?php echo $oklisting->get_region() ?>
                            </div>
                        </div>
					<?php } ?>

					<?php if ( $oklisting->is_premium() && $quote = $oklisting->get_quote() ) { ?>
                        <div class="listing-additional-info quote hidden-phone ">
                            "<?php echo $quote ?>"
                        </div>
					<?php } ?>


                </div>
            </div>
        </div>

		<?php //quote for phone ?>
		<?php if ( $oklisting->is_premium() && $quote = $oklisting->get_quote() ) { ?>
            <div class="wpb_column vc_column_container vc_col-md-12 visible-phone">
                <div class="vc_column-inner ">
                    <div class="wpb_wrapper">
                        <div class="listing-additional-info quote">
                            "<?php echo $quote ?>"
                        </div>
                    </div>
                </div>
            </div>
		<?php } ?>

		<?php //buttons ?>
        <div class="wpb_column vc_column_container vc_col-sm-4">
            <div class="vc_column-inner ">
                <div class="wpb_wrapper">

                    <div class="buttons vc_row wpb_row vc_row-fluid  vc_row-o-content-middle vc_row-flex">

                        <div class="wpb_column vc_column_container vc_col-sm-12 vc_col-xs-6">
                            <div class="vc_column-inner ">

                                <div class="theme-button-expanded-wrap theme-button-wrap clr">

                                    <a href="<?php echo $oklisting->url(); ?>"
                                       class="vcex-button theme-button animate-on-hover expanded <?php $oklisting->print_button_classes() ?>">
                                        <span class="theme-button-inner">View profile</span>
                                    </a>

                                </div>


                            </div>
                        </div>


                        <div class="wpb_column vc_column_container vc_col-sm-12 vc_col-xs-6">
                            <div class="vc_column-inner ">
								<?php //favorite button ?>
                                <div class="additional-header-links">
                                    <?php $oklisting->print_fav_toggle()  ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>