<div class="row-fluid">
	<div class="span12 thumbnail">
		<div class="container-fluid">
			<h2>
				<span class="label label-info pull-right"><?= $this->stats['total_books']; ?></span>
				<?= JText::_('COM_FOOD_TITLE_BLOCK'); ?>
			</h2>
			<div class="">
				<div class=""></div>
				<div class="">
					<form></form>
				</div>
			</div>
			<div class="folder-title">
				<h4><?= $this->stats["food_title"] ? JText::_('COM_FOOD_DIR') . ' /' . $this->stats["food_title"] . '/' : JText::_('COM_FOOD_DIR_ROOT'); ?></h4>
				<?= $this->stats["food_title"] ? '<p class="food-title-root"><i class="glyphicon glyphicon-folder-open"></i>&nbsp;<a href="index.php?option=com_food">' . JText::_('COM_FOOD_DIR_TOP') . '</a></p>' : ''; ?>
			</div>
			<div class="food-table">
				<div class="table-responsive">
					<table class="table table-bordered table-hover table-food">
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
							<?php foreach ($this->stats["files"] as $key => $value): ?>
							<tr>
								<td><i class="glyphicon glyphicon-file"></i>&nbsp;<?= $value; ?></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<?php endforeach; ?>
						<?php else: ?>
							<?php if($this->stats["dir"]):?>
							<tr>
								<td class="nowrap" colspan="5"><?= JText::_('COM_FOOD_TABLE_NOT_FOUND'); ?></td>
							</tr>
							<?php else: ?>
								<?php foreach($this->stats["com_food_params"] as $key => $value): ?>
							<tr>
								<td class="nowrap" colspan="5"><i class="glyphicon glyphicon-folder-open"></i>&nbsp;<a href="index.php?option=com_food&dir=<?= $value; ?>"><?= $value; ?></a></td>
							</tr>
								<?php endforeach; ?>
							<?php endif; ?>
						<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
			<p><?= $this->stats['jversion'];?></p>
			<p><?= $this->stats["com_food_path"];?></p>
			<pre><code><?= print_r($this->stats, true);?></code></pre>
		</div>
	</div>
</div>
<link rel="stylesheet" type="text/css" href="<?= $this->stats["com_food_path"]; ?>assets/css/main.min.css?<?= filemtime(JPATH_COMPONENT_ADMINISTRATOR . "/assets/css/main.min.css");?>">
<script type="text/javascript" src="<?= $this->stats["com_food_path"]; ?>assets/js/jquery.min.js?<?= filemtime(JPATH_COMPONENT_ADMINISTRATOR . "/assets/js/jquery.min.js");?>"></script>
<script type="text/javascript" src="<?= $this->stats["com_food_path"]; ?>assets/js/main.min.js?<?= filemtime(JPATH_COMPONENT_ADMINISTRATOR . "/assets/js/main.min.js");?>"></script>