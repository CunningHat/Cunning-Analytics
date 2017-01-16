<?php
	
	function get_woocommerce_order_notes( $order_id ) {
		remove_filter( 'comments_clauses', array( 'WC_Comments', 'exclude_order_comments' ) );
		$comments = get_comments( array(
			'post_id' => $order_id,
			'orderby' => 'comment_ID',
			'order'   => 'DESC',
			'approve' => 'approve',
			'type'    => 'order_note',
		) );
		$notes = wp_list_pluck( $comments, 'comment_content' );
		add_filter( 'comments_clauses', array( 'WC_Comments', 'exclude_order_comments' ) );
		return $notes;
	}