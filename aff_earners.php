/*** payd yith_affiliates Top Earners ***/
function aff_top_earners() {
global $wpdb;

  ob_start(); ?> 

	<!--display user details in table row -->
	<table class="aff_topearners">
	<tr>
	<th>S/N</th>
	<th>Name</th>
	<th>Payment Method</th>
	<th>Total Earnings</th>
	<th>Total Paid</th>
	</tr>
<?php

$users = $wpdb->get_results("SELECT user_id, earnings, paid FROM $wpdb->yith_affiliates WHERE earnings >= 1000 AND banned = 0 ORDER BY earnings DESC LIMIT 25");
$sn = 1;

foreach ( $users as $row ) 
{
	//assign users id to fetch customer details
	$affid = $row->user_id;
		
?>
	<tr>
	<td><?php echo $sn++;?> </td>
	<td> <?php echo get_user_meta( $affid, 'first_name', true ); ?> &nbsp; <?php echo get_user_meta( $affid, 'last_name', true ); ?> </td>
	<td> <?php echo get_user_meta( $affid, 'pay_method', true ); ?> </td>
	<td> <?php echo "₦". number_format($row->earnings, 2); ?> </td>
	<td> <?php echo "₦". number_format($row->paid, 2); ?> </td>
	</tr>
<?php } ?>
	</table>
  <?php	return ob_get_clean();
}
add_shortcode( 'aff_topearners', 'aff_top_earners' );

/*** payd yith_affiliates All Earners ***/
function aff_all_earners() {
global $wpdb;

  ob_start(); ?> 

	<!--display user details in table row -->
	<table class="aff_topearners">
	<tr>
	<th>S/N</th>
	<th>Name</th>
	<th>Total Earnings</th>
	<th>Total Paid</th>
	</tr>
<?php

$users = $wpdb->get_results("SELECT user_id, earnings, paid FROM $wpdb->yith_affiliates WHERE earnings >= 1000 AND banned = 0 ORDER BY earnings DESC");
$sn = 1;
$sum_earnings = 0;
$sum_paid = 0; 

foreach ( $users as $row ) 
{
	//assign users id to fetch customer details
	$affid = $row->user_id;
	$sum_earnings += $row->earnings;
	$sum_paid += $row->paid;
	
?>
	<tr>
	<td><?php echo $sn++;?> </td>
	<td> <?php echo get_user_meta( $affid, 'first_name', true ); ?> &nbsp; <?php echo get_user_meta( $affid, 'last_name', true ); ?> </td>
	<td> <?php echo "₦". number_format($row->earnings, 2); ?> </td>
	<td> <?php echo "₦". number_format($row->paid, 2); ?> </td>
	</tr>
<?php } ?>
	<tr>
	<td></td>
    <td><strong>TOTAL</strong></td>
	<td><strong><?php echo "₦". number_format($sum_earnings, 2); ?></strong></td>
	<td><strong><?php echo "₦". number_format($sum_paid, 2); ?></strong></td>
	</tr>
	</table>
  <?php	return ob_get_clean();
}
add_shortcode( 'aff_allearners', 'aff_all_earners' );
