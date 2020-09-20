<?php
require $_SERVER['DOCUMENT_ROOT'].'/includes/names_rus.php';
?>

<div class='dropdown'>
    <!--<button class='btn btn-secondary dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'><?php echo $names_rus[$active_table]?></button>-->
    <button class='btn btn-secondary dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>Таблицы</button>
    <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
        <a class='dropdown-item' href='?table=Abonents'>Абоненты</a>
        <a class='dropdown-item' href='?table=GlobalAdmins'>Администраторы программного продукта</a>
        <a class='dropdown-item' href='?table=Admins'>Администраторы управляющих компаний</a>
        <a class='dropdown-item' href='?table=Bots'>Боты социальных сетей</a>
        <a class='dropdown-item' href='?table=TariffTypes'>Виды тарифов</a>
        <a class='dropdown-item' href='?table=Units'>Единицы измерения</a>
         <a class='dropdown-item' href='?table=Debt'>Задолженность</a>
        <a class='dropdown-item' href='?table=AccountsQuery'>Запросы пользователей на добавление лицевых счетов</a>
        <a class='dropdown-item' href='?table=UsersQuery'>Запросы пользователей на регистрацию</a>
        <a class='dropdown-item' href='?table=AccountDevices'>Индивидуальные приборы учета</a>
        <a class='dropdown-item' href='?table=Services'>Коммунальные услуги</a>
        <a class='dropdown-item' href='?table=PersonalAccounts'>Лицевые счета</a>
        <a class='dropdown-item' href='?table=DeviceModels'>Модели приборов учета</a>
        <a class='dropdown-item' href='?table=ModelFunctions'>Назначение моделей приборов учета</a>
        <a class='dropdown-item' href='?table=Profit'>Начисление</a>
        <a class='dropdown-item' href='?table=AccountNormatives'>Нормативы по лицевым счетам</a>
        <a class='dropdown-item' href='?table=CommonDevices'>Общедомовые приборы учета</a>
        <a class='dropdown-item' href='?table=Objects'>Объекты недвижимости</a>
        <a class='dropdown-item' href='?table=Payment'>Платежи</a>
        <a class='dropdown-item' href='?table=DeviceIndications'>Показания приборов учета</a>
        <a class='dropdown-item' href='?table=Users'>Пользователи</a>
        <a class='dropdown-item' href='?table=AccountUsers'>Пользователи абонентов</a>
        <a class='dropdown-item' href='?table=Contractors'>Поставщики коммунальных услуг</a>
        <a class='dropdown-item' href='?table=Devices'>Приборы учета</a>
        <a class='dropdown-item' href='?table=Regions'>Регионы</a>
        <a class='dropdown-item' href='?table=DeviceEvents'>События приборов учета</a>
        <a class='dropdown-item' href='?table=AccountServices'>Состав коммунальных услуг</a>
        <a class='dropdown-item' href='?table=Tariffs'>Тарифы</a>
        <a class='dropdown-item' href='?table=ManagementCompany'>Управляющие компании</a>
    </div>
</div>