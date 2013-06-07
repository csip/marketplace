<?php
/*
 * Plugin name: wpsc-vendors
 * Plugin URI: http://www.wpecommercehelp.com/plugins/many-vendors/
 * Description: This Plugin is ideal for store owners who are selling products on behalf of other people, for example downloadable products that have no stock count or a quick way of mointering how many units have been sold.
 * Version: 1.2
 * Author: WP e-Commerce Help & Instinct Entertainment
*/

//add new roles and set capabilities

add_role('vendor', 'Vendor');
$role =& get_role('vendor');
$role->add_cap('read');
$role->add_cap('level_0');
$role->add_cap('wpsc_view_product_sales');

add_role('vendor-editor', 'Vendor editor');
$role =& get_role('vendor-editor');
$role->add_cap('moderate_comments');
$role->add_cap('manage_categories');
$role->add_cap('manage_links');
$role->add_cap('upload_files');
$role->add_cap('unfiltered_html');
$role->add_cap('edit_posts');
$role->add_cap('edit_others_posts');
$role->add_cap('edit_published_posts');
$role->add_cap('publish_posts');
$role->add_cap('edit_pages');
$role->add_cap('read');
$role->add_cap('level_7');
$role->add_cap('level_6');
$role->add_cap('level_5');
$role->add_cap('level_4');
$role->add_cap('level_3');
$role->add_cap('level_2');
$role->add_cap('level_1');
$role->add_cap('level_0');
$role->add_cap('wpsc_view_product_sales');

add_role('vendor-administrator', 'Vendor administrator');
$role =& get_role('vendor-administrator');
$role->add_cap('switch_themes');
$role->add_cap('edit_themes');
$role->add_cap('activate_plugins');
$role->add_cap('edit_plugins');
$role->add_cap('edit_users');
$role->add_cap('edit_files');
$role->add_cap('manage_options');
$role->add_cap('moderate_comments');
$role->add_cap('manage_categories');
$role->add_cap('manage_links');
$role->add_cap('upload_files');
$role->add_cap('import');
$role->add_cap('unfiltered_html');
$role->add_cap('edit_posts');
$role->add_cap('edit_others_posts');
$role->add_cap('edit_published_posts');
$role->add_cap('publish_posts');
$role->add_cap('edit_pages');
$role->add_cap('read');
$role->add_cap('level_10');
$role->add_cap('level_9');
$role->add_cap('level_8');
$role->add_cap('level_7');
$role->add_cap('level_6');
$role->add_cap('level_5');
$role->add_cap('level_4');
$role->add_cap('level_3');
$role->add_cap('level_2');
$role->add_cap('level_1');
$role->add_cap('level_0');
$role->add_cap('wpsc_view_product_sales');


//save vendors to product meta
function wpsc_save_vendors($product_id = false){
	$wpsc_version = get_option( 'wpsc_version', true );
	if( $wpsc_version == '3.8.8.3' ) {
		if( $_REQUEST['wpsc_vendors'] ) {
			$result = update_product_meta($product_id, 'vendors', $_REQUEST['wpsc_vendors']);
			return $result;
		}
	}
	if( $product_id )
		update_product_meta( absint($_POST['product_id']), 'vendors', $_REQUEST['wpsc_vendors']);
}

//output vendors form to product edit page
function wpsc_vendors_form(){//fixed
	$wpsc_version = get_option( 'wpsc_version', true );
	$blogusers = get_users_of_blog();
	if( $_REQUEST['product'] ) {
		$product = $_REQUEST['product'];
	} else {
		$product = $_REQUEST['post'];
	}
	if ( $product ) {
		if( $wpsc_version == '3.8.8.3' )
			$vendors = get_product_meta( absint( $product ), 'vendors', true);
		else
			$vendors = get_product_meta( absint( $product ), 'vendors', true);

	}
	foreach ( $blogusers as $user ) {
		$meta = $user->meta_value;
		$meta = maybe_unserialize( $meta );
		if( !$meta['vendor'] && !$meta['vendor-administrator'] && !$meta['vendor-editor'] )
			continue;
		$checked = '';
		$rate = '';
		if($vendors[$user->ID]){
			if($vendors[$user->ID]['enabled']){
				$checked = 'checked="checked"';
			}
			$rate = $vendors[$user->ID]['rate'];
		}
	?>
		<label for="wpsc_user_<?php echo $user->ID; ?>">
			<input type="checkbox" name="wpsc_vendors[<?php echo $user->ID; ?>][enabled]" <?php echo $checked; ?> id="wpsc_user_<?php echo $user->ID; ?>" value="true" /> <?php echo $user->user_login; ?></label>, rate: <input type="text" name="wpsc_vendors[<?php echo $user->ID; ?>][rate]" value="<?php echo $rate; ?>" />
		<br />
	<?php
	}
}

//setup metabox for vendors in product edit page
function wpsc_vendor_metabox(){//fixed
	$pagename = 'wpsc-product';
	add_meta_box( 'wpsc_vendors', __( 'Vendors', 'wpsc_vendors' ), 'wpsc_vendors_form', $pagename, 'side', 'low' );
	//do_meta_boxes( 'wpsc_vendors', 'advanced', null );
return $data;
}

