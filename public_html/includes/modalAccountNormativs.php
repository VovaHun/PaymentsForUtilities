<!--MODAL-->
	<div id="ex3" class="modal">
		<p>Вы уверены?</p>
		<form action="../create_update_delete/delete_accountnormatives.php" method="post">
			<input type="hidden" name="normAccountId" id="normAccountId" value="">
			<input type="hidden" name="norm_table" id="norm_table" value="">
			<input type="hidden" name="serviceId" id="serviceId" value="">
			<input type="hidden" name="tariffId" id="tariffId" value="">
			<input type="hidden" name="dateId" id="dateId" value="">
			<input type="submit" value="Да">
		</form>
		<a href="#" rel="modal:close">Нет</a>
	</div>