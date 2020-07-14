<div class="single-listing-list-tiny<?php $oklisting->print_list_class() ?>" <?php $oklisting->print_listing_attributes() ?>>
    <div class="vc_row wpb_row vc_row-fluid vc_row-o-content-middle vc_row-flex">

        <div class="wpb_column vc_column_container vc_col-sm-4 vc_col-xs-4">
            <div class="vc_column-inner ">
                <div class="wpb_wrapper">
                    <div class="listing-thumbnail">
                        <a href="<?php echo $oklisting->url(); ?>">
	                        <?php echo $oklisting->get_thumbnail('small') ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="wpb_column vc_column_container vc_col-sm-8 vc_col-xs-8">
            <div class="vc_column-inner ">
                <div class="wpb_wrapper">

                    <div class="listing-title">
                        <h4>
                            <a href="<?php echo $oklisting->url(); ?>" title="<?php echo $oklisting->get_title(); ?>">
	                            <?php echo $oklisting->get_title(); ?>
                            </a>
                        </h4>
                    </div>

                    <div class="listing-additional-info">
						<?php echo $oklisting->get_subtitle() ?>
                    </div>

                    <div class="listing-additional-info">
                        <?php if ($oklisting->get_region()) {?>
                            <div class="listing-additional-info-item">
                                <i class="fa fa-map-marker"></i> <?php echo $oklisting->get_region() ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>