<?php
/*** payd yith_affiliates Affiliate Commissions... use shortcode [aff_commissions]***/
function aff_commissions() {
global $wpdb;

  ob_start(); ?> 

	<!--display user details in table row -->
	
	<div class="yith-wcaf-section yith-wcaf-dashboard-commissions">
		

<div class="dashboard-title">
	<h3>Commissions</h3>
</div>
		<table id="yith_wcaf_commissions_custom_table" class="shop_table shop_table_responsive yith-wcaf-table">
	<tr>
	<th class="column-id">S/N</th>
	<th class="column-status">Name</th>
	<th class="column-amount">Commission</th>	
	<th class="column-product">Remark</th>
	<th class="column-created_at">Date</th>
	</tr>
<?php

//check if user is logged in
				if ( is_user_logged_in() ) {

//get current user ID
					$uid = get_current_user_id();
$affid = $wpdb->get_var("SELECT ID FROM $wpdb->yith_affiliates WHERE user_id = $uid ");				

$my_affs = $wpdb->get_results("SELECT affiliate_id, amount, order_id, line_item_id, rate, product_name, created_at FROM $wpdb->yith_commissions WHERE affiliate_id = $affid AND status ='pending' ORDER BY ID DESC");
$sn = 1;
$sum_earnings = 0;

//Parameters: ID, order_id, line_item_id(ref), affiliate_id, rate, amount(commision), refunds, status, created_at, last_edit, product_id, product_name, line-total(price). 
					
foreach ( $my_affs as $row ) 
		{
	//assign data to fetch affiliate details
	$date = date_create($row->created_at);
	//fetch referrals from order details
	$order_id = $row->order_id; $order = new WC_Order( $order_id );
$ref_id = $order->user_id; 
	//$payd = $order->payment_method_title; 
	//$user_id = $order->get_user_id($order_id); 
	$sum_earnings += $row->amount;
?>
	<tr id="<?php echo $row->line_item_id ?>">
	<td class="column-id"><?php echo $sn++;?> </td>
	<td class="column-status"> <?php echo get_user_meta( $ref_id, 'first_name', true ); ?> </td>
	<td class="column-amount"> <?php echo "₦". number_format($row->amount, 0); ?> </td>
	<td class="column-product"> <?php echo number_format($row->rate, 0) . "% of " . $row->product_name; ?> </td>
		<td class="column-created_at"> <?php echo date_format($date, "F j, Y"); ?> </td>
	</tr>
<?php 	} 
	}
?>
	<tr>
    <td colspan="2"><strong>TOTAL:</strong></td>
	<td colspan="3" style="text-align: right"><strong><?php echo "₦". number_format($sum_earnings, 2); ?></strong></td>
	</tr>
	</table> 
</div>
  <?php	return ob_get_clean();
}
add_shortcode( 'aff_commissions', 'aff_commissions' );

?>
