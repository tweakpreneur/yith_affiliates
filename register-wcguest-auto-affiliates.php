/***create user from Guest after payment and auto make Affiliate***/
function wc_register_guests( $order_id ) {
global $wpdb;
// get all the order data
$order = new WC_Order($order_id);

//get the user email from the order
$order_email = $order->billing_email;

// check if there are any users with the billing email as user or email
$email = email_exists( $order_email );
$user = username_exists( $order_email );

// if the UID is null, then it's a guest checkout
if( $user == false && $email == false ){

// random password with 12 chars
// $random_password = wp_generate_password();

$random_password = $order->billing_password;

// create new user with email as username & newly created pw
$user_id = wp_create_user( $order_email, $random_password, $order_email );

//WC guest customer identification
// update_user_meta( $user_id, 'guest', 'yes' );

//user's billing data
update_user_meta( $user_id, 'first_name', $order->billing_first_name );
update_user_meta( $user_id, 'last_name', $order->billing_last_name );
update_user_meta( $user_id, 'billing_city', $order->billing_city );
update_user_meta( $user_id, 'billing_state', $order->billing_state );
update_user_meta( $user_id, 'billing_country', $order->billing_country );
update_user_meta( $user_id, 'billing_email', $order->billing_email );
update_user_meta( $user_id, 'phone_number', $order->billing_phone );
update_user_meta( $user_id, 'billing_first_name', $order->billing_first_name );
update_user_meta( $user_id, 'billing_last_name', $order->billing_last_name );
// user's affiliate tron/Bank data
update_user_meta( $user_id, 'billing_paymethod', $order->billing_paymethod );
update_user_meta( $user_id, 'billing_tronwallet', $order->billing_tronwallet );
update_user_meta( $user_id, 'billing_bank_name', $order->billing_bank_name);
update_user_meta( $user_id, 'billing_bank_account', $order->billing_bank_account );

// user's shipping data
/** update_user_meta( $user_id, 'shipping_address_1', $order->shipping_address_1 );
update_user_meta( $user_id, 'shipping_address_2', $order->shipping_address_2 );
update_user_meta( $user_id, 'shipping_city', $order->shipping_city );
update_user_meta( $user_id, 'shipping_company', $order->shipping_company );
update_user_meta( $user_id, 'shipping_country', $order->shipping_country );
update_user_meta( $user_id, 'shipping_first_name', $order->shipping_first_name );
update_user_meta( $user_id, 'shipping_last_name', $order->shipping_last_name );
update_user_meta( $user_id, 'shipping_method', $order->shipping_method );
update_user_meta( $user_id, 'shipping_postcode', $order->shipping_postcode );
update_user_meta( $user_id, 'shipping_state', $order->shipping_state );**/

//create affiliate profile for newly created user
$customer_id = $user_id;

$aff_uid = $wpdb->get_var("SELECT user_id FROM $wpdb->yith_affiliates WHERE user_id = $customer_id ");
$aff = $wpdb->get_var("SELECT enabled FROM $wpdb->yith_affiliates WHERE user_id = $customer_id ");

if (! $aff_uid) {

//if ( $aff != $customer_id ) {

$wpdb->insert(
$wpdb->yith_affiliates, array(
'user_id' => $customer_id,
'enabled' => 1,
'rate' => NULL,
'earnings' => 0,
'refunds' => 0,
'paid' => 0,
'click' => 0,
'banned' => 0,
'conversion' => 0,
'payment_email' => '',
'token' => $customer_id,
//'token' => $this->get_default_user_token( $customer_id )
) );
$aff_id = $wpdb->insert_id;
}
else if ( $aff == 0 ) {
$wpdb->update( $wpdb->yith_affiliates, array ('enabled'=> 1), array('user_id' => $aff_uid) );

}

/*$wpdb->show_errors();
$wpdb->print_error();
*/
/*** automatically complete orders ****/
 $order->update_status('completed');

// link past orders to this newly created customer
wc_update_new_customer_past_orders( $user_id );
}

}
//add this newly created function after order placed
add_action( 'woocommerce_order_status_processing', 'wc_register_guests' );

//add_action( 'woocommerce_thankyou', 'wc_register_guests', 10, 1 );
//add_action( 'woocommerce_checkout_order_processed', 'wc_register_guests' );
//add_action( 'woocommerce_payment_complete_order_status', 'wc_register_guests' );

?>
