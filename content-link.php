<?php
if (!is_home() && !is_archive() && !is_search()) { easel_display_post_thumbnail('large'); ?><div class="clear"></div><?php } 
?>
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="post-head"><?php do_action('easel-post-head'); ?></div>
	<div class="post-content">
		<?php if (is_home() || is_archive() || is_search()) easel_display_post_thumbnail('thumbnail'); ?>
		<div class="entry">
			<?php echo nl2br(get_the_content()); ?>
			<div class="clear"></div>
		</div>
		<?php edit_post_link(__('Edit this post.','easel'), '', ''); ?>
		<div class="clear"></div>
	</div>
	<div class="post-foot"><?php do_action('easel-post-foot'); ?></div>
	<div class="clear"></div>
</div>
<?php 
comments_template('', true);
