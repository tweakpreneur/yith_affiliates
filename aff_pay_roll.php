/***  yith_affiliates Withdrawal Payroll Details: Use shortcode [aff_payroll] to list those who have made withdrawal requests***/ 
/*** Admin only will see and can click on button to confirm they have been paid and the amount will automatically be deducted from their pending earnings and added to their paid earnings. ***/
function aff_pay_roll() {
global $wpdb;

  ob_start(); ?> 

<?php
//get users with unpaid earnings
$my_id = get_current_user_id();
$users = $wpdb->get_results("SELECT ID, affiliate_id, status, amount, created_at FROM $wpdb->yith_payments WHERE status != 'completed' ");
$sn = 1;
?>
	<!--display user details in table row -->
	<style>.aff_payroll .row-<?php echo esc_attr( $my_id ) ?> {color: #0000ff; border: 1px solid #0000ff !important;}</style>
<table class='aff_payroll'>
	<tr>
	<th>S/N</th>
	<th>Name</th>
	<th>To Pay</th>
<th>Payment Method</th>
	
	 <?php if( current_user_can( 'edit_users' ) ) { ?>
	<th>Bank</th>
 <th>Account</th>
 <th>Tron Wallet</th>
	<th>Payment ID</th>
	<th>Pay User</th>
	 <?php } ?>
	
	</tr>
<?php

foreach ( $users as $row ) 
{
	//assign users id to fetch customer details
	$id = $row->ID;
	$affid = $wpdb->get_var("SELECT user_id FROM $wpdb->yith_affiliates WHERE ID = $row->affiliate_id ");
	
	//$affx = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->usermeta WHERE user_id = $affid ");
	//$aff = $wpdb->get_row("SELECT meta_value FROM $wpdb->usermeta WHERE user_id = $affid AND meta_key = 'first_name' ");
	//$fname = $aff->meta_value;
	//$lname = $affx[2]->meta_value;
	
	if ( current_user_can( 'edit_users' )  && $_REQUEST['confirm-pay'] == $id  ) {
	//update payment with status pending to completed
	$wpdb->update( $wpdb->yith_payments, array ('status' => 'completed', 'completed_at' => current_time( 'mysql' )), array('ID' => $id) );	
		//wp_redirect( esc_url_raw( remove_query_arg( 'pay_affiliate' ) ) ); die();
	}

	?>
		<tr class="row-<?php echo esc_attr( $affid ) ?>">
	<td><?php echo $sn++;?> </td>
	<td> <?php echo get_user_meta( $affid, 'first_name', true ); ?> &nbsp; <?php echo get_user_meta( $affid, 'last_name', true ); ?> </td>
	<td> <?php echo "₦". number_format($row->amount); ?> </td>
	<td> <?php echo get_user_meta( $affid, 'pay_method', true ); ?> </td>
	 
 <?php if( current_user_can( 'edit_users' ) ) { ?>	 
 <td> <?php echo get_user_meta( $affid, 'bank_name', true ); ?> </td>
	<td> <?php echo get_user_meta( $affid, 'bank_account', true ); ?> </td>
 <td> <?php echo get_user_meta( $affid, 'tron_wallet', true ); ?> </td>
	<td> <?php echo $id; ?> </td>
	<td> <a  class='pay-user' href="<?php get_permalink() ?>?confirm-pay=<?php echo esc_attr( $id ) ?>">PAY</a> <br /> </td>	
<?php } ?>
		</tr>
	<?php } // end loop ?> 

	</table>
  <?php	return ob_get_clean();
}
add_shortcode( 'aff_payroll', 'aff_pay_roll' );