function wpsc_vendors_setup_widgets() {

	global $wpdb, $wp_meta_boxes, $current_user;
	get_currentuserinfo();

	$roles = $current_user->roles;

	if (sizeof($roles) <= 0) { return; }

	$role = array_shift($roles);

	//$capabilities = $wpdb->prefix . 'capabilities';
	//$capabilities = $current_user->data->$capabilities;

	//if( $capabilities['vendor'] || $capabilities['vendor-administrator'] || $capabilities['vendor-editor'] ) {
	if (in_array($role, array('vendor', 'vendor-administrator', 'vendor-editor'))) {

		remove_action('wp_dashboard_setup', 'ses_wpscd_add_dashboard_widgets' );
		remove_action('wp_dashboard_setup', 'wpsc_dashboard_widget_setup' );
		remove_action('wp_dashboard_setup', 'wpsc_quarterly_setup' );
		remove_action('wp_dashboard_setup', 'wpsc_dashboard_4months_widget_setup' );
		wp_add_dashboard_widget( 'wpsc_vendor_product_sales', __('Product sales', 'wpsc-vendor'), 'wpsc_vendor_product_sales' );
	}
}

//filters
#$wpsc_version = get_option( 'wpsc_version', true );
#print $wpsc_version;
add_action( 'admin_init', 'wpsc_vendor_metabox' );
if( $wpsc_version == '3.8.8.3' ) {
	add_action( 'save_post', 'wpsc_save_vendors');
} else {
	add_action( 'wpsc_edit_product', 'wpsc_save_vendors', 10, 1 );
}
add_action( 'wp_dashboard_setup', 'wpsc_vendors_setup_widgets', 1 );
add_action( 'wp_ajax_wpsc_vendor_ps_ajax','wpsc_vendor_ps_ajax' );

//add_action('admin_init', 'wpsc_vendor_product_sales');

function wpsc_vendor_product_sales() {

	wpsc_vendor_ps_ajax();
	wpsc_vendor_ps_selector();

}

