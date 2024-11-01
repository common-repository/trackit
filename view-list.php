<a href="<?=trackitController::getPluginURL() ?>&action=add" class="button button-primary">Add</a>
<?php 
	if (!empty($results)) {
		$trackitList = new trackitList();
		$trackitList->prepare($results);
		$trackitList->display();
	}
?>