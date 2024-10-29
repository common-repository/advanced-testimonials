<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.codeincept.com
 * @since      1.0.0
 *
 * @package    Advanced_Testimonial
 * @subpackage Advanced_Testimonial/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Advanced_Testimonial
 * @subpackage Advanced_Testimonial/public
 * @author     CodeIncept <codeincept@gmail.com>
 */
class CI_Advanced_Testimonial_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		//add_shortcode( 'advanced_testimonials', array($this, 'advanced_testimonials_shortcode_callback' ));
		add_shortcode( 'advanced_testimonials_slider', array($this, 'advanced_testimonials_shortcode_callback' ));

	}
	public function advanced_testimonials_shortcode_callback($atts){
		wp_enqueue_style( 'slick');
		wp_enqueue_style('slick-theme');
		wp_enqueue_script( 'slick');
			$atts=shortcode_atts(
				array(
				'items'		=>	10,
				'columns'	=>	1,
				'style'		=>	'style-2',
				'cat_id'	=>	'all',
				'arrows'	=>	true,
				'autoplay'	=>	true,
				'navigation'=>	true,
				'showimage'	=>	1,
				)
			, $atts);			

			$args = array(
	          'post_type' => 'advtestimonials',
	          'post_status'=> 'publish',
	          'posts_per_page'=> $atts['items'],
	          'tax_query' => array(
				    array(
				        'taxonomy' => 'testimonial_category',
				        'field'    => 'term_id',
				        'terms'    => $atts['cat_id'],
				        ),
				    ),
	        );
	        $loop = new WP_Query( $args );
	      	if ( $loop->have_posts()):
	      		?>
	      	<div class="advanced_testimonials">
	      	  <div class="advtestimonial_carousel">
	      	<?php while($loop->have_posts()): $loop->the_post(); 
	      	
	      	?>
	      	<div>
	      	<?php 
	      	switch ($atts['style']) {
	      		case 'style-1':
	      			$atts['class']='testimonials-default';
	      			$this->testimonials_default($atts);
	      			break;
	      		case 'style-2':
	      			$atts['class']='top-image-below-caption-simple';
	      			$this->top_image_below_caption_simple($atts);
	      			break;
	      		default:
	      			$atts['class']='testimonials-default';
	      			$this->testimonials_default($atts);
	      		break;
	      	}
	      	 ?>
	      </div>
	      <?php endwhile; ?>
		  </div>
		</div>
		  <script type="text/javascript">
		  jQuery(document).ready(function($){
		  	jQuery('.advtestimonial_carousel').slick({
		  		autoplay: <?php echo ($atts['autoplay']==true) ? 'true' : 'false'; ?>,
		  		autoplaySpeed: 2000,
		  		slidesToShow: '<?php echo $atts["columns"]; ?>',
		  		slidesToScroll: 1,
			  	arrows: <?php echo ($atts['arrows']==true) ? 'true' : 'false'; ?>,
			  	infinite: true,
			  	dots: <?php echo ($atts['navigation']==true) ? 'true' : 'false'; ?>,
			  	prevArrow:"<button type='button' class='slick-prev pull-left'><i class='fa fa-angle-left' aria-hidden='true'></i></button>",
            nextArrow:"<button type='button' class='slick-next pull-right'><i class='fa fa-angle-right' aria-hidden='true'></i></button>",
			  
			  	responsive: [
			  		{
			      breakpoint: 1025,
			      settings: {
			        slidesToShow: 1,
			        slidesToScroll: 1,
			        infinite: true,
			      }
			    },
			    {
			      breakpoint: 767,
			      settings: {
			        slidesToShow: 1,
			        slidesToScroll: 1
			      }
			    },
			    {
			      breakpoint: 480,
			      settings: {
			        slidesToShow: 1,
			        slidesToScroll: 1
			      }
			    }
			  	]
		  	});
		  });
	  </script>
      	<?php
      	wp_reset_postdata();
      	else:
      		echo __('No testimonials found.','advanced-testimonial');
  		endif;
	}
	
	public function testimonials_default($atts){
		$post_id=get_the_ID();
		$meta=get_post_meta($post_id,'advanced_testimonial_options',true);
		?>
		<div class="<?php echo $atts['class']; ?>">
	      		<div class="article-caption">
		      		<?php the_content(); ?>
		      		<div class="article-image">
	      			<?php if($atts['showimage']==1 && has_post_thumbnail($post_id) ){ ?>
	      			<div class="testimonials_image">
	      			<?php the_post_thumbnail('thumbnail'); ?>
		      		</div>
		      		<?php } ?>
		      		<div class="testimonial_details">
		      			<h3 class="testimonial_title"><?php echo $meta['author']; ?></h3>
		      		<h4 class="testimonial_designation"><?php echo $meta['designation']; ?></h4>
		      		</div>
	      		</div>
	      		</div>
	      		
	      	</div>
		<?php
	}
	public function top_image_below_caption_simple($atts){
		$meta=get_post_meta(get_the_ID(),'advanced_testimonial_options',true);
		?>
		<div class="<?php echo $atts['class']; ?>">
			<?php if($atts['showimage']==1 &&  has_post_thumbnail($post_id)){ ?>
	      		<div class="article-image">
	      			<div class="testimonials_image">
	      			<?php the_post_thumbnail('thumbnail'); ?>
		      		</div>
	      		</div>
	      		<?php } ?>
	      		<div class="article-caption">
		      		<?php the_content(); ?>
		      		<h3 class="testimonial_title"><?php echo $meta['author']; ?></h3>
		      		<h4 class="testimonial_designation"><?php echo $meta['designation']; ?></h4>
		      	</div>
	      	</div>
	      	<?php
	}
	/**
	 * Register the stylesheets for the public-facing side of the site.
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
		wp_register_style( 'slick', plugin_dir_url( __FILE__ ) . 'css/slick.css', array(), $this->version, 'all' );
		wp_register_style('slick-theme', plugin_dir_url( __FILE__ ) . 'css/slick-theme.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/advanced-testimonial-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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
		wp_register_script( 'slick', plugin_dir_url( __FILE__ ) . 'js/slick.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/advanced-testimonial-public.js', array( 'jquery' ), $this->version, false );

	}

}
