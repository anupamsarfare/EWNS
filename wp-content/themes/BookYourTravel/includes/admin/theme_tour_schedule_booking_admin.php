<?php

/*************************** LOAD THE BASE CLASS *******************************
 *******************************************************************************
 * The WP_List_Table class isn't automatically available to plugins, so we need
 * to check if it's available and load it if necessary.
 */
 
if(!class_exists('WP_List_Table')){
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

add_action('admin_menu' , 'tour_booking_admin_page');
function tour_booking_admin_page() {
	$hook = add_submenu_page('edit.php?post_type=tour', __('BYT Booking management', 'bookyourtravel'), __('Bookings', 'bookyourtravel'), 'edit_posts', basename(__FILE__), 'tour_bookings_admin_display');

	add_action( "load-$hook", 'tour_bookings_add_screen_options');
}

function tour_bookings_set_screen_options($status, $option, $value) {
	if ( 'tour_bookings_per_page' == $option ) 
		return $value;
}
add_filter('set-screen-option', 'tour_bookings_set_screen_options', 10, 3);

function tour_bookings_admin_head() {
	$page = ( isset($_GET['page'] ) ) ? esc_attr( $_GET['page'] ) : false;
	if( 'theme_tour_schedule_booking_admin.php' != $page )
		return;

	tour_bookings_admin_styles();
}
add_action( 'admin_head', 'tour_bookings_admin_head' );		

function tour_bookings_admin_styles() {

	echo '<style type="text/css">';
	echo '.wp-list-table .column-Id { width: 100px; }';
	echo '.wp-list-table .column-TourName { width: 250px; }';
	echo '.wp-list-table .column-TourDate { width: 150px; }';
	echo '.wp-list-table .column-UserId { width: 50px; }';
	echo '</style>';
	
	echo '<script>';
	echo '	window.adminAjaxUrl = "' . home_url() . '/wp-admin/admin-ajax.php";
		jQuery.noConflict();
		jQuery(document).ready(function () {
			jQuery("#tour_date").datepicker({
				dateFormat: \'yy-mm-dd\',
				minDate: 0
			});
		});
	';
	echo '</script>';	

}

function tour_bookings_add_screen_options() {
	global $wp_tour_booking_table;
	$option = 'per_page';
	$args = array('label' => 'Bookings','default' => 50,'option' => 'tour_bookings_per_page');
	add_screen_option( $option, $args );
 	$wp_tour_booking_table = new tour_booking_admin_list_table();
}

function tour_bookings_admin_display() {
	echo '</pre><div class="wrap"><h2>BYT Bookings</h2> Booking management screen'; 

	global $wp_tour_booking_table;
	$booking_id = $wp_tour_booking_table->handle_form_submit();
	
	if (isset($_GET['view'])) {
		$wp_tour_booking_table->render_view_form(); 
	} else if (isset($_GET['sub']) && $_GET['sub'] == 'manage') {
		$wp_tour_booking_table->render_entry_form($booking_id); 
	} else {	
		$wp_tour_booking_table->prepare_items(); 
		
	if (!empty($_REQUEST['s']))
		$form_uri = esc_url( add_query_arg( 's', $_REQUEST['s'], $_SERVER['REQUEST_URI'] ));
	else 
		$form_uri = esc_url($_SERVER['REQUEST_URI']);	
	?>
		<form method="get" action="<?php echo $form_uri; ?>">
			<input type="hidden" name="paged" value="1">
			<input type="hidden" name="post_type" value="tour">
			<input type="hidden" name="page" value="theme_tour_schedule_booking_admin.php">
			<?php
			$wp_tour_booking_table->search_box( 'search', 'search_id' );
			?>
		</form>
	<?php 		
		$wp_tour_booking_table->display();
	?>
    <div class="tablenav bottom">	
        <div class="alignleft actions">
            <a href="edit.php?post_type=tour&page=theme_tour_schedule_booking_admin.php&sub=manage" class="button-secondary action" ><?php _e('Add booking', 'bookyourtravel') ?></a>
        </div>
    </div>
	<?php
	} 
}

/************************** CREATE A PACKAGE CLASS *****************************
 *******************************************************************************
 * Create a new list table package that extends the core WP_List_Table class.
 * WP_List_Table contains most of the framework for generating the table, but we
 * need to define and override some methods so that our data can be displayed
 * exactly the way we need it to be.
 * 
 * To display this on a page, you will first need to instantiate the class,
 * then call $yourInstance->prepare_items() to handle any data manipulation, then
 * finally call $yourInstance->display() to render the table to the page.
 */
class tour_booking_admin_list_table extends WP_List_Table {

	private $options;
	private $lastInsertedID;
	
	/**
	* Constructor, we override the parent to pass our own arguments.
	* We use the parent reference to set some default configs.
	*/
	function __construct() {
		global $status, $page;	
	
		 parent::__construct( array(
			'singular'=> 'booking', // Singular label
			'plural' => 'bookings', // plural label, also this well be one of the table css class
			'ajax'	=> false // We won't support Ajax for this table
		) );
		
	}	

	function column_default( $item, $column_name ) {
		return $item->$column_name;
	}	
	
	function extra_tablenav( $which ) {
		if ( $which == "top" ){	
			//The code that goes before the table is here
		}
		if ( $which == "bottom" ){
			//The code that goes after the table is there
		}
	}		
	
	function column_Customer($item) {
		return $item->first_name . ' ' . $item->last_name;	
	}
	
	function column_TourName($item) {
		return $item->tour_name;	
	}
	
	function column_UserId($item) {
		return $item->user_id > 0 ? $item->user_id : __('n/a', 'bookyourtravel');	
	}
	
	function column_TourDate($item) {
		return date("d.m.Y", strtotime($item->tour_date));	
	}
	
	function column_Price($item) {
		return $item->total_price;
	}
	
	function column_Created($item) {
		return $item->created;	
	}
	
	function column_Action($item) {
		return "<a href='edit.php?post_type=tour&page=theme_tour_schedule_booking_admin.php&view=" . $item->Id . "'>" . __('View', 'bookyourtravel') . "</a> | 
				<a href='edit.php?post_type=tour&page=theme_tour_schedule_booking_admin.php&sub=manage&edit=" . $item->Id . "'>" . __('Edit', 'bookyourtravel') . "</a> | 		
				<form method='post' name='delete_booking_" . $item->Id . "' id='delete_booking_" . $item->Id . "' style='display:inline;'>
					<input type='hidden' name='delete_booking' id='delete_booking' value='" . $item->Id . "' />
					<a href='javascript: void(0);' onclick='confirmDelete(\"#delete_booking_" . $item->Id . "\", \"" . __('Are you sure?', 'bookyourtravel') . "\");'>" . __('Delete', 'bookyourtravel') . "</a>
				</form>";
	}	
	
	/**
	 * Define the columns that are going to be used in the table
	 * @return array $columns, the array of columns to use with the table
	 */
	function get_columns() {
		return $columns= array(
			'Id'=>__('Id', 'bookyourtravel'),
			'Customer'=>__('Customer', 'bookyourtravel'),
			'TourDate'=>__('Tour Date', 'bookyourtravel'),
			'TourName'=>__('Tour Name', 'bookyourtravel'),
			'Price'=>__('Price', 'bookyourtravel'),
			'Created'=>__('Created', 'bookyourtravel'),
			'UserId'=>__('UserId', 'bookyourtravel'),
			'Action'=>__('Action', 'bookyourtravel'),				
		);
	}	
		
	/**
	 * Decide which columns to activate the sorting functionality on
	 * @return array $sortable, the array of columns that can be sorted by the user
	 */
	public function get_sortable_columns() {
		$sortable_columns = array(
			'Id'=> array( 'Id', true ),
			'TourName'=> array( 'TourName', true ),
			'TourDate'=> array( 'TourDate', true ),
			'Price'=> array( 'Price', true )
		);
		return $sortable_columns;
	}	
	
	/**
	 * Prepare the table with different parameters, pagination, columns and table elements
	 */
	function prepare_items() {
		global $_wp_column_headers;
		
		$screen = get_current_screen();
		$user = get_current_user_id();
		$option = $screen->get_option('per_page', 'option');
		$per_page = get_user_meta($user, $option, true);
		if ( empty ( $per_page) || $per_page < 1 ) {
			$per_page = $screen->get_option( 'per_page', 'default' );
		}	

		$search_term = '';
		if (!empty($_REQUEST['s'])) {
			$search_term = mysql_real_escape_string(strtolower($_REQUEST['s']));
		}

		$columns = $this->get_columns(); 
		$hidden = array();
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array($columns, $hidden, $sortable);		
		
		/* -- Ordering parameters -- */
		//Parameters that are going to be used to order the result
		$orderby = !empty($_GET["orderby"]) ? mysql_real_escape_string($_GET["orderby"]) : 'Id';
		if ($orderby == 'TourName')
			$orderby = 'tours.post_title';
		else if ($orderby == 'TourDate')
			$orderby = 'tour_date';
		else if ($orderby=='Price')
			$orderby = 'total_price';
			
		$order = !empty($_GET["order"]) ? mysql_real_escape_string($_GET["order"]) : 'ASC';

		//How many to display per page?
		//Which page is this?
		$paged = !empty($_GET["paged"]) ? mysql_real_escape_string($_GET["paged"]) : '';
		//Page Number
		if(empty($paged) || !is_numeric($paged) || $paged<=0 ){ $paged=1; }
		//How many pages do we have in total?

		$tour_bookings_results = list_tour_bookings ( $paged, $per_page, $orderby, $order, $search_term );
		/* -- Pagination parameters -- */
		//Number of elements in your table?
		$totalitems = $tour_bookings_results['total']; //return the total number of affected rows
		
		$totalpages = ceil($totalitems/$per_page);

		/* -- Register the pagination -- */
		$this->set_pagination_args( array(
			"total_items" => $totalitems,
			"total_pages" => $totalpages,
			"per_page" => $per_page,
		) );
		//The pagination links are automatically built according to those parameters

		/* -- Register the Columns -- */
		$columns = $this->get_columns();
		$_wp_column_headers[$screen->id]=$columns;

		/* -- Fetch the items -- */
		$this->items = $tour_bookings_results['results'];
	}
	
	function handle_form_submit() {
		
		if (isset($_POST['delete_booking'])) {
			$booking_id = absint($_POST['delete_booking']);
			
			delete_tour_booking($booking_id);
			
			echo '<div class="updated" id="message" onclick="this.parentNode.removeChild(this)">';
			echo '<p>' . __('Successfully deleted booking!', 'bookyourtravel') . '</p>';
			echo '</div>';
			
			return 0;
			
		} else if (isset($_POST['insert']) || isset($_POST['update'])) {
		
			$booking_id = isset($_POST['booking_id']) ? wp_kses($_POST['booking_id'], '') : 0;
			$tour_schedule_id = wp_kses($_POST['tour_schedule_id'], '');		
			$tour_id = wp_kses($_POST['tour_id'], '');	
			$user_id = get_current_user_id();		
			
			$first_name =  wp_kses($_POST['first_name'], '');
			$last_name =  wp_kses($_POST['last_name'], '');
			$email =  wp_kses($_POST['email'], '');
			$phone =  wp_kses($_POST['phone'], '');
			$address =  wp_kses($_POST['address'], '');
			$town =  wp_kses($_POST['town'], '');
			$zip =  wp_kses($_POST['zip'], '');
			$country =  wp_kses($_POST['country'], '');
			$special_requirements =  wp_kses($_POST['special_requirements'], '');
			$adults = intval(wp_kses($_POST['adults'], '0'));
			$children = intval(wp_kses($_POST['children'], '0'));
			$total_price_adults = floatval(wp_kses($_POST['total_price_adults'], '2'));
			$total_price_children = floatval(wp_kses($_POST['total_price_children'], '2'));
			$total_price = floatval(wp_kses($_POST['total_price'], '2'));
			$tour_date = wp_kses($_POST['tour_date'], '');
			$tour_date = date('Y-m-d', strtotime($tour_date));
			
			if (isset($_POST['insert']) && check_admin_referer('tour_booking_entry_form_nonce')) {
				
				$booking_id = create_tour_booking($first_name, $last_name, $email, $phone, $address, $town, $zip, $country, $special_requirements, $adults, $children, $tour_schedule_id, $user_id, $total_price_adults, $total_price_children, $total_price, $tour_date);
				
				echo '<div class="updated" id="message" onclick="this.parentNode.removeChild(this)">';
				echo '<p>' . __('Successfully inserted new tour booking entry!', 'bookyourtravel') . '</p>';
				echo '</div>';
				
			} else if (isset($_POST['update']) && check_admin_referer('tour_booking_entry_form_nonce')) {
				
				update_tour_booking($booking_id, $first_name, $last_name, $email, $phone, $address, $town, $zip, $country, $special_requirements, $adults, $children, $tour_schedule_id, $user_id, $total_price_adults, $total_price_children, $total_price, $tour_date);

				echo '<div class="updated" id="message" onclick="this.parentNode.removeChild(this)">';
				echo '<p>' . __('Successfully updated tour booking entry!', 'bookyourtravel') . '</p>';
				echo '</div>';
				
			}
			
			return $booking_id;
		}
	}
	
	function render_view_form() {
		
		$booking_id = isset($_GET['view']) ? intval($_GET['view']) : 0;
		if ($booking_id > 0) {

			$booking = get_tour_booking($booking_id);
			
			if ($booking != null) {
				echo "<p><h3>" . __('View booking', 'bookyourtravel') . "</h3></p>";
				echo "<table cellpadding='3' cellspacing='3' class='form-table'>";
				echo "<tr>";
				echo "<th>" . __('First name', 'bookyourtravel') . "</th>";
				echo "<td>" . $booking->first_name . "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<th>" . __('Last name', 'bookyourtravel') . "</th>";
				echo "<td>" . $booking->last_name . "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<th>" . __('Email', 'bookyourtravel') . "</th>";
				echo "<td>" . $booking->email . "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<th>" . __('Phone', 'bookyourtravel') . "</th>";
				echo "<td>" . $booking->phone . "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<th>" . __('Address', 'bookyourtravel') . "</th>";
				echo "<td>" . $booking->address . "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<th>" . __('Town', 'bookyourtravel') . "</th>";
				echo "<td>" . $booking->town . "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<th>" . __('Zip', 'bookyourtravel') . "</th>";
				echo "<td>" . $booking->zip . "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<th>" . __('Country', 'bookyourtravel') . "</th>";
				echo "<td>" . $booking->country . "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<th>" . __('Special requirements', 'bookyourtravel') . "</th>";
				echo "<td>" . $booking->special_requirements . "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<th>" . __('Tour', 'bookyourtravel') . "</th>";
				echo "<td>" . $booking->tour_name . "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<th>" . __('Tour date', 'bookyourtravel') . "</th>";
				echo "<td>" . date("d.m.Y", strtotime($booking->tour_date)) . "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<th>" . __('Total price', 'bookyourtravel') . "</th>";
				echo "<td>" . $booking->total_price . "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<th>" . __('Created at', 'bookyourtravel') . "</th>";
				echo "<td>" . $booking->created . "</td>";
				echo "</tr>";
				echo "</table>";
				echo "<p><a href='edit.php?post_type=tour&page=theme_tour_schedule_booking_admin.php'>" . __('&laquo; Go back', 'bookyourtravel') . "</a></p>";
				
			}
		}
	}
	
	function render_entry_form($booking_id) {
		
		$booking_object = null;
		
		$edit = isset($_GET['edit']) ? absint($_GET['edit']) : 0;
		if ($booking_id > 0)
			$edit = $booking_id;
		
		if (!empty($edit)) {
			$booking_object = get_tour_booking($edit);
		}
		
		$tour_date = null;
		if (isset($_POST['tour_date']))
			$tour_date = wp_kses($_POST['tour_date'], '');
		else if ($booking_object != null) {
			$tour_date = $booking_object->tour_date;
		}
		
		$first_name = '';
		if (isset($_POST['first_name']))
			$first_name = wp_kses($_POST['first_name'], '');
		else if ($booking_object != null) {
			$first_name = $booking_object->first_name;
		}
		
		$last_name = '';
		if (isset($_POST['last_name']))
			$last_name = wp_kses($_POST['last_name'], '');
		else if ($booking_object != null) {
			$last_name = $booking_object->last_name;
		}
		
		$email = '';
		if (isset($_POST['email']))
			$email = wp_kses($_POST['email'], '');
		else if ($booking_object != null) {
			$email = $booking_object->email;
		}
		
		$phone = '';
		if (isset($_POST['phone']))
			$phone = wp_kses($_POST['phone'], '');
		else if ($booking_object != null) {
			$phone = $booking_object->phone;
		}
		
		$address = '';
		if (isset($_POST['address']))
			$address = wp_kses($_POST['address'], '');
		else if ($booking_object != null) {
			$address = $booking_object->address;
		}
		
		$town = '';
		if (isset($_POST['town']))
			$town = wp_kses($_POST['town'], '');
		else if ($booking_object != null) {
			$town = $booking_object->town;
		}
		
		$zip = '';
		if (isset($_POST['zip']))
			$zip = wp_kses($_POST['zip'], '');
		else if ($booking_object != null) {
			$zip = $booking_object->zip;
		}		
		
		$country = '';
		if (isset($_POST['country']))
			$country = wp_kses($_POST['country'], '');
		else if ($booking_object != null) {
			$country = $booking_object->country;
		}
		
		$special_requirements = '';
		if (isset($_POST['special_requirements']))
			$special_requirements = wp_kses($_POST['special_requirements'], '');
		else if ($booking_object != null) {
			$special_requirements = $booking_object->special_requirements;
		}
		
		$adults = 0;
		if (isset($_POST['adults']))
			$adults = intval(wp_kses($_POST['adults'], ''));
		else if ($booking_object != null) {
			$adults = $booking_object->adults;
		}
		
		$children = 0;
		if (isset($_POST['children']))
			$children = intval(wp_kses($_POST['children'], ''));
		else if ($booking_object != null) {
			$children = $booking_object->children;
		}
		
		$total_price_adults = 0;
		if (isset($_POST['total_price_adults']))
			$total_price_adults = floatval(wp_kses($_POST['total_price_adults'], ''));
		else if ($booking_object != null) {
			$total_price_adults = $booking_object->total_price_adults;
		}
		
		$total_price_children = 0;
		if (isset($_POST['total_price_children']))
			$total_price_children = floatval(wp_kses($_POST['total_price_children'], ''));
		else if ($booking_object != null) {
			$total_price_children = $booking_object->total_price_children;
		}
		
		$total_price = 0;
		if (isset($_POST['total_price']))
			$total_price = floatval(wp_kses($_POST['total_price'], ''));
		else if ($booking_object != null) {
			$total_price = $booking_object->total_price;
		}
		
		$tour_id = 0;
		if (isset($_GET['tour_id'])) {
			$tour_id = absint($_GET['tour_id']);
		} else if (isset($_POST['tour_id'])) {
			$tour_id = intval(wp_kses($_POST['tour_id'], ''));
		} else if ($booking_object != null) {
			$tour_id = $booking_object->tour_id;
		}

		$tour_schedule_id = 0;
		if (isset($_GET['tour_schedule_id'])) {
			$tour_schedule_id = absint($_GET['tour_schedule_id']);
		} else if (isset($_POST['tour_schedule_id'])) {
			$tour_schedule_id = intval(wp_kses($_POST['tour_schedule_id'], ''));
		} else if ($booking_object != null) {
			$tour_schedule_id = $booking_object->tour_schedule_id;
		}

		if ($booking_object)
			echo '<h3>' . __('Update Tour Booking Entry', 'bookyourtravel') . '</h3>';
		else
			echo '<h3>' . __('Add Tour Booking Entry', 'bookyourtravel') . '</h3>';

		echo '<form id="tour_booking_entry_form" method="post" action="' . esc_url($_SERVER['REQUEST_URI']) . '" style="clear: both;">';
		
		echo wp_nonce_field('tour_booking_entry_form_nonce');	
		
		echo '<table cellpadding="3" class="form-table"><tbody>';
		
		$tours_select = '<select id="tour_id" name="tour_id" onchange="tourBookingTourFilterRedirect(' . $edit . ',this.value)">';
		$tours_select .= '<option value="">' . __('Select tour', 'bookyourtravel') . '</option>';

		$tour_results = list_tours();
		if ( count($tour_results) > 0 && $tour_results['total'] > 0 ) {
			foreach ($tour_results['results'] as $tour_result) {
				global $post;				
				$post = $tour_result;
				setup_postdata( $post ); 
				$tours_select .= '<option value="' . $post->ID . '" ' . ($post->ID == $tour_id ? 'selected' : '') . '>' . $post->post_title . '</option>';
			}
		}
		$tours_select .= '</select>';
		
		echo '<tr>';
		echo '	<th scope="row" valign="top">' . __('Select tour entry', 'bookyourtravel') . '</th>';
		echo '	<td>' . $tours_select . '</td>';
		echo '</tr>';
		
		$is_price_per_group = 0;
		if ($tour_id > 0) {
		
			$tour_obj = new byt_tour(intval($tour_id));
			$is_price_per_group = $tour_obj->get_is_price_per_group();
			
			$tour_type = __('One-off tour', 'bookyourtravel');
			if ($tour_obj->get_type_is_repeated() == 1) {
				$tour_type = __('Daily tour', 'bookyourtravel'); 
			} else if ($tour_obj->get_type_is_repeated() == 2) {
				$tour_type =  __('Weekday tour', 'bookyourtravel'); 
			} else if ($tour_obj->get_type_is_repeated() == 3) {
				$tour_type = sprintf(__('Tour on %s.</p>', 'bookyourtravel'), $tour_obj->get_type_day_of_week_day()); 
			}
		
			$tours_schedule_select = '<select id="tour_schedule_id" name="tour_schedule_id" onchange="tourBookingTourScheduleFilterRedirect(' . $edit . ',' . $tour_id . ',this.value)">';
			$tours_schedule_select .= '<option value="">' . __('Select tour schedule', 'bookyourtravel') . '</option>';
			
			$tours_schedule_results = list_tour_schedules (null, 0, 'Id', 'ASC', 0, 0, 0, $tour_id, '');
			if ( count($tours_schedule_results) > 0 && $tours_schedule_results['total'] > 0 ) {
				foreach ($tours_schedule_results['results'] as $tours_schedule_result) {
					$schedule_date = date('Y-m-d', strtotime($tours_schedule_result->start_date));
					$tours_schedule_select .= '<option value="' . $tours_schedule_result->Id . '" ' . ($tours_schedule_result->Id == $tour_schedule_id ? 'selected' : '') . '>' . __('From', 'bookyourtravel') . ' ' . $schedule_date . ' ' . __('Days', 'bookyourtravel') . ' ' . $tours_schedule_result->duration_days . ' ' . __('Price', 'bookyourtravel') . ' ' . $tours_schedule_result->price . ' [' . $tour_type . '] </option>';
				}
			}
			$tours_schedule_select .= '</select>';
			
			echo '<tr>';
			echo '	<th scope="row" valign="top">' . __('Select tour schedule entry', 'bookyourtravel') . '</th>';
			echo '	<td>' . $tours_schedule_select . '</td>';
			echo '</tr>';
		}
		
		echo '<tr>';
		echo '	<th scope="row" valign="top">' . __('Tour date', 'bookyourtravel') . '</th>';
		echo '	<td><input class="datepicker" type="text" name="tour_date" id="tour_date" value="' . $tour_date . '" /></td>';
		echo '</tr>';
		
		echo '<tr>';
		echo '	<th scope="row" valign="top">' . __('First name', 'bookyourtravel') . '</th>';
		echo '	<td><input type="text" name="first_name" id="first_name" value="' . $first_name . '" /></td>';
		echo '</tr>';

		echo '<tr>';
		echo '	<th scope="row" valign="top">' . __('Last name', 'bookyourtravel') . '</th>';
		echo '	<td><input type="text" name="last_name" id="last_name" value="' . $last_name . '" /></td>';
		echo '</tr>';

		echo '<tr>';
		echo '	<th scope="row" valign="top">' . __('Email', 'bookyourtravel') . '</th>';
		echo '	<td><input type="text" name="email" id="email" value="' . $email . '" /></td>';
		echo '</tr>';
		
		echo '<tr>';
		echo '	<th scope="row" valign="top">' . __('Phone', 'bookyourtravel') . '</th>';
		echo '	<td><input type="text" name="phone" id="phone" value="' . $phone . '" /></td>';
		echo '</tr>';
		
		echo '<tr>';
		echo '	<th scope="row" valign="top">' . __('Address', 'bookyourtravel') . '</th>';
		echo '	<td><input type="text" name="address" id="address" value="' . $address . '" /></td>';
		echo '</tr>';
		
		echo '<tr>';
		echo '	<th scope="row" valign="top">' . __('Town', 'bookyourtravel') . '</th>';
		echo '	<td><input type="text" name="town" id="town" value="' . $town . '" /></td>';
		echo '</tr>';
		
		echo '<tr>';
		echo '	<th scope="row" valign="top">' . __('Zip', 'bookyourtravel') . '</th>';
		echo '	<td><input type="text" name="zip" id="zip" value="' . $zip . '" /></td>';
		echo '</tr>';

		echo '<tr>';
		echo '	<th scope="row" valign="top">' . __('Country', 'bookyourtravel') . '</th>';
		echo '	<td><input type="text" name="country" id="country" value="' . $country . '" /></td>';
		echo '</tr>';

		echo '<tr>';
		echo '	<th scope="row" valign="top">' . __('Special requirements', 'bookyourtravel') . '</th>';
		echo '	<td><textarea type="text" name="special_requirements" id="special_requirements" rows="5" cols="50">' . $special_requirements . '</textarea></td>';
		echo '</tr>';
		
		echo '<tr>';
		echo '	<th scope="row" valign="top">' . __('Number of adults', 'bookyourtravel') . '</th>';
		echo '	<td><input type="text" name="adults" id="adults" value="' . $adults . '" /></td>';
		echo '</tr>';
		
		echo '<tr>';
		echo '	<th scope="row" valign="top">' . __('Number of children', 'bookyourtravel') . '</th>';
		echo '	<td><input type="text" name="children" id="children" value="' . $children . '" /></td>';
		echo '</tr>';
		
		if (!$is_price_per_group) {

			echo '<tr>';
			echo '	<th scope="row" valign="top">' . __('Total price for adults', 'bookyourtravel') . '</th>';
			echo '	<td><input type="text" name="total_price_adults" id="total_price_adults" value="' . $total_price_adults . '" /></td>';
			echo '</tr>';
			
			echo '<tr>';
			echo '	<th scope="row" valign="top">' . __('Total price for children', 'bookyourtravel') . '</th>';
			echo '	<td><input type="text" name="total_price_children" id="total_price_children" value="' . $total_price_children . '" /></td>';
			echo '</tr>';
			
		} else {

			echo '<tr>';
			echo '	<th scope="row" valign="top">' . __('Total price for group', 'bookyourtravel') . '</th>';
			echo '	<td><input type="text" name="total_price_adults" id="total_price_adults" value="' . $total_price_adults . '" />
			<input type="hidden" name="total_price_children" id="total_price_children" value="' . $total_price_children . '" />
			</td>';
			echo '</tr>';
		
		}
			
		echo '<tr>';
		echo '	<th scope="row" valign="top">' . __('Total price', 'bookyourtravel') . '</th>';
		echo '	<td><input type="text" name="total_price" id="total_price" value="' . $total_price . '" /></td>';
		echo '</tr>';
		
		echo '</table>';
		echo '<p>';
		echo '<a href="edit.php?post_type=tour&page=theme_tour_schedule_booking_admin.php" class="button-secondary">' . __('Cancel', 'bookyourtravel') . '</a>&nbsp;';

		if ($booking_object) {
			echo '<input id="booking_id" name="booking_id" value="' . $edit . '" type="hidden" />';
			echo '<input class="button-primary" type="submit" name="update" value="' . __('Update Booking', 'bookyourtravel') . '"/>';
		} else {
			if ($tour_id > 0 && $tour_schedule_id > 0) {
				echo '<input class="button-primary" type="submit" name="insert" value="' . __('Add Booking', 'bookyourtravel') . '"/>';
			}
		}
		echo '</p>';
		echo '</form>';
		
	}
}