<?php
/*
Widget Name: Control Panel
Description: Display an area for login and logout, forgot password and register.
Author: Philip M. Hofer (Frumph)
Author URI: http://frumph.net/
Version: 1.04
*/
class easel_control_panel_widget extends WP_Widget {

	function easel_control_panel_widget($skip_widget_init = false) {
		if (!$skip_widget_init) {
			$widget_ops = array('classname' => __CLASS__, 'description' => __('Login/Logoff menu with register/lost password links if not logged on. (use only if registrations are enabled.','easel') );
			$this->WP_Widget(__CLASS__, __('Control Panel','easel'), $widget_ops);
		}
	}
	
	function easel_show_control_panel() { 
		global $user_login;
		if (!is_user_logged_in()) { ?>
			<form action="<?php bloginfo('url') ?>/wp-login.php" method="post">
			<?php _e('UserName:','easel'); ?><br />
			<input type="text" name="log" id="sname" value="<?php echo esc_html(stripslashes($user_login), 1) ?>" size="22" /><br /><br />
			<?php _e('Password:','easel'); ?><br />
			<input type="password" name="pwd" id="spassword" size="22" /><br />
			<label for="rememberme"><input name="rememberme" id="rememberme" type="checkbox" checked="checked" value="forever" /> Remember me</label><br />
			<br />
			<button type="submit" class="button"><?php _e('Login','easel'); ?></button>
			<input type="hidden" name="redirect_to" value="<?php bloginfo('url'); ?>"/>
			</form>
			<br />
			<ul>
			<?php if (is_multisite()) { ?>
				<li><a href="<?php bloginfo('url') ?>/wp-signup.php"><?php _e('Register','easel'); ?></a></li>
			<?php } else { ?>
				<li><a href="<?php bloginfo('url') ?>/wp-register.php"><?php _e('Register','easel'); ?></a></li>
			<?php } ?>
			<li><a href="<?php bloginfo('url') ?>/wp-login.php?action=lostpassword"><?php _e('Recover password','easel'); ?></a></li>
			</ul>
		<?php } else { ?>
			<ul>
			<?php $redirect = '&amp;redirect_to='.urlencode(wp_make_link_relative(get_bloginfo('wpurl')));
			$uri = wp_nonce_url( site_url("wp-login.php?action=logout$redirect", 'login'), 'log-out' ); ?>
			<li><a href="<?php echo $uri; ?>"><?php _e('Logout','easel'); ?></a></li>
			<?php wp_register(); ?>
			<li><a href="<?php bloginfo('url'); ?>/wp-admin/profile.php"><?php _e('Profile','easel'); ?></a></li>
			</ul>
		<?php } ?>
		<?php
	} 	
		

	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		Protect();
		echo $before_widget;
		$title = empty($instance['title']) ? __('Control Panel','easel') : apply_filters('widget_title', $instance['title']); 
		if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };
		$this->easel_show_control_panel();
		echo $after_widget;
		UnProtect();
	}
	
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		return $instance;
	}
	
	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
		$title = strip_tags($instance['title']);
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','easel'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
		<?php
	}
}
register_widget('easel_control_panel_widget');

?>