<div id="content-wrapper-head"><?php do_action('easel-content-wrapper-head'); ?></div>
<div id="content-wrapper">
	
	<?php do_action('easel-content-area'); ?>
	
	<div id="subcontent-wrapper-head"><?php do_action('easel-subcontent-wrapper-head'); ?></div>
	<div id="subcontent-wrapper">
		<?php do_action('easel-subcontent-wrapper'); ?>
		<div id="column-head"></div>
		<?php if (easel_is_signup() || is_page('chat')) { ?>
			<div id="column" class="widecolumn">
		<?php } else { ?>
			<?php get_sidebar('left'); ?>
			<div id="column" class="narrowcolumn">
		<?php } ?>
		<?php do_action('easel-narrowcolumn-area'); ?>
		<?php easel_get_sidebar('over-blog'); ?>