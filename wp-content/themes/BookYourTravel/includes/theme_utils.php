<?php

function get_posts_children($parent_id, $args){

	$args['post_parent'] = $parent_id;
	$children = array();
    // grab the posts children
    $posts = get_posts( $args );
    // now grab the grand children
    foreach( $posts as $child ){
        // recursion!! hurrah
        $gchildren = get_posts_children($child->ID, $args);
        // merge the grand children into the children array
        if( !empty($gchildren) ) {
            $children = array_merge($children, $gchildren);
        }
    }
    // merge in the direct descendants we found earlier
    $children = array_merge($children,$posts);
    return $children;
}

function strip_tags_and_shorten($content, $character_count) {
	$content = wp_strip_all_tags($content);
	return (mb_strlen($content) > $character_count) ? mb_substr($content, 0, $character_count).' ' : $content;
	// return implode(' ', array_slice(explode(' ', $content), 0, $words));
}

function get_current_language_page_id($id){
	if(function_exists('icl_object_id')) {
		return icl_object_id($id,'page',true);
	} else {
		return $id;
	}
}

function get_default_language_post_id($id, $post_type) {
	global $sitepress;
	if ($sitepress) {
		$default_language = $sitepress->get_default_language();
		if(function_exists('icl_object_id')) {
			return icl_object_id($id, $post_type, true, $default_language);
		} else {
			return $id;
		}
	}
	return $id;	
}

function get_default_language() {
	global $sitepress;
	if ($sitepress) {
		return $sitepress->get_default_language();
	} else if (defined(WPLANG)) {
		return WPLANG;
	} else
		return "en";	
}

function table_exists($table_name) {
	global $wpdb;
	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
		return false;
	}
	return true;
}

/*
 * Contact form js
 */
function byt_contact_form_js() {

	global $business_address_longitude, $business_address_latitude;

	wp_register_script('google-maps','//maps.google.com/maps/api/js?sensor=false',	'jquery','1.0',true);
	wp_enqueue_script( 'google-maps' );	
	wp_register_script('infobox', get_byt_file_uri('/js/infobox.js'),'jquery','1.0',true);
	wp_enqueue_script( 'infobox' );
	wp_register_script(	'contact', get_byt_file_uri('/js/contact.js'), 'jquery', '1.0',true);
	wp_enqueue_script( 'contact' );

	/* Contact form related stuff */
	$business_address_latitude =  of_get_option('business_address_latitude', '');
	$business_address_longitude =  of_get_option('business_address_longitude', '');
	$contact_company_name = trim(of_get_option('contact_company_name', ''));
	$contact_phone_number = trim(of_get_option('contact_phone_number', ''));
	$contact_address_street = trim(of_get_option('contact_address_street', ''));
	$contact_address_city = trim(of_get_option('contact_address_city', ''));
	$contact_address_country = trim(of_get_option('contact_address_country', ''));	 
	$company_address = '<strong>' . $contact_company_name . '</strong>';
	$company_address .= (!empty($contact_address_street) ? $contact_address_street : '') . ', ';
	$company_address .= (!empty($contact_address_city) ? $contact_address_city : '') . ', ';
	$company_address .= (!empty($contact_address_country) ? $contact_address_country : '');
	$company_address = rtrim(trim($company_address), ',');

	if (!empty($business_address_longitude) && !empty($business_address_latitude)) {
	?>	 
	<script>
		window.business_address_latitude = '<?php echo $business_address_latitude; ?>';
		window.business_address_longitude = '<?php echo $business_address_longitude; ?>';
		window.company_address = '<?php echo $company_address; ?>';
	</script>
	<?php
	}
}

/*
 * Breadcrumbs
 */
