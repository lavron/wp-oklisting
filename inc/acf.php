<?php

add_filter('acf/load_field/name=first_name', 'ok_prepopulate_listing_title');
add_filter('acf/load_field/name=last_name', 'ok_prepopulate_listing_title');
add_filter('acf/load_field/name=contact_email', 'ok_prepopulate_listing_title');
function ok_prepopulate_listing_title($field)
{

    if (is_user_logged_in() && empty($field['value'])) {
        $current_user = wp_get_current_user();

        switch ($field['name']) {
            case 'first_name':
                $field['value'] = $current_user->user_firstname;
                break;
            case 'last_name':
                $field['value'] = $current_user->user_lastname;
                break;
            case 'contact_email':
                $field['value'] = $current_user->user_email;
                break;
            default:
        }
    }


    return $field;
}



//adding select for listing's category
add_action('acf/render_field_settings/type=select', 'select_related_category');
add_action('acf/render_field_settings/type=checkbox', 'select_related_category');
function select_related_category($field)
{
    $choices = acf_get_taxonomy_terms_formatted(array('listing-category'));

    acf_render_field_setting($field, array(
        'label'        => __('Applied to the categories'),
        'instructions' => __('Select to which categories of listings this field will apply'),
        'name'         => 'related_category',
        'type'         => 'select',
        'choices'      => $choices,
        'multiple'     => true,
        'ui'           => true,
    ));
}


//redefined acf_get_taxonomy_terms - another output format
function acf_get_taxonomy_terms_formatted($taxonomies = array())
{

    $taxonomies = acf_get_array($taxonomies);
    $taxonomies = acf_get_pretty_taxonomies($taxonomies);

    $r = array();

    foreach (array_keys($taxonomies) as $taxonomy) {

        $label           = $taxonomies[$taxonomy];
        $is_hierarchical = is_taxonomy_hierarchical($taxonomy);
        $terms           = acf_get_terms(array(
            'taxonomy'   => $taxonomy,
            'hide_empty' => false
        ));

        // bail early i no terms
        if (empty($terms)) {
            continue;
        }

        if ($is_hierarchical) {
            $terms = _get_term_children(0, $terms, $taxonomy);
        }

        // add placeholder
        $r[$label] = array();

        // add choices
        foreach ($terms as $term) {

            $k                 = "{$term->slug}";
            $r[$label][$k] = acf_get_term_title($term);
        }
    }

    return $r;
}


add_filter('acf/fields/taxonomy/query', 'ok_filter_tags_by_category', 10, 3);
function ok_filter_tags_by_category($args, $field, $listing_ID)
{

    $listing_cats = wp_get_post_terms($listing_ID, 'listing-category', array('fields' => 'ids'));


    $term_args = array(
        'taxonomy'   => $args['taxonomy'], //'issues'
        'hide_empty' => false,
    );
    $issues    = get_terms($term_args);

    $exclude = array();

    foreach ($issues as $issue) {


        //if some issue_category is set for this issue
        if ($parent_categories = get_term_meta($issue->term_id, 'issue_category', true) && !empty($parent_categories)) {
            //and issue_category is not in listing categories
            if (count(array_intersect($parent_categories, $listing_cats)) == 0) {
                //excluding issue form list
                $exclude[] = $issue->term_id;
            }
        }
    }

    $args['hide_empty'] = false;
    $args['exclude']    = $exclude;

    return $args;
}

add_filter('acf/update_value/name=cropped_image', 'ok_acf_set_featured_image', 10, 3);
function ok_acf_set_featured_image($value, $post_id, $field)
{

	if ($value != '') {
		//Add the value which is the image ID to the _thumbnail_id meta data for the current post
		update_post_meta($post_id, '_thumbnail_id', $value);
	}

	return $value;
}