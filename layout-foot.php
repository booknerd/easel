			<?php easel_get_sidebar('under-blog'); ?>
		</div>
		<div id="column-foot"></div>
		<?php if (!easel_is_signup() && !is_page('chat')) get_sidebar('right'); ?>
		<div class="clear"></div>
	</div>
	<div id="subcontent-wrapper-foot"><?php do_action('easel-subcontent-wrapper-foot'); ?></div>
</div>
<div id="content-wrapper-foot"><?php do_action('easel-content-wrapper-foot'); ?></div>