function byt_breadcrumbs() {
	if (is_home()) {}
	else {
		echo '<!--breadcrumbs--><nav role="navigation" class="breadcrumbs clearfix">';
		echo '<ul>';
		echo '<li><a href="' . home_url() . '" title="' . __('Home', 'bookyourtravel') . '">' . __('Home', 'bookyourtravel') . '</a></li>';
		if (is_category()) {
			echo "<li>";
			the_category('</li><li>');
			echo "</li>";
		} elseif (is_page() || is_single()) {
			echo "<li>";
			echo the_title();
			echo "</li>";
		} elseif (is_404()) {
			echo "<li>" . __('Error 404 - Page not found', 'bookyourtravel') . "</li>";
		} elseif (is_search()) {
			echo "<li>";
			echo __('Search results for: ', 'bookyourtravel');
			echo '"<em>';
			echo get_search_query();
			echo '</em>"';
			echo "</li>";
		} else if (is_post_type_archive('accommodation')) {
			echo "<li>";
			echo __('Accommodations', 'bookyourtravel');
			echo "</li>";
		} else if (is_post_type_archive('location')) {
			echo "<li>";
			echo __('Locations', 'bookyourtravel');
			echo "</li>";
		}
		
		echo '</ul>';
		echo '</nav><!--//breadcrumbs-->';
	}
}

/**
  * Helper function: string contains string
  */
function byt_string_contains($haystack, $needle) {
	if (strpos($haystack, $needle) !== FALSE)
		return true;
	else
		return false;
}

/**
  * Helper function: get current page url
 */
