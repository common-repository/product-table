<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'PT_Shortcode' ) ) {
    /**
     * Shortcode
     *
     * Shortcode related functions
     *
     * @package ProductTable/Classes
     * @since   1.0.0
     */
    class PT_Shortcode
    {
        /**
         * Construct
         *
         * @since 1.0.0
         */
        public function __construct()
        {
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueues' ) );
            add_shortcode( 'pt_product_table', array( $this, 'shortcode' ) );
        }
        
        /**
         * Enqueues files required by the shortcode
         *
         * @since 1.0.0
         */
        public function enqueues()
        {
            wp_enqueue_script( 'datatables', plugin_dir_url( __DIR__ ) . 'libraries/DataTables/datatables.min.js', array( 'jquery' ) );
            wp_enqueue_style( 'datatables', plugin_dir_url( __DIR__ ) . 'libraries/DataTables/datatables.min.css' );
        }
        
        /**
         * Shortcode output
         *
         * @since 1.0.0
         */
        public function shortcode( $atts )
        {
            // WooCommerce check
            
            if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
                // Categories
                
                if ( isset( $atts['categories'] ) ) {
                    $categories = $atts['categories'];
                } else {
                    // Default
                    $categories = '';
                }
                
                // Columns
                
                if ( isset( $atts['columns'] ) ) {
                    $columns = explode( ',', $atts['columns'] );
                } else {
                    $columns = array(
                        'id',
                        'sku',
                        'image',
                        'name',
                        'categories',
                        'price'
                    );
                }
                
                // Filters (Note that is searching is disabled then filters will be too - see condition later)
                
                if ( isset( $atts['filters'] ) ) {
                    $filters = $atts['filters'];
                } else {
                    // Default
                    $filters = 1;
                }
                
                // Length
                
                if ( isset( $atts['length'] ) ) {
                    $length = $atts['length'];
                } else {
                    // Default
                    $length = 1;
                }
                
                // Ordering
                
                if ( isset( $atts['ordering'] ) ) {
                    $ordering = $atts['ordering'];
                } else {
                    // Default
                    $ordering = 1;
                }
                
                // Paging
                
                if ( isset( $atts['paging'] ) ) {
                    $paging = $atts['paging'];
                } else {
                    // Default
                    $paging = 1;
                }
                
                // Searching
                
                if ( isset( $atts['searching'] ) ) {
                    $searching = $atts['searching'];
                    $filters = 0;
                    // Filters needs to be disabled here when searching disabled as reliant on searching
                } else {
                    // Default
                    $searching = 1;
                }
                
                // Info
                
                if ( isset( $atts['info'] ) ) {
                    $info = $atts['info'];
                } else {
                    // Default
                    $info = 1;
                }
                
                ob_start();
                // Enqueue CSS
                wp_enqueue_style( 'Product_Table', plugin_dir_url( __DIR__ ) . 'assets/css/product-table.css' );
                // Enqueued here as only required if shortcode in use
                ?>

				<div id="pt-product-table-wrap">
					<table id="pt-product-table">
						<thead>
							<tr>
								<th><?php 
                _e( 'Image', 'pt-product-table' );
                ?></th>
								<th><?php 
                _e( 'ID', 'pt-product-table' );
                ?></th>
								<th><?php 
                _e( 'SKU', 'pt-product-table' );
                ?></th>
								<th><?php 
                _e( 'Name', 'pt-product-table' );
                ?></th>
								<th><?php 
                _e( 'Categories', 'pt-product-table' );
                ?></th>
								<th><?php 
                _e( 'Price', 'pt-product-table' );
                ?></th>
								<?php 
                ?>
							</tr>
						</thead>
						<tbody>
						</tbody>
						<tfoot>
							<tr>
								<td><?php 
                _e( 'Image', 'pt-product-table' );
                ?></td>
								<td><?php 
                _e( 'ID', 'pt-product-table' );
                ?></td>
								<td><?php 
                _e( 'SKU', 'pt-product-table' );
                ?></td>
								<td><?php 
                _e( 'Name', 'pt-product-table' );
                ?></td>
								<td><?php 
                _e( 'Categories', 'pt-product-table' );
                ?></td>
								<td><?php 
                _e( 'Price', 'pt-product-table' );
                ?></td>
								<?php 
                ?>
							</tr>
						</tfoot>
					</table>
				</div>

				<script>
					jQuery(document).ready( function($) {

						// Define AJAX URLs

						var ajaxUrl = "<?php 
                echo  admin_url( 'admin-ajax.php' ) ;
                ?>";
						var ajaxUrlDatatable = '<?php 
                echo  admin_url( 'admin-ajax.php?action=datatable&categories=' . $categories ) ;
                ?>';

						<?php 
                ?>

						// Filters

						<?php 
                if ( $filters == 1 ) {
                    ?>

							$('#pt-product-table thead tr').clone(true).appendTo( '#pt-product-table thead' );
							
							$('#pt-product-table thead tr:eq(1) th').each( function (i) {

								$(this).html( '<input type="text" />' );

								$( 'input', this ).on( 'keyup change', function () {
									if ( table.column(i).search() !== this.value ) {
										table.column(i).search( this.value ).draw();
									}
								} );

							} );

						<?php 
                }
                ?>

						// Datatable

						$.fn.dataTable.ext.classes.sPageButton = 'button';
						$.fn.dataTable.ext.errMode = 'none'; // Disable normal error mode

						var table = $('#pt-product-table').DataTable({
							"ajax": ajaxUrlDatatable,
							"stateSave": false,
							"orderCellsTop": true, // must be true or sorting interferes with filters
							"bLengthChange": <?php 
                echo  ( $length == 0 ? 'false' : 'true' ) ;
                ?>,
							"ordering": <?php 
                echo  ( $ordering == 0 ? 'false' : 'true' ) ;
                ?>,
							"paging": <?php 
                echo  ( $paging == 0 ? 'false' : 'true' ) ;
                ?>,
							"searching": <?php 
                echo  ( $searching == 0 ? 'false' : 'true' ) ;
                ?>,
							"info": <?php 
                echo  ( $info == 0 ? 'false' : 'true' ) ;
                ?>,
							"columnDefs": [
								{
									"targets": [0],
									"visible": <?php 
                echo  ( in_array( 'image', $columns ) ? 'true' : 'false' ) ;
                ?>,
								},
								{
									"targets": [1],
									"visible": <?php 
                echo  ( in_array( 'id', $columns ) ? 'true' : 'false' ) ;
                ?>,
								},
								{
									"targets": [2],
									"visible": <?php 
                echo  ( in_array( 'sku', $columns ) ? 'true' : 'false' ) ;
                ?>,
								},
								{
									"targets": [3],
									"visible": <?php 
                echo  ( in_array( 'name', $columns ) ? 'true' : 'false' ) ;
                ?>,
								},
								{
									"targets": [4],
									"visible": <?php 
                echo  ( in_array( 'categories', $columns ) ? 'true' : 'false' ) ;
                ?>,
								},
								{
									"targets": [5],
									"visible": <?php 
                echo  ( in_array( 'price', $columns ) ? 'true' : 'false' ) ;
                ?>,
								},
								<?php 
                ?>
							],
							"fnCreatedRow": function( nRow, aData, iDataIndex ) {
								$(nRow).attr('data-product-id', aData[0]); // Row ID
							},
						});

					});
				</script>

				<?php 
                return ob_get_clean();
            } else {
                _e( 'Product table unavailable as WooCommerce not installed or activated.', 'pt-product-table' );
            }
        
        }
    
    }
    new PT_Shortcode();
}
