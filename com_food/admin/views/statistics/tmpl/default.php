<div class="row-fluid">
	<div class="span12 thumbnail">
		<h3>
			<span class="label label-info pull-right"><?= $this->stats['total_books']; ?></span>
			<?= JText::_('COM_FOOD_TITLE_BLOCK'); ?>
		</h3>
		<p><?= $this->stats['jversion'];?></p>
		<p><?= $this->stats["com_food_path"];?></p>
		<div class="">
			<div class="">
				<pre><code><?= print_r($this->stats, true);?></code></pre>
			</div>
			<div class="">
				<form>
				</form>
			</div>
		</div>
		<div class="folder-title">
			<h3><?= $this->stats["food_title"] ? JText::_('COM_FOOD_DIR') . ' /' . $this->stats["food_title"] . '/' : JText::_('COM_FOOD_DIR_ROOT'); ?></h3>
		</div>
		<div class="food-table">
			<div class="table-responsive">
				<table class="table table-bordered table-food">
					<thead>
						<tr>
						<?php if($this->stats["dir"]):?>
							<th><?= JText::_('COM_FOOD_TABLE_NAME'); ?></th>
							<th style="width: 1%;" class="nowrap"><?= JText::_('COM_FOOD_TABLE_PERMISION'); ?></th>
							<th style="width: 1%;" class="nowrap"><?= JText::_('COM_FOOD_TABLE_CHANGE'); ?></th>
							<th style="width: 1%;" class="nowrap"><?= JText::_('COM_FOOD_TABLE_SIZE'); ?></th>
							<th style="width: 1%;"><?= JText::_('COM_FOOD_TABLE_PARAMETERS'); ?></th>
						<?php else: ?>
							<th class="nowrap" colspan="5"><?= JText::_('COM_FOOD_TABLE_TITLE_NOT_FOUND'); ?></th>
						<?php endif; ?>
						</tr>
					</thead>
					<tbody>
					<?php if($this->stats["files"] && $this->stats["dir"]):?>
						<tr>
						<?php foreach ($variable as $key => $value): ?>
							// code...
						<?php endforeach; ?>
						</tr>
					<?php else: ?>
						<?php if($this->stats["dir"]):?>
						<tr><td class="nowrap" colspan="5"><?= JText::_('COM_FOOD_TABLE_NOT_FOUND'); ?></td></tr>
						<?php else: ?>
						<?php foreach($this->stats["com_food_params"] as $key => $value): ?>
						<tr><td class="nowrap" colspan="5"><?= $value; ?></td></tr>
						<?php endforeach; ?>
						<?php endif; ?>
					<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<link rel="stylesheet" type="text/css" href="<?= $this->stats["com_food_path"]; ?>assets/css/main.min.css?<?= filemtime(JPATH_COMPONENT_ADMINISTRATOR . "/assets/css/main.min.css");?>">
	<script type="text/javascript" src="<?= $this->stats["com_food_path"]; ?>assets/js/jquery.min.js?<?= filemtime(JPATH_COMPONENT_ADMINISTRATOR . "/assets/js/jquery.min.js");?>"></script>
	<script type="text/javascript" src="<?= $this->stats["com_food_path"]; ?>assets/js/main.min.js?<?= filemtime(JPATH_COMPONENT_ADMINISTRATOR . "/assets/js/main.min.js");?>"></script>
</div>