function current_page_url() {
	$pageURL = 'http';
	if ( isset( $_SERVER["HTTPS"] ) && strtolower($_SERVER["HTTPS"]) == "on") {$pageURL .= "s";}
		$pageURL .= "://";
	if ( isset( $_SERVER["SERVER_PORT"] )  && $_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	return $pageURL;
}

/*
 * Pager
 */
function byt_display_pager($max_num_pages) {

	$pattern = '#(www\.|https?:\/\/){1}[a-zA-Z0-9\-]{2,254}\.[a-zA-Z0-9]{2,20}[a-zA-Z0-9.?&=_/]*#i';

	$big = 999999999; // need an unlikely integer
	$pager_links = paginate_links( array(
		'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
		'format' => '?paged=%#%',
		'current' => max( 1, get_query_var('paged') ),
		'total' => $max_num_pages,
		'prev_text'    => __('&lt;', 'bookyourtravel'),
		'next_text'    => __('&gt;', 'bookyourtravel'),
		'type'		   => 'array'
	) );
	$count_links = count($pager_links);
	if ($count_links > 0) {
	
		$first_link = $pager_links[0];
		$last_link = $first_link;
		preg_match_all($pattern, $first_link, $matches, PREG_PATTERN_ORDER);
		echo '<span><a href="' . get_pagenum_link(1) . '">' . __('First page', 'bookyourtravel') . '</a></span>';
		for ($i=0; $i<$count_links; $i++) {
			$pager_link = $pager_links[$i];
			if (!byt_string_contains($pager_link, 'current'))
				echo '<span>' . $pager_link . '</span>';
			else
				echo $pager_link;
			$last_link = $pager_link;
		}
		preg_match_all($pattern, $last_link, $matches, PREG_PATTERN_ORDER);
		echo '<span><a href="' . get_pagenum_link($max_num_pages) . '">' . __('Last page', 'bookyourtravel') . '</a></span>';
	}
}

/*-----------------------------------------------------------------------------------*/
/*	Custom comments template
/*-----------------------------------------------------------------------------------*/
function byt_comment($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment; 
   $comment_class = comment_class('clearfix', null, null, false);
   ?>							
	<!--single comment-->
	<article <?php echo $comment_class; ?> id="article-comment-<?php comment_ID() ?>">
		<div class="third">
			<figure><?php echo get_avatar( $comment->comment_author_email, 70 ); ?></figure>
			<address>
				<span><?php echo get_comment_author_link(); ?></span><br />
				<?php the_time('F j, Y'); ?>
			</address>
			<div class="comment-meta commentmetadata"><?php edit_comment_link(__('(Edit)', 'bookyourtravel'),'  ','') ?></div>
		</div>
		<?php if ($comment->comment_approved == '0') : ?>
		<em><?php _e('Your comment is awaiting moderation.', 'bookyourtravel') ?></em>
		<?php endif; ?>
		<div class="comment-content"><?php echo get_comment_text(); ?></div>
<?php 
	$reply_link = get_comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth'])));
	$reply_link = str_replace('comment-reply-link', 'comment-reply-link reply', $reply_link);
	$reply_link = str_replace('comment-reply-login', 'comment-reply-login reply', $reply_link);
?>		
		<?php echo $reply_link; ?>
	</article>
	<!--//single comment-->
<?php
}

/**
 * Email sent to user during registration process requiring confirmation if option enabled in Theme settings
 */
function byt_activation_notification( $user_id ){

	$user = get_userdata( $user_id );
	
	if( !$user  ) return false;
	
	$user_activation_key = get_user_meta($user_id, 'user_activation_key', true);
	
	if (empty($user_activation_key))
		return false;
	
	$register_page_url_id = get_current_language_page_id(of_get_option('register_page_url', ''));
	$register_page_url = get_permalink($register_page_url_id);
	if (!$register_page_url)
		$register_page_url = get_home_url() . '/wp-login.php';
	
	$activation_url = add_query_arg( 
		array( 
			'action' => 'activate',
			'user_id' => $user->ID,
			'activation_key' => $user_activation_key
		), 
		$register_page_url
	);
	
	$subject = get_bloginfo( 'name' ) . __( ' - User Activation ', 'bookyourtravel' );
	$body = __( 'To activate your user account, please click the activation link below: ', 'bookyourtravel' );
	$body .= "\r\n";
	$body .= $activation_url;

	$admin_email = get_option( 'admin_email' );
	
	$headers   = array();
	$headers[] = "MIME-Version: 1.0";
	$headers[] = "Content-type: text/plain; charset=utf-8";
	$headers[] = "From: " . get_bloginfo( 'name' ) . " <" . $admin_email . ">";
	$headers[] = "Reply-To: " . get_bloginfo( 'name' ) . " <" . $admin_email . ">";
	$headers[] = "X-Mailer: PHP/".phpversion();
	
	if( wp_mail( $user->user_email, $subject, $body, $headers ) ) {
		return true;
	} else {
		return false;
	}

}

/**
 * Activate user if option enabled in Theme settings
 * 
 * @param  object $user
 * @param  string $activation_key
 * @return bool
 */
function byt_activate_user( $user_id, $activation_key ){
	$user = get_userdata( $user_id );
	$user_activation_key = get_user_meta($user_id, 'user_activation_key', true);

	if ( $user && !empty($user_activation_key) && $user_activation_key === $activation_key ) {
		$userdata = array(
			'ID' => $user->ID,
			'role' => get_option('default_role')
		);

		wp_update_user( $userdata );
		delete_user_meta( $user_id, 'user_activation_key' );
		
		return true;
	} else{
		return false;
	}
}

/**
 * Notify user about successful password reset if option enabled in Theme settings
 * 
 * @param  object $user
 * @param  string $activation_key
 * @return bool
 */
function byt_newpassword_notification( $user_id, $new_password ){

	$user = get_userdata( $user_id );
	if( !$user || !$new_password ) return false;

	$subject = get_bloginfo( 'name' ) . __( ' - New Password ', 'bookyourtravel' );
	$body = __( 'Your password was successfully reset. ', 'bookyourtravel' );
	$body .= "\r\n";
	$body .= "\r\n";
	$body .= __( 'Your new password is:', 'bookyourtravel' );
	$body .= ' ' . $new_password;

	$admin_email = get_option( 'admin_email' );
	
	$headers   = array();
	$headers[] = "MIME-Version: 1.0";
	$headers[] = "Content-type: text/plain; charset=utf-8";
	$headers[] = "From: " . get_bloginfo( 'name' ) . " <" . $admin_email . ">";
	$headers[] = "Reply-To: " . get_bloginfo( 'name' ) . " <" . $admin_email . ">";
	$headers[] = "X-Mailer: PHP/".phpversion();
	
	if( mail( $user->user_email, $subject, $body, implode( "\r\n", $headers ), '-f ' . $admin_email ) ){
		return true;
	} else {
		return false;
	}
}

/**
 * Send reset password notification if option enabled in Theme settings
 * 
 * @param  int $user_id 
 * @return bool
 */
function byt_resetpassword_notification( $user_id ){

	$user = get_userdata( $user_id );
	if( !$user || !$user->user_resetpassword_key ) return false;

	$override_wp_login = of_get_option('override_wp_login', 0);
	$reset_password_page_url_id = get_current_language_page_id(of_get_option('reset_password_page_url', ''));
	$reset_password_page_url = get_permalink($reset_password_page_url_id);
	if (!$reset_password_page_url || !$override_wp_login)
		$reset_password_page_url = get_home_url() . '/wp-login.php';
	
	$admin_email = get_option( 'admin_email' );
	
	$resetpassword_url = add_query_arg( 
		array( 
			'action' => 'resetpassword',
			'user_id' => $user->ID,
			'resetpassword_key' => $user->user_resetpassword_key
		), 
		$reset_password_page_url
	);

	$subject = get_bloginfo( 'name' ) . __( ' - Reset Password ', 'bookyourtravel' );
	$body = __( 'To reset your password please go to the following url: ', 'bookyourtravel' );
	$body .= "\r\n";
	$body .= $resetpassword_url;
	$body .= "\r\n";
	$body .= "\r\n";
	$body .= __( 'This link will remain valid for the next 24 hours.', 'bookyourtravel' );
	$body .= __( 'In case you did not request a password reset, please ignore this email.', 'bookyourtravel' );

	$headers   = array();
	$headers[] = "MIME-Version: 1.0";
	$headers[] = "Content-type: text/plain; charset=utf-8";
	$headers[] = "From: " . get_bloginfo( 'name' ) . " <" . $admin_email . ">";
	$headers[] = "Reply-To: " . get_bloginfo( 'name' ) . " <" . $admin_email . ">";
	$headers[] = "X-Mailer: PHP/".phpversion();
	
	if( mail( $user->user_email, $subject, $body, implode( "\r\n", $headers ), '-f ' . $admin_email ) ){
		return true;
	} else {
		return false;
	}
}

/**
 * Reset password
 * 
 * @param  int $user_id
 * @param  str $resetpassword_key
 * @return str/false New password or false
 */
function byt_resetpassword( $user_id, $resetpassword_key ){
	$user = get_userdata( $user_id );

	if( 
		$user && 
		$user->user_resetpassword_key && 
		$user->user_resetpassword_key === $resetpassword_key 
	){
		// check reset password time
		if(
			!$user->user_resetpassword_datetime ||
			strtotime( $user->user_resetpassword_datetime ) < time() - ( 24 * 60 * 60 )
		) return false;

		// reset password
		$userdata = array(
			'ID' => $user->ID,
			'user_pass' => wp_generate_password( 8, false )
		);

		wp_update_user( $userdata );
		delete_user_meta( $user->ID, 'user_resetpassword_key' );
		
		return $userdata['user_pass'];
	} else{
		return false;
	}
}

function get_byt_file_path($relative_path_to_file) {
	if (is_child_theme()) {
		if (file_exists( get_stylesheet_directory() . $relative_path_to_file ) )
			return get_stylesheet_directory() . $relative_path_to_file;
		else
			return get_template_directory() . $relative_path_to_file;
	}
	return get_template_directory() . $relative_path_to_file;
}


function get_byt_file_uri($relative_path_to_file) {
	if (is_child_theme()) {
		if (file_exists( get_stylesheet_directory() . $relative_path_to_file ) )
			return get_stylesheet_directory_uri() . $relative_path_to_file;
		else
			return get_template_directory_uri() . $relative_path_to_file;
	}
	return get_template_directory_uri() . $relative_path_to_file;
}

function bookyourtravel_create_currencies_tables($installed_version) {

	if ($installed_version != BOOKYOURTRAVEL_VERSION) {
		global $wpdb;
		
		// we do not execute sql directly
		// we are calling dbDelta which cant migrate database
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');	

		$table_name = BOOKYOURTRAVEL_CURRENCIES_TABLE;
		$sql = "CREATE TABLE " . $table_name . " (
					Id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
					currency_code varchar(10) NOT NULL,
					currency_label varchar(255) NOT NULL,
					currency_symbol varchar(10) NULL,
					PRIMARY KEY  (Id)
				);";

		dbDelta($sql);
		
		$table_name = BOOKYOURTRAVEL_CURRENCIES_TABLE;
		$sql = "SELECT COUNT(*) cnt FROM $table_name";
		$cnt = $wpdb->get_var($sql);
		if ($cnt == 0) {
		
			$sql = "INSERT INTO $table_name (currency_code, currency_label, currency_symbol) VALUES ('aed','united arab emirates dirham', '');
					INSERT INTO $table_name (currency_code, currency_label, currency_symbol) VALUES ('ars','argentina peso', '');
					INSERT INTO $table_name (currency_code, currency_label, currency_symbol) VALUES ('aud','australia dollar', '');
					INSERT INTO $table_name (currency_code, currency_label, currency_symbol) VALUES ('bgn','bulgaria lev', '');
					INSERT INTO $table_name (currency_code, currency_label, currency_symbol) VALUES ('bob','bolivia boliviano', '');
					INSERT INTO $table_name (currency_code, currency_label, currency_symbol) VALUES ('brl','brazil real', '');
					INSERT INTO $table_name (currency_code, currency_label, currency_symbol) VALUES ('cad','canada dollar', '');
					INSERT INTO $table_name (currency_code, currency_label, currency_symbol) VALUES ('chf','switzerland franc', '');
					INSERT INTO $table_name (currency_code, currency_label, currency_symbol) VALUES ('clp','chile peso', '');
					INSERT INTO $table_name (currency_code, currency_label, currency_symbol) VALUES ('cny','china yuan renminbi', '');
					INSERT INTO $table_name (currency_code, currency_label, currency_symbol) VALUES ('czk','czech republic koruna', '');
					INSERT INTO $table_name (currency_code, currency_label, currency_symbol) VALUES ('dkk','denmark krone', '');
					INSERT INTO $table_name (currency_code, currency_label, currency_symbol) VALUES ('egp','egypt pound', '');
					INSERT INTO $table_name (currency_code, currency_label, currency_symbol) VALUES ('eur','euro', '€');
					INSERT INTO $table_name (currency_code, currency_label, currency_symbol) VALUES ('gbp','pound','£');
					INSERT INTO $table_name (currency_code, currency_label, currency_symbol) VALUES ('hkd','hong kong dollar', '');
					INSERT INTO $table_name (currency_code, currency_label, currency_symbol) VALUES ('hrk','croatia kuna', '');
					INSERT INTO $table_name (currency_code, currency_label, currency_symbol) VALUES ('huf','hungary forint', '');
					INSERT INTO $table_name (currency_code, currency_label, currency_symbol) VALUES ('idr','indonesia rupiah', '');
					INSERT INTO $table_name (currency_code, currency_label, currency_symbol) VALUES ('ils','israel shekel', '');
					INSERT INTO $table_name (currency_code, currency_label, currency_symbol) VALUES ('inr','india rupee', '');
					INSERT INTO $table_name (currency_code, currency_label, currency_symbol) VALUES ('jpy','japan yen', '');
					INSERT INTO $table_name (currency_code, currency_label, currency_symbol) VALUES ('krw','korea (south) won', '');
					INSERT INTO $table_name (currency_code, currency_label, currency_symbol) VALUES ('ltl','lithuania litas', '');
					INSERT INTO $table_name (currency_code, currency_label, currency_symbol) VALUES ('mad','morocco dirham', '');
					INSERT INTO $table_name (currency_code, currency_label, currency_symbol) VALUES ('mxn','mexico peso', '');
					INSERT INTO $table_name (currency_code, currency_label, currency_symbol) VALUES ('myr','malaysia ringgit', '');
					INSERT INTO $table_name (currency_code, currency_label, currency_symbol) VALUES ('nok','norway krone', '');
					INSERT INTO $table_name (currency_code, currency_label, currency_symbol) VALUES ('nzd','new zealand dollar', '');
					INSERT INTO $table_name (currency_code, currency_label, currency_symbol) VALUES ('pen','peru nuevo sol', '');
					INSERT INTO $table_name (currency_code, currency_label, currency_symbol) VALUES ('php','philippines peso', '');
					INSERT INTO $table_name (currency_code, currency_label, currency_symbol) VALUES ('pkr','pakistan rupee', '');
					INSERT INTO $table_name (currency_code, currency_label, currency_symbol) VALUES ('pln','poland zloty', '');
					INSERT INTO $table_name (currency_code, currency_label, currency_symbol) VALUES ('ron','romania new leu', '');
					INSERT INTO $table_name (currency_code, currency_label, currency_symbol) VALUES ('rsd','serbia dinar', '');
					INSERT INTO $table_name (currency_code, currency_label, currency_symbol) VALUES ('rub','russia ruble', '');
					INSERT INTO $table_name (currency_code, currency_label, currency_symbol) VALUES ('sar','saudi arabia riyal', '');
					INSERT INTO $table_name (currency_code, currency_label, currency_symbol) VALUES ('sek','sweden krona', '');
					INSERT INTO $table_name (currency_code, currency_label, currency_symbol) VALUES ('sgd','singapore dollar', '');
					INSERT INTO $table_name (currency_code, currency_label, currency_symbol) VALUES ('thb','thailand baht', '');
					INSERT INTO $table_name (currency_code, currency_label, currency_symbol) VALUES ('trl','turkey lira', '');
					INSERT INTO $table_name (currency_code, currency_label, currency_symbol) VALUES ('twd','taiwan new dollar', '');
					INSERT INTO $table_name (currency_code, currency_label, currency_symbol) VALUES ('uah','ukraine hryvna', '');
					INSERT INTO $table_name (currency_code, currency_label, currency_symbol) VALUES ('usd','us dollar', '$');
					INSERT INTO $table_name (currency_code, currency_label, currency_symbol) VALUES ('vef','venezuela bolivar', '');
					INSERT INTO $table_name (currency_code, currency_label, currency_symbol) VALUES ('vnd','viet nam dong', '');
					INSERT INTO $table_name (currency_code, currency_label, currency_symbol) VALUES ('zar','south africa rand', '');";

			dbDelta($sql);
					
		}
		
		global $EZSQL_ERROR;
		$EZSQL_ERROR = array();
	}
}

