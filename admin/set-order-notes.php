<?php
	
	Class CHAnalytics_OrderNotes {
		
		public function __construct() {
			add_action('woocommerce_payment_complete', array($this, 'AddTrackedNote'));
		}
		
		public function AddTrackedNote($order) {
			
			if($this->is_tracked_by_CH($order->id)) :
				$order->add_order_note( 'tracked_by_CHAnalytics', 0, false );
			endif;
		}
		
		public function is_tracked_by_CH($order_id) {
			$AllOrderNotes = $this->CHGetOrderNotes($order_id);
			
			if(!in_array('tracked_by_CHAnalytics', $AllOrderNotes)) return true;
		}
		
		public function CHGetOrderNotes( $order_id ) {
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
		
	}