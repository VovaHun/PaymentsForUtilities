<!--MODAL-->
	<div id="ex1" class="modal">
		<p>Вы уверены?</p>
		<div class="modal_buttons">
    		<form action="../create_update_delete/delete_table.php" method="post">
    			<input type="hidden" name="popup_id" id="popup_id" value="">
    			<input type="hidden" name="popup_table" id="popup_table" value="">
    			<input type="submit" value="Да">
    		</form>
    		<a rel="modal:close">Нет</a>
		</div>
	</div>