function find_currency_object($currency_code) {
	global $wpdb;
	$table_name = BOOKYOURTRAVEL_CURRENCIES_TABLE;
	$sql = "SELECT * FROM $table_name WHERE currency_code = %s";
	$row = $wpdb->get_row($wpdb->prepare($sql, $currency_code));
	return $row;
}

function list_currencies_total_items() {
	global $wpdb;
	$table_name = BOOKYOURTRAVEL_CURRENCIES_TABLE;
	return $wpdb->get_var("SELECT COUNT(*) cnt FROM $table_name");
}

function get_currency($currency_id) {
	global $wpdb;
	$table_name = BOOKYOURTRAVEL_CURRENCIES_TABLE;
	$sql = "SELECT * FROM $table_name WHERE Id = %d";
	return $wpdb->get_row($wpdb->prepare($sql, $currency_id), ARRAY_A );	
}

function list_paged_currencies($orderby = 'Id', $order = 'ASC', $paged = null, $per_page = 0 ) {
	global $wpdb;
	
	$table_name = BOOKYOURTRAVEL_CURRENCIES_TABLE;
	$sql = "SELECT *
			FROM " . $table_name . " currencies 
			WHERE 1=1 ";
			
	if(!empty($orderby) & !empty($order)){ 
		$sql.=' ORDER BY ' . $orderby . ' ' . $order; 
	}
	
	if(!empty($paged) && !empty($per_page)){
		$offset=($paged-1)*$per_page;
		$sql .=' LIMIT '.(int)$offset.','.(int)$per_page;
	}

	return $wpdb->get_results($sql);
}

function retrieve_array_of_values_from_query_string($key, $are_numbers = false) {
	$values_array = array();
	$query_string = explode("&",$_SERVER['QUERY_STRING']);
	foreach ($query_string as $part) {
		if (strpos($part, $key) !== false) {
			$split = strpos($part,"=");
			$value = trim(substr($part, $split + 1));
			if (!empty($value))
				$values_array[] = $are_numbers ? intval($value) : $value;
		}
	}
	return $values_array;
}

function byt_get_post_descendants($parent_id, $post_type){
    $children = array();
    $posts = get_posts( array( 'numberposts' => -1, 'post_status' => 'publish', 'post_type' => $post_type, 'post_parent' => $parent_id, 'suppress_filters' => false ));
    foreach( $posts as $child ){
        $gchildren = byt_get_post_descendants($child->ID, $post_type);
        if( !empty($gchildren) ) {
            $children = array_merge($children, $gchildren);
        }
    }
    $children = array_merge($children,$posts);
    return $children;
}