<?php
/*** payd yith_affiliates Request Withdrawal: uses shortcode [aff_withdraw]***/

function aff_request_pay() {
global $wpdb;

  ob_start(); ?> 

<?php

//check if user is logged in
				if ( is_user_logged_in() ) {

$affid = get_current_user_id();
$aff_email = get_user_meta( $affid, 'email', true );
$aff = $wpdb->get_row("SELECT ID, earnings, paid FROM $wpdb->yith_affiliates WHERE user_id = $affid AND banned = 0 ");

$withdraw_limit = 3000; // set minimum withdrawal for affiliates here
	//retrieving balance
$s_affid = $aff->ID;
$s_earnings = $aff->earnings;
$s_paid = $aff->paid;
$s_bal = $s_earnings - $s_paid;
?>

<form action="<?php admin_url( 'admin-post.php' ); ?>" method="post">
	<label><b> <?php echo 'Account Balance: ₦' . number_format($s_bal) ; ?> </b></label> <br /> <br />
 	<label><b> <?php echo 'Request Payout: (min. ₦' . number_format($withdraw_limit) . ')'; ?> </b></label> <br />
    <input type="hidden" name="action" value="submit_withdrawal" />
	<input type="text" name="payment" value="<?php echo esc_attr( $s_bal ) ?>" class="form-control"/>
    <input type="submit" name="make_payment" value="Withdraw" class="form-control"/>
</form>
 	
 <?php 
 }
 
 if ( isset( $_POST['make_payment'] ) ) {
	 $amt = sanitize_text_field( $_POST['payment'] );		
// $amt = $_POST['payment'];
	
	if ( $amt < $withdraw_limit || $s_bal < $withdraw_limit ) {
		echo "<span style='color:#ff0000'>Insufficient funds!</span> You need to have minimum balance of ₦" . number_format($withdraw_limit) . "&nbsp;to request payout.<br />";
	}
	else if ( $amt > $s_bal ) {
		echo 'You do not have enough funds to request that amount.';		
	}
	else {
	//submit withdrawal request 	
 $wpdb->insert( 
$wpdb->yith_payments, array(
							 'affiliate_id' => $s_affid,                     // Affiliate id (int)<br />
		                     'payment_email' => $aff_email,             // Payment email (string)<br />
		                     'status' => 'pending',                   // Status (valid payment status on-hold/pending/completed)<br />
		                     'amount' => $amt,                           // Amount (double)<br />
		                     'created_at' => current_time( 'mysql' ), // Date of creation (mysql date format; default to current server time)<br />
		                     'completed_at' => 'NULL',                    // Date of complete (mysql date format; default to null)<br />
		                    // 'transaction_key' => '$wpdb->insert_id;' // Payment transaction key (string; default null)<br />

							) );
	//deduct withdrawal amount and add to paid
	$wpdb->update( $wpdb->yith_affiliates, array ('paid'=> $s_paid + $amt), array('user_id' => $affid) );
	echo 'Withdrawal submitted. You have been added to the payroll.';
	//wp_redirect( get_permalink( $post->post_parent ) ); die; 
	 //wp_redirect( get_permalink( 32 ) );
    //    die;
   }
		}
				
?>
<br /> <br />
<p> <?php echo "<span style='color:#ff0000'>Note:</span> Ensure that your name in your <a href='/affiliate/withdrawal-settings/'>payment profile</a> on this website is the same name of the account number you submitted for withdrawal, or you may not receive your payment."; ?> </p> <br />
 	
  <?php	return ob_get_clean();
}
add_action( 'admin_post_submit_withdrawal', 'aff_request_pay' );
add_action( 'admin_post_nopriv_submit_withdrawal', 'aff_request_pay' );
add_shortcode( 'aff_withdraw', 'aff_request_pay' );
