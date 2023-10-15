  <?php
/*** LifeEarners yith_affiliates Stats: Use shortcode [aff_astats] ***/
/*** This shortcode shows total "active" affiliates and the site-wide Affiliate earnings, how much have been withdrawn and how much yet to be withdrawn. ***/ 
function aff_astats() {
global $wpdb;

  ob_start(); ?> 

<?php

$stats = $wpdb->get_results("SELECT earnings, paid FROM $wpdb->yith_affiliates WHERE earnings >= 1000 AND banned = 0 ORDER BY earnings DESC");

$sum_earnings = 0;
$sum_paid = 0; 
$sn = 0;
	
	
	foreach ( $stats as $row ) 
{
	$sum_earnings += $row->earnings;
	$sum_paid += $row->paid; 
		$sn++;
}	
?>
		<table style="text-align: center !important; border: 1px solid #ddd;">
	<tr>
	<td><strong>Active Affiliates: <br> <?php echo $sn; ?> </strong></td>
		</tr>
		</table> 
		
		<table style="text-align: center !important; border: 1px solid #ddd;">
		  	<tr> <td colspan="3"><strong>Affiliate Earnings Summary</strong></td> 
			</tr> 
			<tr>
	<td><strong>Total: <br> <?php echo "₦". number_format($sum_earnings, 2); ?></strong></td>
	<td><strong>Paid: <br> <?php echo "₦". number_format($sum_paid, 2); ?></strong></td> 
				<td><strong>Balance: <br> <?php echo "₦".number_format($sum_earnings - $sum_paid, 2); ?> </strong></td>			
	</tr>
	</table>

  <?php	return ob_get_clean();
}
add_shortcode( 'aff_astats', 'aff_astats' );
?>
