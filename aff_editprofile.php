<?php

/*** Edit Affiliate User Details: Use Shortcode [aff_editprofile]***/
/*** This function allow you to have a page for users to change their passwords and payment details. ***/
function update_user_profile() {
global $wpdb;

  ob_start(); ?> 

<?php
 
/* Get user info. */
global $current_user, $wp_roles;

/* Load the registration file. */
/* If profile was saved, update profile. */
if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'update-user' ) {

    /* Update user password. */
    if ( !empty($_POST['pass1'] ) && !empty( $_POST['pass2'] ) ) {
        if ( $_POST['pass1'] == $_POST['pass2'] )
            wp_update_user( array( 'ID' => $current_user->ID, 'user_pass' => esc_attr( $_POST['pass1'] ) ) );
        else
            $error[] = __('The passwords you entered do not match.  Your password was not updated.', 'profile');
    }

    /* Update user information. */
    if ( !empty( $_POST['email'] ) ){
       if (!is_email(esc_attr( $_POST['email'] )))
           $error[] = __('The Email you entered is not valid.  please try again.', 'profile');
        else if (email_exists(esc_attr( $_POST['email'] )) != $current_user->id )
            $error[] = __('This email is already used by another user.  try a different one.', 'profile');
        else{
            wp_update_user( array ('ID' => $current_user->ID, 'user_email' => esc_attr( $_POST['email'] )));
        }
    }

    if ( !empty( $_POST['first-name'] ) )
        update_user_meta( $current_user->ID, 'first_name', esc_attr( $_POST['first-name'] ) );
    if ( !empty( $_POST['last-name'] ) )
        update_user_meta($current_user->ID, 'last_name', esc_attr( $_POST['last-name'] ) );
	if ( !empty( $_POST['pay-method'] ) )
        update_user_meta($current_user->ID, 'pay_method', esc_attr( $_POST['pay-method'] ) );
		if ( !empty( $_POST['tron-wallet'] ) )
        update_user_meta($current_user->ID, 'tron_wallet', esc_attr( $_POST['tron-wallet'] ) );
		if ( !empty( $_POST['bank-name'] ) )
        update_user_meta($current_user->ID, 'bank_name', esc_attr( $_POST['bank-name'] ) );
		if ( !empty( $_POST['bank-account'] ) )
        update_user_meta($current_user->ID, 'bank_account', esc_attr( $_POST['bank-account'] ) ); 
 
 
	echo "<br><b>Your profile changes have been saved.</b>";
}
//
 if ( !is_user_logged_in() ) : ?>
                    <p class="warning">
                        <?php _e('You must be logged in to edit your profile.', 'profile'); ?>
                    </p><!-- .warning -->
            <?php else : ?>
                <?php //if ( count($error) > 0 ) echo '<p class="error">' . implode("<br />", $error) . '</p>'; ?>
                <form method="post" id="adduser" action="<?php the_permalink(); ?>">
                    <p class="form-username">
                        <label for="first-name"><?php _e('First Name', 'profile'); ?></label>
                        <input class="text-input" name="first-name" type="text" id="first-name" value="<?php the_author_meta( 'first_name', $current_user->ID ); ?>" />
                    </p><!-- .form-username -->
                    <p class="form-username">
                        <label for="last-name"><?php _e('Last Name', 'profile'); ?></label>
                        <input class="text-input" name="last-name" type="text" id="last-name" value="<?php the_author_meta( 'last_name', $current_user->ID ); ?>" />
                    </p><!-- .form-username -->
                    <p class="form-username">
                        <label for="pay-method"><?php _e('Payment Method', 'profile'); ?></label>
                        <select class="text-input" name="pay-method" id="pay-method">
                        <option value="<?php the_author_meta( 'pay-method', $current_user->ID ); ?>" selected><?php the_author_meta( 'pay_method', $current_user->ID ); ?></option>
   						<option value="Bank Transfer">Bank Transfer</option>
                        <option value="Tron Wallet">Tron Wallet</option>
                        </select>
                    </p><!-- .form-username -->
					 <p class="form-username">
                        <label for="billing-bank"><?php _e('Bank', 'profile'); ?></label>
                        <input class="text-input" name="bank-name" type="text" id="bank-name" value="<?php the_author_meta( 'bank_name', $current_user->ID ); ?>" />
                    </p><!-- .form-username -->
                    <p class="form-username">
                        <label for="bank-account"><?php _e('Bank Account', 'profile'); ?></label>
                        <input class="text-input" name="bank-account" type="text" id="bank-account" value="<?php the_author_meta( 'bank_account', $current_user->ID ); ?>" />
                    </p><!-- .form-username -->
                    <p class="form-username">
                        <label for="tron-wallet"><?php _e('Tron Wallet', 'profile'); ?></label>
                        <input class="text-input" name="tron-wallet" type="text" id="tron-wallet" value="<?php the_author_meta( 'tron_wallet', $current_user->ID ); ?>" />
                    </p><!-- .form-username -->                                  
                  
                    <!--
					<p class="form-email">
                        <label for="email"><?php _e('E-mail *', 'profile'); ?></label>
                        <input class="text-input" name="email" type="text" id="email" value="<?php the_author_meta( 'user_email', $current_user->ID ); ?>" />
                    </p><!-- .form-email -->
                    <!--<b>Change Password (leave blank to keep old password.)</b>
					<p class="form-password">
                        <label for="pass1"><?php _e('Password *', 'profile'); ?> </label>
                        <input class="text-input" name="pass1" type="password" id="pass1" />
                    </p><!-- .form-password -->
                    <!--<p class="form-password">
                        <label for="pass2"><?php _e('Repeat Password *', 'profile'); ?></label>
                        <input class="text-input" name="pass2" type="password" id="pass2" />
                    </p><!-- .form-password -->
                   
                    <p class="form-submit">
                        <?php echo $referer; ?>
                        <input name="updateuser" type="submit" id="updateuser" class="submit button" value="<?php _e('Update', 'profile'); ?>" />
                        <?php wp_nonce_field( 'update-user' ) ?>
                        <input name="action" type="hidden" id="action" value="update-user" />
                    </p><!-- .form-submit -->
                </form><!-- #adduser -->
            <?php endif; ?>
 <?php	return ob_get_clean();
}
add_shortcode( 'aff_editprofile', 'update_user_profile' );
 ?>
