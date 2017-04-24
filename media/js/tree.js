/**
 * Created by Grzegorz Chwiluk on 2017-04-24.
 */
$(function () {

    $.ajax({
            type: 'GET',
            url: 'json.php',
            dataType: "json",
        })
        .done(function (response) {
            var $handle = $('#tree').treeview({
                levels: 99,
                data: response,
                onNodeSelected: function (event, node) {
                    $('.selected').val(node.text);
                    $('.selected').text(node.text + ' (id:' + node.id + ')');
                    //
                    $('.selected_id').val(node.id);
                }
            });

            $('#btn-expand-all').on('click', function () {
                $handle.treeview('expandAll', {levels: 99, silent: 0});
            });

            $('#btn-collapse-all').on('click', function () {
                $handle.treeview('collapseAll', {silent: 0});
            });
        })
        .fail(function (response) {
            console.log(response);
        });


});

function refresh() {
    $.ajax({
            type: 'GET',
            url: 'json.php',
            dataType: "json",
        })
        .done(function (response) {
            $('#tree').treeview({
                levels: 99,
                data: response,
                onNodeSelected: function (event, node) {
                    $('.selected').val(node.text);
                    $('.selected').text(node.text + ' (id:' + node.id + ')');
                    //
                    $('.selected_id').val(node.id);
                }
            });
        })
        .fail(function (response) {
            console.log(response);
        });
}

function message(content) {
    var date = new Date();
    var history = document.getElementById("history");

    var value = '[' + date.getDate() + '-' + (date.getMonth() + 1) + '-' + date.getFullYear();
    value += ' ' + date.getHours() + ':' + date.getMinutes() + ':' + date.getSeconds() + ']';

    refresh();
    return history.innerHTML = value + content + '\n' + history.innerHTML;
}

function rename() {
    var id = $("input[name='id']").val();
    var new_name = $("input[name='new_name']").val();


    $.post("action.php?id=rename",
        {
            id: id,
            new_name: new_name
        },
        function (response, status) {
            message(response);
        });
}

function remove() {
    var id = $("input[name='id']").val();
    var history = document.getElementById("history");

    $.post("action.php?id=remove",
        {
            id: id
        },
        function (response, status) {
            message(response);
        });
}