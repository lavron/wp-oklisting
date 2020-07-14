<a href="#"
   class="favorite-listing-toggle<?php echo( $oklisting->is_favorited() ? ' favorited' : '' ) ?>"

   rel="nofollow"
	<?php echo is_user_logged_in() ? 'data-listing-id="'.$oklisting->ID.'"' : ' data-show-youzer-login="true"' ?>
>
	<?php echo( $oklisting->is_favorited() ? 'Favorited' : 'Add to favorites' ); ?>
</a>