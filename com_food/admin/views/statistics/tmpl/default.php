<div class="row-fluid">
	<div class="span12 thumbnail">
		<h3>
			<span class="label label-info pull-right"><?= $this->stats['total_books']; ?></span>
			<?= JText::_('COM_FOOD_TITLE_BLOCK'); ?>
		</h3>
		<div class="">
			Вывод формы и описания
		</div>
		<div class="">
			<table class="table table-bordered">
				<thead>
					<tr>
						<th>Имя файла</th>
						<th>Права</th>
						<th>Изменен</th>
						<th>Размер файла</th>
						<th>Параметры</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td colspan="5">5</td>
					</tr>
				</tbody>
			</table>
		</div>
		<p><?= JText::_('COM_FOOD_DESC_BLOCK'); ?></p>
		<p>Root: <?= JPATH_ROOT; ?></p>
	</div>
</div>