<?php 
	if (!empty($_GET['action']) && $_GET['action']=='add') :
?>
	<div class="card">
		<form method="post">
			<input type="hidden" name="form-type" value="add">
			<table>
				<tr>
					<td><label for="name">Name</label></td>
					<td><input type="text" name="name" id="name" /></td>
				</tr>
				<tr>
					<td><label for="location">Location</label></td>
					<td>
					<select name="location" id="location">
						<?php 
							foreach (trackitController::$LOCATIONS as $key => $value) :
						?>
						<option value="<?=$key ?>"><?=$value ?></option>
						<?php 
							endforeach;
						?>
					</select>
					</td>
				</tr>
				<tr>
					<td><label for="code">Code</label></td>
					<td><textarea name="code" id="code"></textarea></td>
				</tr>
				<tr>
					<td><input type="submit" name="submit" class="button button-primary" value="Add" /></td>
				</tr>
			</table>
		</form>
	</div>
<?php 
	elseif (!empty($_GET['action']) && $_GET['action']=='edit') :
?>
	<div class="card">
		<form method="post">
			<input type="hidden" name="form-type" value="edit">
			<input type="hidden" name="confirm" value="1">
			<table>
				<tr>
					<td><label for="name">Name</label></td>
					<td><input type="text" name="name" id="name" value="<?=$name ?>" /></td>
				</tr>
				<tr>
					<td><label for="location">Location</label></td>
					<td>
					<select name="location" id="location">
						<?php 
							foreach (trackitController::$LOCATIONS as $key => $value) :
							$selected = ($key == $location)? 'selected' : '';
						?>
						<option value="<?=$key ?>" <?=$selected ?> ><?=$value ?></option>
						<?php 
							endforeach;
						?>
					</select>
					</td>
				</tr>
				<tr>
					<td><label for="code">Code</label></td>
					<td><textarea name="code" id="code"><?=$code ?></textarea></td>
				</tr>
				<tr>
					<td><input type="submit" name="submit" class="button button-primary" value="Update" /></td>
				</tr>
			</table>
		</form>
	</div>
<?php 
	elseif (!empty($_GET['action']) && $_GET['action']=='delete') :
?>
	<div class="card">
		<p>Are you sure you want to delete "<?=$name ?>" ?</p>
		<a class="button" href="<?=trackitController::getPluginURL() ?>">No</a>
		<a class="button" href="<?=trackitController::getPluginURL() ?>&action=delete&id=<?=$id ?>&confirm=1">Yes</a>
	</div>
<?php 
	endif;
?>