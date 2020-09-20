<?php
    require $_SERVER['DOCUMENT_ROOT'].'/ManagementCompany/_header.php';
?>
<body>
	<?php
		require $_SERVER['DOCUMENT_ROOT'].'/includes/table_names.php';
		require $_SERVER['DOCUMENT_ROOT'].'/ManagementCompany/_admin_header.php';
		require $_SERVER['DOCUMENT_ROOT'].'/includes/names_rus.php';
        
		if (!isset($_GET["table"])) {
			$active_table = "Contractors";
		}
		else $active_table = $_GET["table"];
	?>
	
	<div class="header_bottom">
		<div class="container head_bot" style="background-color: inherit;">
			<h2>Административная панель</h2>
			<h2>
				<?php  
					$query = "SELECT ManagementCompany.FullName FROM ManagementCompany WHERE ManagementCompany.CompanyId = '".$company_admin['CompanyId']."' ";
					$result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link));
					
					while($row = mysqli_fetch_array($result))
					{
						echo $row['FullName'];
					}
				?>
			</h2>
		</div>
	</div>
	
	<div class="main">
		<div class="container content">
			<div class="main__left">
				<div class="container">
					<div class="title_wrapper">
						<div class="content__title">
							<h5><?php echo $names_rus[$active_table]?></h5>
						</div>
						<div class="content__tables">
							<?php
								require $_SERVER['DOCUMENT_ROOT'].'/ManagementCompany/query_tables_names_dropdown.php';
							?>
						</div>
					</div>
					<?php if($active_table == "Profit"){ ?>
    					<div class="wrapper_button">
                            <a id="profit_button" href='./create_update_forms/form_profit.php' class="confirm">Рассчитать</a>
                        </div>
                    <?php } ?>
					<div class="content__body" style="display: flex">
						<?php
							switch ($active_table) 
							{
								case "Abonents":
									require $_SERVER['DOCUMENT_ROOT'] . '/ManagementCompany/tables_display/table_abonents.php';
									break;
								case "AccountsQuery":
									require $_SERVER['DOCUMENT_ROOT'] . '/ManagementCompany/tables_display/table_accounts_query.php';
									break;
								case "PersonalAccounts":
									require $_SERVER['DOCUMENT_ROOT'] . '/ManagementCompany/tables_display/table_personalaccount.php';
									break;
								case "ManagementCompany":
									require $_SERVER['DOCUMENT_ROOT'].'/ManagementCompany/tables_display/table_managementcompany.php';
									break;
								case "Admins":
									require $_SERVER['DOCUMENT_ROOT'].'/ManagementCompany/tables_display/table_admins.php';
									break;
								case "Contractors":
									require $_SERVER['DOCUMENT_ROOT'].'/ManagementCompany/tables_display/table_contractors.php';
									break;
								case "CommonDevices":
									require $_SERVER['DOCUMENT_ROOT'].'/ManagementCompany/tables_display/table_commondevices.php';
									break;
								case 'AccountDevices':
									require $_SERVER['DOCUMENT_ROOT'].'/ManagementCompany/tables_display/table_accountdevices.php';
									break;
								case 'AccountNormatives':
									require $_SERVER['DOCUMENT_ROOT'].'/ManagementCompany/tables_display/table_accountnormatives.php';
									break;
								case 'AccountServices':
									require $_SERVER['DOCUMENT_ROOT'].'/ManagementCompany/tables_display/table_accountservices.php';
									break;
								case 'ModelFunctions':
									require $_SERVER['DOCUMENT_ROOT'].'/ManagementCompany/tables_display/table_modelfunctions.php';
									break;
								case "Devices":
									require $_SERVER['DOCUMENT_ROOT'].'/ManagementCompany/tables_display/table_devices.php';
									break;
								case "DeviceEvents":
									require $_SERVER['DOCUMENT_ROOT'].'/ManagementCompany/tables_display/table_deviceevents.php';
									break;
								case "DeviceIndications":
									require $_SERVER['DOCUMENT_ROOT'].'/ManagementCompany/tables_display/table_deviceindications.php';
									break;
								case "DeviceModels":
									require $_SERVER['DOCUMENT_ROOT'].'/ManagementCompany/tables_display/table_devicemodels.php';
									break;
								case "Services":
									require $_SERVER['DOCUMENT_ROOT'].'/ManagementCompany/tables_display/table_services.php';
									break;
								case 'Regions':
									require $_SERVER['DOCUMENT_ROOT'].'/ManagementCompany/tables_display/table_regions.php';
									break;
								case "Tariffs":
									require $_SERVER['DOCUMENT_ROOT'].'/ManagementCompany/tables_display/table_tariffs.php';
									break;
								case "TariffTypes":
									require $_SERVER['DOCUMENT_ROOT'].'/ManagementCompany/tables_display/table_tarifftypes.php';
									break;
								case "Units":
									require $_SERVER['DOCUMENT_ROOT'].'/ManagementCompany/tables_display/table_units.php';
									break;
								case "Objects":
									require $_SERVER['DOCUMENT_ROOT'].'/ManagementCompany/tables_display/table_objects.php';
									break;
									
								case "Payment":
    								require $_SERVER['DOCUMENT_ROOT'] . '/ManagementCompany/tables_display/table_payment.php';
    								break;
    							case "Debt":
    								require $_SERVER['DOCUMENT_ROOT'] . '/ManagementCompany/tables_display/table_debt.php';
    								break;
    							case "Profit":
    								require $_SERVER['DOCUMENT_ROOT'] . '/ManagementCompany/tables_display/table_profit.php';
    								break;
							}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
    require $_SERVER['DOCUMENT_ROOT'].'/includes/footer.php';
?>
<?php
    require $_SERVER['DOCUMENT_ROOT'].'/includes/modal.php';
?>

</body>
</html>