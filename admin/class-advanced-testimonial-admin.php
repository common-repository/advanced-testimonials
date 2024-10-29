<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.codeincept.com
 * @since      1.0.0
 *
 * @package    Advanced_Testimonial
 * @subpackage Advanced_Testimonial/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Advanced_Testimonial
 * @subpackage Advanced_Testimonial/admin
 * @author     CodeIncept <codeincept@gmail.com>
 */
class CI_Advanced_Testimonial_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		add_action( 'init', array($this, 'testimonials_register'));
		add_action( 'init', array($this, 'register_testimonial_taxonomies'));
		add_action( 'add_meta_boxes', array($this, 'testimonial_register_metabox' ));
		add_action( 'save_post_advtestimonials', array($this, 'save_testimonial_meta_box_data' ));
			
	}
	public function testimonials_register() {

			$labels = array(
				'name'                  => __( 'Testimonials', 'advanced-testimonial' ),
				'singular_name'         => __( 'Testimonial', 'advanced-testimonial' ),
				'menu_name'             => __( 'Testimonials', 'advanced-testimonial' ),
				'name_admin_bar'        => __( 'Testimonial', 'advanced-testimonial' ),
				'archives'              => __( 'Item Archives', 'advanced-testimonial' ),
				'attributes'            => __( 'Item Attributes', 'advanced-testimonial' ),
				'parent_item_colon'     => __( 'Parent Item:', 'advanced-testimonial' ),
				'all_items'             => __( 'All Items', 'advanced-testimonial' ),
				'add_new_item'          => __( 'Add New Item', 'advanced-testimonial' ),
				'add_new'               => __( 'Add New', 'advanced-testimonial' ),
				'new_item'              => __( 'New Item', 'advanced-testimonial' ),
				'edit_item'             => __( 'Edit Item', 'advanced-testimonial' ),
				'update_item'           => __( 'Update Item', 'advanced-testimonial' ),
				'view_item'             => __( 'View Item', 'advanced-testimonial' ),
				'view_items'            => __( 'View Items', 'advanced-testimonial' ),
				'search_items'          => __( 'Search Item', 'advanced-testimonial' ),
				'not_found'             => __( 'Not found', 'advanced-testimonial' ),
				'not_found_in_trash'    => __( 'Not found in Trash', 'advanced-testimonial' ),
				'featured_image'        => __( 'Featured Image', 'advanced-testimonial' ),
				'set_featured_image'    => __( 'Set featured image', 'advanced-testimonial' ),
				'remove_featured_image' => __( 'Remove featured image', 'advanced-testimonial' ),
				'use_featured_image'    => __( 'Use as featured image', 'advanced-testimonial' ),
				'insert_into_item'      => __( 'Insert into item', 'advanced-testimonial' ),
				'uploaded_to_this_item' => __( 'Uploaded to this item', 'advanced-testimonial' ),
				'items_list'            => __( 'Items list', 'advanced-testimonial' ),
				'items_list_navigation' => __( 'Items list navigation', 'advanced-testimonial' ),
				'filter_items_list'     => __( 'Filter items list', 'advanced-testimonial' ),
			);
			$args = array(
				'label'                 => __( 'Testimonial', 'advanced-testimonial' ),
				'description'           => __( 'Testimonials for your site', 'advanced-testimonial' ),
				'labels'                => $labels,
				'supports'              => array( 'title', 'editor', 'thumbnail' ,'excerpt'),
				'taxonomies'            => array( 'testimonial_category', 'advtestimonials' ),
				'hierarchical'          => false,
				'public'                => true,
				'show_ui'               => true,
				'show_in_menu'          => true,
				'menu_icon'				=>	plugin_dir_url(dirname(__FILE__)).'admin/images/rating.png',
				'menu_position'         => 5,
				'show_in_admin_bar'     => true,
				'show_in_nav_menus'     => false,
				'can_export'            => false,
				'has_archive'           => false,
				'exclude_from_search'   => false,
				'publicly_queryable'    => false,
				'capability_type'       => 'post',
			);
			register_post_type( 'advtestimonials', $args );
		}
		public function register_testimonial_taxonomies(){
			register_taxonomy(
				'testimonial_category',
				'advtestimonials',
				array(
					'label' => __( 'Category','advanced-testimonial' ),
					'rewrite' => array( 'slug' => 'testimonial_category' ),
					'hierarchical' => true,
				)
			);
		}
		public function testimonial_register_metabox(){
			add_meta_box( 
				'testimonial-meta-box-id', 
				__('Author Details','advanced-testimonial'), 
				array($this,'testimonial_metabox_callback'), 
				'advtestimonials', 
				'normal', 
				'high' );
		}
		public function testimonial_metabox_callback($post){
			wp_nonce_field( 'testimonial_meta_box_nonce', 'testimonial_meta_box_nonce' );
			$testimonial=get_post_meta($post->ID,'advanced_testimonial_options',true);
			

			?>
			<table style="width: 100%; text-align: left;">
				<tbody>
					<tr>
					<th><label for="testimonial_name"> <?php _e('Author Name','advanced-testimonial'); ?></label></th>
					<td><input type="text" name="testimonial[author]" id="testimonial_name" 
						value="<?php echo isset($testimonial['author']) ? esc_attr($testimonial['author']) :''; ?>" class="widefat"></td>
					</tr>
					<tr>
					<th><label for="designation"> <?php _e('Designation','advanced-testimonial'); ?></label></th>
					<td><input type="text" name="testimonial[designation]" id="designation" 
						value="<?php echo isset($testimonial['designation']) ? esc_attr($testimonial['designation']) :''; ?>" class="widefat"></td>
					</tr>
					
					
					
				</tbody>
			</table>
			<?php
		}
		public function save_testimonial_meta_box_data($post_id){
			// Bail if we're doing an auto save
		    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
		     
		    // if our nonce isn't there, or we can't verify it, bail
		    if( !isset( $_POST['testimonial_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['testimonial_meta_box_nonce'], 'testimonial_meta_box_nonce' ) ) return;
		     
		    // if our current user can't edit this post, bail
		    if( !current_user_can( 'edit_post' ) ) return;
		    
		    // save 
		    if(isset($_POST['testimonial'])){
		    	update_post_meta($post_id,'advanced_testimonial_options',$_POST['testimonial']);
		    }
		}
		
	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Advanced_Testimonial_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Advanced_Testimonial_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/advanced-testimonial-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Advanced_Testimonial_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Advanced_Testimonial_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/advanced-testimonial-admin.js', array( 'jquery' ), $this->version, false );

	}

}
