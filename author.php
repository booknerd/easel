<?php 
get_header();

	if(get_query_var('author_name') ) {
		$curauth = get_user_by('slug', get_query_var('author_name'));
	} else {
		$curauth = get_userdata(get_query_var('author'));
	}
		if (empty($curauth)) { ?>
			<h2><?php _e('No such author.','easel'); ?></h2>
		<?php } else { ?>
		<div <?php post_class(); ?>>
			<div class="post-head"></div>
			<div class="post-content">
					<div class="userpage-avatar">
						<?php echo str_replace('photo', 'photo instant nocorner itxtalt', get_avatar($curauth->user_email, 64, easel_random_default_avatar($curauth->user_email), esc_attr($curauth->display_name, 1))); ?>
					</div>
					<div class="userpage-info">
						<div class="userpage-bio">
	<?php
		if($curauth->display_name)
			$authorname = $curauth->display_name;
		elseif($curauth->user_nickname)
			$authorname = $curauth->nickname;
		elseif($curauth->user_nicename)
			$authorname = $curauth->user_nicename;
		else
			$authorname = $curauth->user_login;
	?>
							<cite><?php echo $authorname; ?></cite><br />
							<?php _e('Registered on','easel'); ?> <?php echo date('l \\t\h\e jS \o\f M, Y',strtotime($curauth->user_registered)); ?><br />
							<br />
							<?php if (!empty($curauth->user_url)) { ?><?php _e('Website:','easel'); ?> <a href="<?php echo $curauth->user_url; ?>" target="_blank"><?php echo $curauth->user_url; ?></a><br /><?php } ?>
							<?php if (!empty($curauth->aim)) { ?><?php _e('AIM:','easel'); ?> <a href="<?php echo $curauth->user_aim; ?>" target="_blank"><?php echo $curauth->aim; ?></a><br /><?php } ?>
							<?php if (!empty($curauth->jabber)) { ?><?php _e('Jabber/Google Talk:','easel'); ?> <a href="<?php echo $curauth->jabber; ?>" target="_blank"><?php echo $curauth->jabber; ?></a><br /><?php } ?>
							<?php if (!empty($curauth->yim)) { ?><?php _e('Yahoo IM:','easel'); ?> <a href="<?php echo $curauth->jabber; ?>" target="_blank"><?php echo $curauth->jabber; ?></a><br /><?php } ?>
						</div>
						<?php if (!empty($curauth->description)) { ?>
						<div class="userpage-desc">
							<?php echo $curauth->description; ?>
						</div>
						<?php } ?>
					</div>
					<div class="clear"></div>
					<div class="userpage-posts">
						<?php if (have_posts()) { ?>
							<h3><?php _e('Posts by','easel'); ?> <?php echo $authorname; ?> (<?php echo get_usernumposts($curauth->ID); ?>) &not;</h3>
							<?php // this area is a loop that shows what posts the person has done. ?>
							<ol>
									<li><table class="month-table">
							<?php while (have_posts()) : the_post() ?>
									<tr><td class="archive-date" align="right"><?php the_time('M j, Y') ?></td><td class="archive-title"><a href="<?php the_permalink(); ?>"><?php the_title() ?></a></td>
													
							<?php endwhile; ?>
									</table></li>
							</ol>
							
							<?php easel_pagination(); ?>
						
						<?php } ?>
					</div>
				</div>
				<div class="post-foot"></div>
			</div>
		<?php } ?>

<?php get_footer(); ?>