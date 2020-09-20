<!--MODAL-->
	<div id="ex3" class="modal">
		<p>Вы уверены?</p>
		<form action="../create_update_delete/delete_modelfunctions.php" method="post">
			<input type="hidden" name="modelId" id="modelId" value="">
			<input type="hidden" name="popup_table" id="popup_table" value="">
			<input type="hidden" name="tariffId" id="tariffId" value="">
			<input type="submit" value="Да">
		</form>
		<a href="#" rel="modal:close">Нет</a>
	</div>