<div class="admin-box">
	<h3>History</h3>
	<?php echo form_open($this->uri->uri_string()); ?>
		<table class="table table-striped">
			<thead>
				<tr>
					<?php if ($this->auth->has_permission('History.Reports.Delete') && isset($records) && is_array($records) && count($records)) : ?>
					<th class="column-check"><input class="check-all" type="checkbox" /></th>
					<?php endif;?>
					
					<th>Table</th>
					<th>Row</th>
					<th>Action</th>
					<th>Old</th>
					<th>New</th>
					<th>User</th>
					<th>Created</th>
					<th>IP</th>
				</tr>
			</thead>
			<?php if (isset($records) && is_array($records) && count($records)) : ?>
			<tfoot>
				<?php if ($this->auth->has_permission('History.Reports.Delete')) : ?>
				<tr>
					<td colspan="9">
						<?php echo lang('bf_with_selected') ?>
						<input type="submit" name="delete" id="delete-me" class="btn btn-danger" value="<?php echo lang('bf_action_delete') ?>" onclick="return confirm('<?php e(js_escape(lang('history_delete_confirm'))); ?>')">
					</td>
				</tr>
				<?php endif;?>
			</tfoot>
			<?php endif; ?>
			<tbody>
			<?php if (isset($records) && is_array($records) && count($records)) : ?>
			<?php foreach ($records as $record) : ?>
				<tr>
					<?php if ($this->auth->has_permission('History.Reports.Delete')) : ?>
					<td><input type="checkbox" name="checked[]" value="<?php echo $record->id ?>" /></td>
					<?php endif;?>
					
				<?php if ($this->auth->has_permission('History.Reports.Edit')) : ?>
				<td><?php echo anchor(SITE_AREA .'/reports/history/edit/'. $record->id, '<i class="icon-pencil">&nbsp;</i>' .  $record->table) ?></td>
				<?php else: ?>
				<td><?php e($record->table) ?></td>
				<?php endif; ?>
			
				<td><?php e($record->row) ?></td>
				<td><?php echo $record->action; ?></td>
				<td><?php e( strlen($record->old)>64 ? substr($record->old, 0, 61)."..." : $record->old ) ?></td>
				<td><?php e( strlen($record->new)>64 ? substr($record->new, 0, 61)."..." : $record->new ) ?></td>
				<td><?php e($record->user_id) ?></td>
				
				<td><?php e($record->created_on) ?></td>
				<td><?php e($record->ip_address) ?></td>
				</tr>
			<?php endforeach; ?>
			<?php else: ?>
				<tr>
					<td colspan="9">No records found that match your selection.</td>
				</tr>
			<?php endif; ?>
			</tbody>
		</table>
	<?php echo form_close(); ?>
</div>