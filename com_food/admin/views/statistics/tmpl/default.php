<div class="row-fluid">
	<div class="span12 thumbnail">
		<h3>
			<span class="label label-info pull-right"><?= $this->stats['total_books']; ?></span>
			<?= JText::_('COM_FOOD_TITLE_BLOCK'); ?>
		</h3>
		<p><?= JText::_('COM_FOOD_DESC_BLOCK'); ?></p>
		<div class="">
			<table class="table table-bordered table-food">
				<thead>
					<tr>
						<th><?= JText::_('COM_FOOD_TABLE_NAME'); ?></th>
						<th style="width: 1%;" class="nowrap"><?= JText::_('COM_FOOD_TABLE_PERMISION'); ?></th>
						<th style="width: 1%;" class="nowrap"><?= JText::_('COM_FOOD_TABLE_CHANGE'); ?></th>
						<th style="width: 1%;" class="nowrap"><?= JText::_('COM_FOOD_TABLE_SIZE'); ?></th>
						<th style="width: 1%;"><?= JText::_('COM_FOOD_TABLE_PARAMETERS'); ?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="nowrap" colspan="5"><?= JText::_('COM_FOOD_TABLE_NOT_FOUND'); ?></td>
					</tr>
				</tbody>
			</table>
		</div>
		<p><?= JText::_('COM_FOOD_DESC_BLOCK'); ?></p>
		<p>Root: <?= JPATH_ROOT; ?></p>
	</div>
	<link rel="stylesheet" type="text/css" href="<?= JURI::base(false); ?>components/com_food/assets/css/main.min.css">
	<script type="text/javascript" src="<?= JURI::base(false); ?>components/com_food/assets/js/main.min.js">"></script>
</div>