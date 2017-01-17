<?php
	
	if(!class_exists('CHAnalytics_Tracking')) {
		
		Class CHAnalytics_Tracking {
			
			public function __construct() {
				
				add_action('wp_enqueue_scripts', array( $this, 'enqueue_ga_vanilla' ));
				add_action('wp_head', array( $this, 'create_pageview_script' ));
				
			}
			
			public function enqueue_ga_vanilla() {
				wp_enqueue_script('ga_vanilla', plugin_dir_url( __FILE__ ) . 'js/ga-vanilla.js');
			}
			
			public function create_pageview_script() {
// 				if(is_admin_bar_showing()) return;
				global $wp;
				$CHAnalytics_SetOrderNotes = new CHAnalytics_OrderNotes();
				
				ob_start();
				?>
				<script>
					ga('create', '<?php echo get_option('ch_tracking_code') ?>', 'auto');
					<?php	
						if(function_exists('is_wc_endpoint_url')):
							if(is_wc_endpoint_url( 'order-received' )):
								if(!$CHAnalytics_SetOrderNotes->is_tracked_by_CH($wp->query_vars['order-received'])):
									$this->TrackOrder();
								endif;
							endif;
						endif;
					?>
					ga('send', 'pageview');
				
				</script>
				<?php
				echo ob_get_clean();
			}
			
			public function TrackOrder() {
			?>
				ga('require', 'ec');
				<?php
					
				global $wp;
				$order_id = $wp->query_vars['order-received'];
				$order = new WC_Order( $order_id );
				
				$item_category = '';
				
				$order_items = $order->get_items();
					foreach($order_items as $order_item):
	// 					print_r($order_item);
						if($order_item['variation_id'] !== '0'):
							$item_id = $order_item['variation_id'];
						else:
							$item_id = $order_item['product_id'];
						endif;
						
						// get all product cats for the current post
						$categories = get_the_terms( $item_id, 'product_cat' ); 
						
						// wrapper to hide any errors from top level categories or products without category
						if ( $categories && ! is_wp_error( $categories ) ) : 
						
						    // loop through each cat
						    foreach($categories as $category) :
						      // get the children (if any) of the current cat
						      $children = get_categories( array ('taxonomy' => 'product_cat', 'parent' => $category->term_id ));
						
						      if ( count($children) == 0 ) {
						          // if no children, then set the category name.
						          $item_category = $category->name;
						      }
						    endforeach;
			
						endif;
						
						$item_price = $order_item['item_meta']['_line_tax'][0] + $order_item['item_meta']['_line_total'][0];
						$order_qty = $order_item['item_meta']['_qty'][0];
						
						?>
						ga('ec:addProduct', {
						  'id': '<?php echo $item_id; ?>',
						  'name': '<?php echo $order_item['name']; ?>',
						  'category': '<?php echo $item_category; ?>',
						  'price': '<?php echo $item_price; ?>',
						  'quantity': '<?php echo $order_qty; ?>'
						});
						<?php
					endforeach;
				
				$order_coupons = $order->get_used_coupons();
				$all_order_coupons = '';
				if(count($order_coupons) !== 0):
					foreach($order_coupons as $order_coupon) {
						$all_order_coupons .= $order_coupon . ', ';
					}
				endif;
				
				$order_total = $order->get_subtotal() + $order->get_cart_tax() + $order->get_total_shipping();
				$order_total = round($order_total,2);
				$order_total = number_format((float)$order_total, 2, '.', '');
				
				?>
						ga('ec:setAction', 'purchase', {
						  'id': '<?php echo $order->get_order_number(); ?>',
						  'affiliation': '',
						  'revenue': '<?php echo $order_total; ?>',
						  'tax': '<?php echo $order->get_cart_tax(); ?>',
						  'shipping': '<?php echo $order->get_total_shipping(); ?>',
						  'coupon': '<?php echo $all_order_coupons; ?>'
						});
				<?php
				$CHAnalytics_SetOrderNotes = new CHAnalytics_OrderNotes();
				$CHAnalytics_SetOrderNotes->AddTrackedNote($order);
			}
			
		}
		
	}
