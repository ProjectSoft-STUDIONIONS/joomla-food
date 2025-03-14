<div class="clearfix">
	<script type="text/javascript">
		window.MAX_COUNT_FILE = <?= ini_get("max_file_uploads");?>;
	</script>
	<div class="row clearfix">
		<div class="container-fluid clearfix">
			<h2>
				<span class="label label-info pull-right"><?= $this->stats['total_books']; ?></span>
				<?= JText::_('COM_FOOD_TITLE_BLOCK'); ?>
			</h2>
			<div class="clearfix">
				<?php if($this->stats["dir"]):?>
				<form class="text-right" name="upload" method="post" action="index.php?option=com_food&dir=<?= $this->stats["dir"];?>" enctype="multipart/form-data">
					<input type="hidden" name="mode" value="upload">
					<div id="uploader" class="text-right">
						<label class="btn btn-secondary text-uppercase">
							<i class="glyphicon glyphicon-floppy-save"></i> Select files
							<input type="file" name="userfiles[]" onchange="uploadFiles(this);" multiple accept=".xlsx,.pdf">
						</label>
						<p id="p_uploads" class="alert alert-info"></p>
						<a class="btn btn-success text-uppercase" href="javascript:;" onclick="document.upload.submit()"><i class="glyphicon glyphicon-cloud-upload"></i> Upload</a>
					</div>
				</form>
				<?php endif;?>
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
							<?php foreach ($this->stats["files"] as $key => $value):
								$tmp_file = $this->realPath(JPATH_ROOT) . "/" . $this->stats["dir"] . "/" . $value;
								$ltime = $this->toDateFormat(filemtime($tmp_file));
								$size = $this->getSize($tmp_file);
								$perms = substr(sprintf('%o', fileperms($tmp_file)), -4);
							?>
							<tr>
								<td><i class="glyphicon glyphicon-file"></i>&nbsp;<a href="/<?= $this->stats["dir"] . "/" . $value; ?>" target="_blank"><?= $value; ?></a></td>
								<td><?= $perms; ?></td>
								<td><?= $ltime; ?></td>
								<td><?= $size; ?></td>
								<td><!-- Переименовать, Удалить -->
									<div class="flex">
										<i class="btn btn-secondary glyphicon glyphicon-edit" data-mode="rename" data-file="<?= $value; ?>" title='Переименовать "<?= $value; ?>"'></i>
										<i class="btn btn-secondary glyphicon glyphicon-trash" data-mode="delete" data-file="<?= $value; ?>" title='Удалить "<?= $value; ?>"'></i>
									</div>
								</td>
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
								<td class="nowrap" colspan="4"><i class="glyphicon glyphicon-folder-open"></i>&nbsp;<a href="index.php?option=com_food&dir=<?= $value; ?>"><?= $value; ?></a></td>
								<td style="width: 1%;" class="nowrap"><a href="/<?= $value; ?>/" target="_blank"><i class="glyphicon glyphicon-new-window"></i></a></td>
							</tr>
								<?php endforeach; ?>
							<?php endif; ?>
						<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
			<pre><code><?= print_r($this->stats, true);?></code></pre>
		</div>
	</div>
</div>
<link rel="stylesheet" type="text/css" href="/viewer/app.min.css?<?= filemtime($this->realPath(JPATH_ROOT) . "/viewer/app.min.css");?>">
<link rel="stylesheet" type="text/css" href="<?= $this->stats["com_food_path"]; ?>assets/css/main.min.css?<?= filemtime($this->realPath(JPATH_COMPONENT_ADMINISTRATOR) . "/assets/css/main.min.css");?>">
<script type="text/javascript" src="<?= $this->stats["com_food_path"]; ?>assets/js/jquery.min.js?<?= filemtime($this->realPath(JPATH_COMPONENT_ADMINISTRATOR) . "/assets/js/jquery.min.js");?>"></script>
<script type="text/javascript" src="/viewer/fancybox.min.js?<?= filemtime($this->realPath(JPATH_ROOT) . "/viewer/fancybox.min.js");?>"></script>
<script type="text/javascript" src="/viewer/app.min.js?<?= filemtime($this->realPath(JPATH_ROOT) . "/viewer/app.min.js");?>"></script>
<script type="text/javascript" src="<?= $this->stats["com_food_path"]; ?>assets/js/main.min.js?<?= filemtime($this->realPath(JPATH_COMPONENT_ADMINISTRATOR) . "/assets/js/main.min.js");?>"></script>