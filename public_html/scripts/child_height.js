$(document).ready(function(){
    var table = document.getElementById('data_table');//получаем таблицу
    var source;
    var num_row = table.rows.length;//количество строк
    
    for (let i = 0; i < num_row; i++) {
        source = document.getElementById('tr_parent_' + i);
        $('#tr_child_' + i).height($(source).height());
        
    }
})