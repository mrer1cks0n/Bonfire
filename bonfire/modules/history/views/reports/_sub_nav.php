<ul class="nav nav-pills">
	<li <?php echo $this->uri->segment(4) == '' ? 'class="active"' : '' ?>>
		<a href="<?php echo site_url(SITE_AREA .'/reports/history') ?>" id="list"><?php echo lang('history_list'); ?></a>
	</li>
	<?php if ($this->auth->has_permission('History.Reports.Create')) : ?>
	<li <?php echo $this->uri->segment(4) == 'create' ? 'class="active"' : '' ?> >
		<a href="<?php echo site_url(SITE_AREA .'/reports/history/create') ?>" id="create_new"><?php echo lang('history_new'); ?></a>
	</li>
	<?php endif; ?>
</ul>