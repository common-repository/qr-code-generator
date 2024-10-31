<?php
/*
Plugin Name: QR code generator
Plugin URI: http://www.blue-design.ch/qr-code-generator/
Description: This plugin will display a QR code in your sidebar which you can easily scan with your handphone and read later on the way home, to office or to school.
Version: 0.2
Author: Marco Wagner
Author URI: http://www.blue-design.ch
License: GNU General Public License Version 2
*/

/*  Copyright 2012 MARCO WAGNER

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/



class qr_code_generator extends WP_Widget {

	function qr_code_generator() {
		// Instantiate the parent object
		parent::__construct(
			false, // Base ID
			'QR Code', // Name
			array( 'description' => __( 'Displays a QR Code in the sidebar', 'qr-code-generator' ), ) // Args
		);
	}

	function widget( $args, $instance ) {
		// Widget output
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		$qrsize = $instance['size'] . 'x' . $instance['size'];
		$current_post_id = get_the_id();
		$qr_url = wp_get_shortlink( $current_post_id );


		echo $before_widget;
		if ( ! empty( $title ) )
			echo $before_title . $title . $after_title;
		
		_e( 'Scan this code with your handphone:', 'qr-code-generator' );
		echo "<br>";

		if ( is_front_page() )
			$qr_url = site_url();
		
		printf( '<img src="https://chart.googleapis.com/chart?chs=%1$s&cht=qr&chl=%2$s&chld=L|0" border="0">', $qrsize, urlencode($qr_url) );
		
		// Show some debug information
		if ( WP_DEBUG ) {
			echo "<p>";
			_e('This block is only being showed because your site has <a href=\'http://codex.wordpress.org/WP_DEBUG\' target=\'_blank\'>WP_DEBUG</a> enabled.<br>','qr-code-generator');
			printf( 'Post ID: %1$d<br>Size: %2$s<br>URL: %3$s', $current_post_id, $qrsize, $qr_url );
			echo "</p>";
		}
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		// Save widget options
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['size'] = $new_instance['size'];

		return $instance;
	}

	function form( $instance ) {
		// Output admin widget options form
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'QR Code', 'qr-code-generator' );
		}

		if ( isset( $instance[ 'size' ] ) ) {
			$size = $instance[ 'size' ];
		}
		else {
			$size = "100";
		}
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'qr-code-generator'); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'size' ); ?>"><?php _e( 'Size of the QR code in pixels:', 'qr-code-generator'); ?></label>
			<select id="<?php echo $this->get_field_id( 'size' ); ?>" name="<?php echo $this->get_field_name( 'size' ); ?>" class="widefat">
				<option <?php if ( '50' == $instance['size'] ) echo 'selected="selected"'; ?> value="50">50 x 50</option>
                <option <?php if ( '75' == $instance['size'] ) echo 'selected="selected"'; ?> value="75">75 x 75</option>
                <option <?php if ( '100' == $instance['size'] ) echo 'selected="selected"'; ?> value="100">100 x 100</option>
				<option <?php if ( '150' == $instance['size'] ) echo 'selected="selected"'; ?> value="150">150 x 150</option>
			</select>
		</p>
		<?php
	}
}

function qr_code_generator_register_widgets() {
	register_widget( 'qr_code_generator' );
}

add_action( 'widgets_init', 'qr_code_generator_register_widgets' );
?>