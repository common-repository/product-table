<?php

if( !defined( 'ABSPATH' ) ) {
	exit;
}

if( !class_exists( 'PL_Dashboard' ) ) {

	/**
	 * Dashboard
	 *
	 * Dashboard related functions
	 *
	 * @package ProductTable/Classes
	 * @since   1.0.0
	 */

	class PT_Dashboard {

		/**
		 * Construct
		 *
		 * @since 1.0.0
		 */

		public function __construct() {

			add_action( 'admin_menu', array( $this, 'dashboard_menu' ) );

		}

		/**
		 * Adds a menu to the WordPress dashboard
		 *
		 * @since 1.0.0
		 */

		public function dashboard_menu() {

			add_menu_page(
				__( 'Product Table', 'pt-product-table' ),
				__( 'Product Table', 'pt-product-table' ),
				'manage_options',
				'pt-product-table',
				array( $this, 'dashboard_page' ),
				'dashicons-list-view',
				'58' // After WooCommerce Products
			);

		}

		/**
		 * Displays the dashboard page
		 *
		 * @since 1.0.0
		 */

		public function dashboard_page() {

			global $wpdb; ?>

			<div class="wrap">
				<h1 class="wp-heading-inline"><?php _e( 'Product Table', 'pt-product-table'); ?></h1>
				<h2>General</h2>
				<p><?php _e( 'Product tables can be generated using the shortcode <code>[pt_product_table]</code>.', 'pt-product-table'); ?></p>
				<p><?php _e( 'Insert this shortcode into any page, post, template (using the do_shortcode function) or other location where you want your table to display.', 'pt-product-table'); ?></p>
				<h2><?php _e( 'Configuration', 'pt-product-table'); ?></h2>
				<?php _e( 'By default the product table will display with default options, but you can configure the product table further by using the following options within the shortcode tag:', 'pt-product-table'); ?>
				<p>
					<table class="widefat fixed striped">
						<thead>
							<tr>
								<td><?php _e( 'Option', 'pt-product-table'); ?></td>
								<td><?php _e( 'Example', 'pt-product-table'); ?></td>
								<td><?php _e( 'Values', 'pt-product-table'); ?></td>
								<td><?php _e( 'Description', 'pt-product-table'); ?></td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><?php _e( 'Categories', 'pt-product-table'); ?></td>
								<td><code>[pt_product_table categories="shirts,pants"]</code></td>
								<td><?php _e( 'category-slug', 'pt-product-table'); ?></td>
								<td><?php _e( 'Limits the product table to only show products from specific categories, enter each category slug comma seperated. Note that if you choose a parent category then products from sub categories are included. You can find a list of your category slugs below.', 'pt-product-table'); ?></td>
							</tr>
							<tr>
								<td><?php _e( 'Columns', 'pt-product-table'); ?></td>
								<td><code>[pt_product_table columns="sku,name,price"]</code></td>
								<td>image, id, sku, name, categories, price, buy*<br><small><?php _e( '*requires premium', 'pt-product-table'); ?></small></td>
								<td><?php _e( 'Allows you to define the columns to be displayed, any not included will be hidden.', 'pt-product-table'); ?></td>
							</tr>
							<tr>
								<td><?php _e( 'Filters', 'pt-product-table'); ?></td>
								<td><code>[pt_product_table filters="0"]</code></td>
								<td>1 <?php _e( 'or', 'pt-product-table'); ?> 0</td>
								<td><?php _e( 'Enable or disable filters shown in the column headers. Note that searching must be enabled to allow filters to work (see searching below)', 'pt-product-table'); ?></td>
							</tr>
							<tr>
								<td><?php _e( 'Length', 'pt-product-table'); ?></td>
								<td><code>[pt_product_table length="50"]</code></td>
								<td><?php _e( 'Any number', 'pt-product-table'); ?></td>
								<td><?php _e( 'Number of products to show before pagination (if paging enabled).', 'pt-product-table'); ?></td>
							</tr>
							<tr>
								<td><?php _e( 'Ordering', 'pt-product-table'); ?></td>
								<td><code>[pt_product_table ordering="0"]</code></td>
								<td>1 <?php _e( 'or', 'pt-product-table'); ?> 0</td>
								<td><?php _e( 'Enable or disable ability to order the product table by a column by clicking the column header.', 'pt-product-table'); ?></td>
							</tr>
							<tr>
								<td><?php _e( 'Paging', 'pt-product-table'); ?></td>
								<td><code>[pt_product_table paging="0"]</code></td>
								<td>1 <?php _e( 'or', 'pt-product-table'); ?> 0</td>
								<td><?php _e( 'Enable or disable spliting the product table across multiple pages to avoid long pages.', 'pt-product-table'); ?></td>
							</tr>
							<tr>
								<td><?php _e( 'Searching', 'pt-product-table'); ?></td>
								<td><code>[pt_product_table searching="0"]</code></td>
								<td>1 <?php _e( 'or', 'pt-product-table'); ?> 0</td>
								<td><?php _e( 'Enable or disable searching the table. Note that disabling searching also disables filtering as filtering uses the search functionality.', 'pt-product-table'); ?></td>
							</tr>
							<tr>
								<td><?php _e( 'Info', 'pt-product-table'); ?></td>
								<td><code>[pt_product_table info="0"]</code></td>
								<td>1 <?php _e( 'or', 'pt-product-table'); ?> 0</td>
								<td><?php _e( 'Enable or disable information shown at the bottom of the table displaying entry counts and filtering information.', 'pt-product-table'); ?></td>
							</tr>
						</tbody>
					</table>
				</p>
				
				<h2><?php _e( 'Combined Options', 'pt-product-table'); ?></h2>
				<p><?php _e( 'You can combine any options like so', 'pt-product-table'); ?></p>
				<p><code>[pt_product_table categories="shirts,pants" columns="sku,name,price" filters="0"]</code></p>
				
				<h2><?php _e( 'Using Page Caching?', 'pt-product-table'); ?></h2>
				<p><?php _e( 'If you are using page caching (you are using some means of caching dynamic pages as static content) then our plugin gets product data dynamically by AJAX as standard, however the shortcode options you use are cached and therefore if you change your shortcode you should clear your page cache on the pages the shortcode is used to see the updated product table display.', 'pt-product-table'); ?></p>

				<h2><?php _e( 'Active Pages', 'pt-product-table'); ?></h2>
				<p><?php _e( 'Product table shortcode is used on these pages:', 'pt-product-table'); ?></p>
				<?php $product_table_pages = $wpdb->get_results("SELECT ID FROM `{$wpdb->prefix}posts` WHERE `post_content` LIKE '%[pt_product_table%' AND `post_type` != 'revision' ORDER BY `post_author`  DESC"); ?>
				<table class="widefat fixed striped">
					<thead>
						<tr>
							<th><?php _e( 'Name', 'text-domain' ); ?></th>
							<th><?php _e( 'URL', 'text-domain' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php
						if( !empty( $product_table_pages ) ) {
							foreach( $product_table_pages as $product_table_page ) { ?>
								<tr>
									<td><?php echo get_the_title( $product_table_page->ID ); ?></td>
									<td><?php echo '<a href="' . get_permalink( $product_table_page->ID ) . '" target="_blank">' . get_permalink( $product_table_page->ID ) . '</a>'; ?></td>
								</tr>
							<?php }
						} else { ?>
								<tr>
									<td colspan="2"><?php _e( 'Not used on any pages yet.', 'pt-product-table'); ?></td>
								</tr>
						<?php } ?>
					</tbody>
				</table>

				<h2><?php _e( 'Category Slugs', 'pt-product-table'); ?></h2>
				<p><?php _e( 'List of category slugs to use in the category options above.', 'pt-product-table'); ?></p>

				<?php

				global $wpdb;

				$category_details = $wpdb->get_results("
					SELECT terms.term_id, terms.name, terms.slug
					FROM `{$wpdb->prefix}term_taxonomy` as term_taxonomy
					INNER JOIN `{$wpdb->prefix}terms` as terms
					ON term_taxonomy.term_id = terms.term_id
					WHERE term_taxonomy.taxonomy = 'product_cat'
					ORDER BY terms.name ASC
				"); ?>

				<table class="widefat fixed striped">
					<thead>
						<tr>
							<th><?php _e( 'Name', 'text-domain' ); ?></th>
							<th><?php _e( 'Slug', 'text-domain' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach( $category_details as $category_detail ) { ?>
							<tr>
								<td><a href="<?php echo get_edit_term_link( $category_detail->term_id ); ?>"><?php echo $category_detail->name; ?></a></td>
								<td><?php echo $category_detail->slug; ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
				<h2><?php _e( 'Buy Column', 'pt-product-table'); ?></h2>
				<p><?php echo sprintf( __( 'If you wish to allow table viewers to add items to cart <a href="%s">we offer a premium version of the plugin to do this</a>.', 'product-table' ), admin_url( 'admin.php?page=pt-product-table-pricing' ) ); ?></p>
				<p><?php _e( 'Purchasing the premium version ensures we can commit time to develop this plugin further. Enabling the premium version will enable the buy column if using the default shortcode <code>[pt_product_table]</code>. If you already have shortcodes using the columns options, add the buy column to it like this: <code>[pt_product_table columns="id,sku,name,buy"]</code>.', 'pt-product-table'); ?></p>
				
			</div>

		<?php }

	}

	new PT_Dashboard();

}