function wpsc_vendor_ps_ajax() {
	global $wpdb, $current_user;
	$wpsc_version = get_option( 'wpsc_version', true );
	get_currentuserinfo();

	$period = wpsc_vendor_ps_period();
	if (isset($_GET['wpsc_vendor_period']))
		$exit = TRUE;

	switch($period) {
		case "7days":
			// Actually today + 6 previous days
			$mindate = mktime(0,0,0,date('n'),date('j'),date('Y')) - 6*60*60*24;
			$maxdate = mktime(23,59,59,date('n'),date('j'),date('Y'));
			break;

		case "today":
			$mindate = mktime(0,0,0,date('n'),date('j'),date('Y'));
			$maxdate = mktime(23,59,59,date('n'),date('j'),date('Y'));
			break;

		case "lastmonth":
			if (date('n') == 1) {
				$mindate = mktime(0,0,0,12,date('j'),date('Y')-1);
			} else {
				$mindate = mktime(0,0,0,date('n')-1,date('j'),date('Y'));
			}
			$maxdate = mktime(0,0,0,date('n'),0,date('Y'));
			break;

		case "thisyear":
			$mindate = mktime(0,0,0,1,1,date('Y'));
			$maxdate = mktime(23,59,59,12,31,date('Y'));
			break;

		case "thismonth":
		default:
			$mindate = mktime(0,0,0,date('n'),1,date('Y'));
			if (date('n') == 12) {
				$maxdate = mktime(0,0,0,1,date('j'),date('Y')+1)-1;
			} else {
				$maxdate = mktime(0,0,0,date('n')+1,date('j'),date('Y'))-1;
			}
			break;

	}
	if ($period != "alltime") {
		$wpsc_vendor_query_date_range = "pl.date BETWEEN $mindate AND $maxdate";
	} else {
		$wpsc_vendor_query_date_range = "1 = 1";
	}
	$wpsc_vendor_query = "SELECT c.name, c.prodid as id,
                                   SUM(c.quantity) AS num_items,
                                   SUM(c.quantity * c.price) AS product_revenue
	     	                 FROM {$wpdb->prefix}wpsc_cart_contents c
                             LEFT JOIN {$wpdb->prefix}wpsc_purchase_logs pl
                                ON c.purchaseid = pl.id
                             WHERE $wpsc_vendor_query_date_range AND pl.processed IN (2,3,4)
                          GROUP BY c.prodid
		                  ORDER BY product_revenue DESC, num_items DESC";

	$wpsc_vendor_result_rows = $wpdb->get_results($wpdb->prepare($wpsc_vendor_query),ARRAY_A);
	?>
	<style type="text/css">
		.ses-wpscd-table {
			padding: 5px 2px;
			margin: 0px;
			border-collapse: collapse;
		}

		.ses-wpscd-headerrow {
			border-bottom: 1px solid #ddd;
		}

		.ses-wpscd-row {
			border-bottom: 1px solid #f9f9f9;
		}

		.ses-wpscd-cell {
			padding: 5px 2px;
			text-align: center;
		}

		.ses-wpscd-left {
			text-align: left;
		}

		.ses-wpscd-right {
			text-align: right;
		}

		#ses-wpscd-product-sales-config {
			visibility: hidden;
		}
	</style>
	<div id="wpsc-vendor-product-sales">
		<table width="100%" class="ses-wpscd-table">
		<tr class="ses-wpscd-headerrow"><th class="ses-wpscd-left">Product</th><th>Units</th><th>Rate</th><th>Revenue</th><th class="ses-wpscd-right">Profit</th></tr>
		<?php
		if (!count($wpsc_vendor_result_rows)) {
			$output = "<td class=\"ses-wpscd-cell\" colspan=3>No Sales In Selected Period</td>";
		} else {
			//$output =�new string;
			$sales_found = 0;
			foreach ($wpsc_vendor_result_rows as $row) {
				if( $wpsc_version == '3.8.8.3' )
					$vendors = get_product_meta( $row['id'], 'vendors', true );
				else
					$vendors = get_product_meta( $row['id'], 'vendors', true );
				if( !$vendors )
					continue;
				$vendors = maybe_unserialize($vendors);
				if( !isset($vendors[$current_user->ID]['enabled']) )
					continue;
				if( ! $vendors[$current_user->ID]['enabled'] )
					continue;
				$rate = 1;
				if( isset($vendors[$current_user->ID]['rate']) )
					if( $vendors[$current_user->ID]['rate'] )
						$rate = $vendors[$current_user->ID]['rate']/100;
				$output .= "<tr class=\"ses-wpscd-row\">";
				$output .= "<td class=\"ses-wpscd-cell ses-wpscd-left\">".htmlentities($row['name'])."</td>";
				$output .= "<td class=\"ses-wpscd-cell\">".htmlentities($row['num_items'])."</td>";
				$output .= "<td class=\"ses-wpscd-cell\">" . $rate*100 . '%</td>';
				$sales_found = 1;
				if( $wpsc_version == '3.8.8.3' ){
					$output .= "<td class=\"ses-wpscd-cell\">" . wpsc_currency_display( $row['product_revenue'] ) . "</td>";
					$output .= "<td class=\"ses-wpscd-cell ses-wpscd-right\">" . wpsc_currency_display( ( (float) $row['product_revenue'] ) * $rate ) . "</td>";
				} else {
					$output .= "<td class=\"ses-wpscd-cell\">" . nzshpcrt_currency_display( $row['product_revenue'], 1 ) . "</td>";
					$output .= "<td class=\"ses-wpscd-cell ses-wpscd-right\">" . nzshpcrt_currency_display( ( (float) $row['product_revenue'] ) * $rate, 1 ) . "</td>";
				}
				$output .= "</tr>";
			}
			if( !$sales_found )
				$output = "<td class=\"ses-wpscd-cell\" colspan=3>No Sales In Selected Period</td>";
		}

		echo $output;
		?>
		</table>
	</div>
	<?php
	if ($exit) {
		// This is an AJAX update - so exit()
		exit();
	}

}

function wpsc_vendor_ps_selector() {

	$period = wpsc_vendor_ps_period();
?>

<div width="100%" class="wpsc-vendor-right">
	<form method="POST" action="#">
		<select id="wpsc-vendor-product-sales-period" name="wpsc-vendor-product-sales-period">
			<option value="today"<?php if($period=="today") echo " selected"; ?>>Today</option>
			<option value="7days"<?php if($period=="7days") echo " selected"; ?>>Last 7 Days</option>
			<option value="thismonth"<?php if($period=="thismonth") echo " selected"; ?>>This Month</option>
			<option value="lastmonth"<?php if($period=="lastmonth") echo " selected"; ?>>Last Month</option>
			<option value="thisyear"<?php if($period=="thisyear") echo " selected"; ?>>This Year</option>
			<option value="alltime"<?php if($period=="alltime") echo " selected"; ?>>All Time</option>
		</select>
	</form>
	<script type="text/javascript">
		jQuery('#wpsc-vendor-product-sales-period').change(function() {
	             jQuery.ajax( { url: "admin-ajax.php?action=wpsc_vendor_ps_ajax&wpsc_vendor_period="+jQuery(this).val(),
                                    success: function(data) { jQuery("#wpsc-vendor-product-sales").html(data); }
                                      }
                                    ) });
	</script>
</div>
<?php
}

function wpsc_vendor_ps_period() {

	if (isset($_GET['wpsc_vendor_period'])) {
		$period = $_GET['wpsc_vendor_period'];
		setcookie('wpsc_vendor_product_sales_period', $period, time()+(86400*30));
		$exit = TRUE;
	} elseif (isset($_COOKIE['wpsc_vendor_product_sales_period'])) {
		$period = $_COOKIE['wpsc_vendor_product_sales_period'];
	} else {
		$period = 'thismonth';
	}
	return $period;
}
