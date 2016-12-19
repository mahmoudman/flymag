<?php

/**
 * Recent posts type C widget
 */

class Flymag_Recent_C extends WP_Widget {

	public function __construct() {
		$widget_ops = array( 'classname' => 'recent_posts_c clearfix', 'description' => __( 'Recent posts widget Type C - two categories (front page)', 'flymag' ) );
		parent::__construct( 'recent_posts_c', __( 'Flymag: Recent posts type C', 'flymag' ), $widget_ops );
		$this->alt_option_name = 'recent_posts_c';

		add_action( 'save_post', array( $this, 'flush_widget_cache' ) );
		add_action( 'deleted_post', array( $this, 'flush_widget_cache' ) );
		add_action( 'switch_theme', array( $this, 'flush_widget_cache' ) );
	}

	public function widget( $args, $instance ) {
		$cache = array();
		if ( ! $this->is_preview() ) {
			$cache = wp_cache_get( 'recent_posts_c', 'widget' );
		}

		if ( ! is_array( $cache ) ) {
			$cache = array();
		}

		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		if ( isset( $cache[ $args['widget_id'] ] ) ) {
			echo $cache[ $args['widget_id'] ];
			return;
		}

		ob_start();

		$cat_one 	= isset( $instance['cat_one'] ) ? esc_attr( $instance['cat_one'] ) : '';
		$cat_two 	= isset( $instance['cat_two'] ) ? esc_attr( $instance['cat_two'] ) : '';
		$bg_color 	= isset( $instance['bg_color'] ) ? esc_attr( $instance['bg_color'] ) : '';
		$text_color	= isset( $instance['text_color'] ) ? esc_attr( $instance['text_color'] ) : '';

		$left_query = new WP_Query( array(
			'posts_per_page'      => 4,
			'no_found_rows'       => true,
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true,
			'category_name' 	  => $cat_one,

		) );

		$right_query = new WP_Query( array(
			'posts_per_page'      => 4,
			'no_found_rows'       => true,
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true,
			'category_name' 	  => $cat_two,

		) );

?>
		<?php echo $args['before_widget']; ?>

		<div class="widget-inner clearfix" style="background-color: <?php echo $bg_color; ?>; color: <?php echo $text_color; ?>">

		<div class="col-md-6 col-sm-6 col-xs-12">

		<?php $cat = get_term_by( 'slug', $cat_one, 'category' ) ?>
		<?php if ( $cat ) {
			echo '<h3 class="cat-title"><a style="color:' . $text_color . '" href="' . esc_url( get_category_link( get_cat_ID( $cat -> name ) ) ) . '">' . $cat -> name . '</a></h3>';
} ?>

		<?php $counter = 1; ?>	
		<?php while ( $left_query->have_posts() ) : $left_query->the_post(); ?>

		<?php if ( $counter == 1 ) : ?>
			<div class="recent-post first-post clearfix">
			<?php if ( has_post_thumbnail() ) : ?>
				<div class="recent-thumb">
					<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" >
						<?php the_post_thumbnail( 'carousel-thumb' ); ?>
					</a>							
				</div>	
			<?php endif; ?>
				<div class="recent-content">					
					<?php the_title( sprintf( '<h3 class="entry-title"><a style="color:' . $text_color . '" href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>
					<div class="entry-meta" style="color: <?php echo $text_color; ?>">
						<?php flymag_posted_on(); ?>
						<?php flymag_post_first_cat(); ?>
					</div>					
					<?php the_excerpt(); ?>
				</div>
			</div>
		<?php else : ?>	
			<div class="recent-post clearfix">
				<div class="recent-thumb col-md-3 col-sm-3 col-xs-3">
					<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" >
						<?php if ( has_post_thumbnail() ) : ?>
							<?php the_post_thumbnail( 'carousel-thumb' ); ?>
						<?php else : ?>
							<?php echo '<img src="' . get_stylesheet_directory_uri() . '/images/placeholder.png"/>'; ?>
						<?php endif; ?>	
					</a>			
				</div>
				<div class="post-meta col-md-9 col-sm-9 col-xs-9">
					<?php the_title( sprintf( '<h4 class="entry-title"><a style="color:' . $text_color . '" href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h4>' ); ?>
					<div class="entry-meta" style="color: <?php echo $text_color; ?>">
						<?php flymag_posted_on(); ?>
					</div>		
				</div>			
			</div>
		<?php endif; ?>
		<?php  $counter++; ?>
		<?php endwhile; ?>
		</div>

		<div class="col-md-6 col-sm-6 col-xs-12">

		<?php $cat = get_term_by( 'slug', $cat_two, 'category' ) ?>
		<?php if ( $cat ) {
			echo '<h3 class="cat-title"><a style="color:' . $text_color . '" href="' . esc_url( get_category_link( get_cat_ID( $cat -> name ) ) ) . '">' . $cat -> name . '</a></h3>';
} ?>

		<?php $counter = 1; ?>	
		<?php while ( $right_query->have_posts() ) : $right_query->the_post(); ?>
		<?php if ( $counter == 1 ) : ?>
			<div class="recent-post first-post clearfix">
			<?php if ( has_post_thumbnail() ) : ?>
				<div class="recent-thumb">
					<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" >
						<?php the_post_thumbnail( 'carousel-thumb' ); ?>
					</a>							
				</div>	
			<?php endif; ?>
				<div class="recent-content">
					<?php the_title( sprintf( '<h3 class="entry-title"><a style="color:' . $text_color . '" href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>
					<div class="entry-meta" style="color: <?php echo $text_color; ?>">
						<?php flymag_posted_on(); ?>
						<?php flymag_post_first_cat(); ?>
					</div>					
					<?php the_excerpt(); ?>
				</div>
			</div>
		<?php else : ?>	
			<div class="recent-post clearfix">
				<div class="recent-thumb col-md-3 col-sm-3 col-xs-3">
					<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" >
						<?php if ( has_post_thumbnail() ) : ?>
							<?php the_post_thumbnail( 'carousel-thumb' ); ?>
						<?php else : ?>
							<?php echo '<img src="' . get_stylesheet_directory_uri() . '/images/placeholder.png"/>'; ?>
						<?php endif; ?>	
					</a>			
				</div>		
				<div class="post-meta col-md-9 col-sm-9 col-xs-9">
					<?php the_title( sprintf( '<h4 class="entry-title"><a style="color:' . $text_color . '" href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h4>' ); ?>
					<div class="entry-meta" style="color: <?php echo $text_color; ?>">
						<?php flymag_posted_on(); ?>
					</div>		
				</div>	
			</div>
		<?php endif; ?>
		<?php  $counter++; ?>
		<?php endwhile; ?>	

		</div>	

		<?php echo $args['after_widget']; ?>
<?php
		wp_reset_postdata();

if ( ! $this->is_preview() ) {
	$cache[ $args['widget_id'] ] = ob_get_flush();
	wp_cache_set( 'recent_posts_c', $cache, 'widget' );
} else {
	ob_end_flush();
}
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['cat_one'] 	= strip_tags( $new_instance['cat_one'] );
		$instance['cat_two'] 	= strip_tags( $new_instance['cat_two'] );
		$instance['bg_color'] 	= strip_tags( $new_instance['bg_color'] );
		$instance['text_color'] = strip_tags( $new_instance['text_color'] );
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset( $alloptions['recent_posts_c'] ) ) {
			delete_option( 'recent_posts_c' );
		}

		return $instance;
	}

	public function flush_widget_cache() {
		wp_cache_delete( 'recent_posts_c', 'widget' );
	}

	public function form( $instance ) {
		$cat_one  	= isset( $instance['cat_one'] ) ? esc_attr( $instance['cat_one'] ) : '';
		$cat_two  	= isset( $instance['cat_two'] ) ? esc_attr( $instance['cat_two'] ) : '';
		$bg_color 	= isset( $instance['bg_color'] ) ? esc_attr( $instance['bg_color'] ) : '';
		$text_color = isset( $instance['text_color'] ) ? esc_attr( $instance['text_color'] ) : '';
?>

		<p><label for="<?php echo $this->get_field_id( 'cat_one' ); ?>"><?php _e( 'Enter the slug for your first category.', 'flymag' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'cat_one' ); ?>" name="<?php echo $this->get_field_name( 'cat_one' ); ?>" type="text" value="<?php echo $cat_one; ?>" size="3" /></p>

		<p><label for="<?php echo $this->get_field_id( 'cat_two' ); ?>"><?php _e( 'Enter the slug for your second category.', 'flymag' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'cat_two' ); ?>" name="<?php echo $this->get_field_name( 'cat_two' ); ?>" type="text" value="<?php echo $cat_two; ?>" size="3" /></p>

		<p><label for="<?php echo $this->get_field_id( 'bg_color' ); ?>" style="display:block;"><?php _e( 'Background color', 'flymag' ); ?></label> 
		<input class="color-picker" type="text" id="<?php echo $this->get_field_id( 'bg_color' ); ?>" name="<?php echo $this->get_field_name( 'bg_color' ); ?>" value="<?php echo $bg_color; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'text_color' ); ?>" style="display:block;"><?php _e( 'Text color', 'flymag' ); ?></label> 
		<input class="color-picker" type="text" id="<?php echo $this->get_field_id( 'text_color' ); ?>" name="<?php echo $this->get_field_name( 'text_color' ); ?>" value="<?php echo $text_color; ?>" /></p>
	
<?php
	}
}
