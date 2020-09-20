<!--MODAL-->
	<div id="ex3" class="modal">
		<p>Вы уверены?</p>
		<form action="../create_update_delete/delete_tableTariffs.php" method="post">
			<input type="hidden" name="popup_id" id="popup_id" value="">
			<input type="hidden" name="popup_table" id="popup_table" value="">
			<input type="hidden" name="services" id="services" value="">
			<input type="hidden" name="regions" id="regions" value="">
			<input type="submit" value="Да">
		</form>
		<a href="#" rel="modal:close">Нет</a>
	</div>