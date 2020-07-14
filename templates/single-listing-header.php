<div class="single-listing-header vc_row wpb_row vc_row-fluid vc_row-o-content-middle vc_row-flex no-bottom-margins">
    <div class="wpb_column vc_column_container vc_col-sm-8" data-listing-wrapper-id="<?php echo $this->ID ?>" data-listing-is-single="1">
        <div class="vc_column-inner ">
            <div class="wpb_wrapper">
                <div class="vc_row wpb_row vc_inner vc_row-fluid vc_row-o-content-middle vc_row-flex ">

                    <div class="wpb_column vc_column_container vc_col-sm-3">
                        <div class="vc_column-inner ">
                            <div class="wpb_wrapper">
                                <div class="vcex-post-media clr round-image">
                                    <?php echo $this->get_thumbnail() ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="wpb_column vc_column_container vc_col-sm-9">
                        <div class="vc_column-inner ">
                            <div class="wpb_wrapper">
                                <h1 itemprop="name"><?php echo $this->get_title(); ?></h1>
                                <?php echo $this->get_verified_badge() ?>
                                <div class="listing-additional-info">
                                    <h2>
                                        <span itemprop="jobTitle"><?php echo $this->get_subtitle() ?></span>
                                        <?php if ($this->get_region()) { ?>
                                            <span class="listing-additional-info-item">
                                                &nbsp;<i class="fa fa-map-marker"></i> <?php echo $this->get_region() ?>
                                            </span>
                                        <?php } ?>
                                    </h2>
                                </div>
                                <div class="listing-social-icons">
                                    <?php echo $this->get_social_icons() ?>
                                </div>

                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
    <div class="wpb_column vc_column_container vc_col-sm-4">
        <div class="vc_column-inner ">
            <div class="wpb_wrapper">

                <?php if (get_users_listing_ID() == $this->ID) { ?>
                    <?php //edit button 
                    ?>
                    <div class="theme-button-expanded-wrap theme-button-wrap clr">
                        <a href="<?php echo home_url() ?>/edit-listing/" class="vcex-button theme-button flat small expanded animate-on-hover expanded wpex-dhover-0-p">
                            <span class="theme-button-inner">
                                <span class="vcex-icon-wrap theme-button-icon-left wpex-dhover-0" style="padding-right:12px;" data-wpex-hover="{&quot;parent&quot;:&quot;.vcex-button&quot;,&quot;transform&quot;:&quot;translateX(4px)&quot;}">
                                    <span class="fa fa-edit"></span>
                                </span>
                                Edit your listing
                            </span>
                        </a>
                    </div>
                <?php } ?>

                <?php //phone button 
                ?>
                <?php if ($this->get_phone()) { ?>
                    <div class="theme-button-expanded-wrap theme-button-wrap clr">
                        <a href="tel:<?php echo $this->get_phone() ?>" class="vcex-button theme-button flat expanded animate-on-hover expanded wpex-dhover-0-p white phone-button">
                            <span class="theme-button-inner">
                                <span class="vcex-icon-wrap theme-button-icon-left wpex-dhover-0" style="padding-right:12px;" data-wpex-hover="{&quot;parent&quot;:&quot;.vcex-button&quot;,&quot;transform&quot;:&quot;translateX(4px)&quot;}">
                                    <span class="fa fa-phone"></span>
                                </span>
                                <span itemprop="telephone"><?php echo $this->get_phone_formatted() ?></span>
                            </span>
                        </a>
                    </div>
                <?php } ?>



                <?php //Email me button 
                ?>
                <div class="theme-button-expanded-wrap theme-button-wrap last clr">
                    <a href="#contact-with-specialist" class="vcex-button theme-button flat small expanded animate-on-hover expanded wpex-dhover-0-p" data-lity>
                        <span class="theme-button-inner">
                            <span class="vcex-icon-wrap theme-button-icon-left wpex-dhover-0" style="padding-right:12px;" data-wpex-hover="{&quot;parent&quot;:&quot;.vcex-button&quot;,&quot;transform&quot;:&quot;translateX(4px)&quot;}">
                                <span class="fa fa-envelope"></span>
                            </span>
                            Email Me
                        </span>
                    </a>
                </div>

                <div class="additional-header-links">

                    <?php //share button 
                    ?>
                    <a href="#share-specialist" class="share-specialist" data-lity>
                        Send to Friend
                    </a>


                    <?php //favorite button 
                    ?>
                    <?php require(OKLISTING_PARTIALS_PATH . 'favorite-toggle.php'); ?>

                </div>

            </div>
        </div>
    </div>
</div>