/**
 * Created by Grzegorz Chwiluk on 2017-04-24.
 */
$(function () {
    $.ajax({
            type: 'GET',
            url: 'ajax.php?json=true',
            dataType: "json",
        })
        .done(function (response) {
            var $handle = $('#tree').treeview({
                levels: 99,
                data: response,
                onNodeSelected: function (event, node) {
                    $('.selected').val(node.text);
                    $('.selected').text(node.text + ' (id:' + node.id + ')');
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
            url: 'ajax.php?json=true',
            dataType: "json",
        })
        .done(function (response) {

            var $handle = $('#tree').treeview({
                levels: 99,
                data: response,
                onNodeSelected: function (event, node) {
                    $('.selected').val(node.text);
                    $('.selected').text(node.text + ' (id:' + node.id + ')');
                    $('.selected_id').val(node.id);
                }
            });
            $.ajax({
                    type: 'GET',
                    url: 'ajax.php?get_position=true',
                    dataType: "json"
                })
                .done(function (response) {
                    $handle.treeview('selectNode', response);
                });
        })
        .fail(function (response) {
            console.log(response);
        });


}

function message(content) {
    if (content) {
        var date = new Date();
        var history = document.getElementById("history");

        var value = '[' + date.getDate() + '-' + (date.getMonth() + 1) + '-' + date.getFullYear();
        value += ' ' + date.getHours() + ':' + date.getMinutes() + ':' + date.getSeconds() + ']';

        refresh();
        return history.innerHTML = value + content + '\n' + history.innerHTML;
    }
}

function rename() {
    var id = $("input[name='id']").val();
    var new_name = $("input[name='new_name']").val();


    $.post("action.php?id=rename",
        {
            id: id,
            new_name: new_name
        },
        function (response) {
            message(response);
        });
}

function remove() {
    var id = $("input[name='id']").val();

    $.post("action.php?id=remove",
        {
            id: id
        },
        function (response) {
            message(response);
        });

    $('.selected').val('');
    $('.selected').text('');
    $('.selected_id').val('');
}

function add() {
    var id = $("input[name='id']").val();
    var add_name = $("input[name='add_name']").val();

    if ($("input[name='root']").is(':checked')) {
        id = 0;
    }

    $.post("action.php?id=add",
        {
            parent_id: id,
            add_name: add_name,

        },
        function (response) {
            message(response);
        });
}

function move_to() {
    var id = $("input[name='id']").val();
    var new_parent_id = $("input[name='new_parent_id']").val();

    $.post("action.php?id=move_to",
        {
            id: id,
            new_parent_id: new_parent_id
        },
        function (response) {
            message(response);
        });
}

function move_left() {
    var id = $("input[name='id']").val();

    $.post("action.php?id=move_left",
        {
            id: id
        },
        function (response) {
            message(response);
        });
}

function move_up() {
    var id = $("input[name='id']").val();

    $.post("action.php?id=move_up",
        {
            id: id
        },
        function (response) {
            message(response);
        });
}

function move_down() {
    var id = $("input[name='id']").val();

    $.post("action.php?id=move_down",
        {
            id: id
        },
        function (response) {
            message(response);
        });
}

function move_right() {
    var id = $("input[name='id']").val();

    $.post("action.php?id=move_right",
        {
            id: id
        },
        function (response) {
            message(response);
        });
}

document.addEventListener("keydown", function (event) {
    //key left
    if (event.which == '37') {
        move_left();
    }
    //key up
    if (event.which == '38') {
        move_up();
    }
    //key down
    if (event.which == '40') {
        move_down();
    }
    //key right
    if (event.which == '39') {
        move_right();
    }
    //key delete
    if (event.which == '46') {
        remove();
    }
    //key F2
    if (event.which == '113') {
        rename();
    }
});

function clear_history() {
    document.getElementById("history").innerHTML = '';
}