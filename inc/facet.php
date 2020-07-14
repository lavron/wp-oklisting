<?php
//FacetWP related snippets


function get_listing_list_block($listing_ID = false)
{
    $listing_ID = ($listing_ID ? $listing_ID : get_the_ID());
    $oklisting  = new OKlistingSingle($listing_ID);
    require(OKLISTING_PARTIALS_PATH . 'block-listing-list.php');
}


function get_listing_list_block_tiny($listing_ID = false)
{
    $listing_ID = ($listing_ID ? $listing_ID : get_the_ID());
    $oklisting  = new OKlistingSingle($listing_ID);
    require(OKLISTING_PARTIALS_PATH . 'block-listing-tiny.php');
}

add_filter('facetwp_proximity_store_distance', '__return_true');