/**
 * Created by Grzegorz Chwiluk on 2017-04-24.
 */

/*
 * init tree
 */
function refresh() {
    $.ajax({
        type: 'GET',
        url: 'ajax.php?json=true',
        dataType: "json"
    }).done(function (response) {
        var $handle = $('#tree').treeview({
            levels: 99,
            data: response,
            showTags: true,
            onNodeSelected: function (event, node) {
                $('.selected').val(node.text);
                $('.selected').text(node.text + ' (id:' + node.id + ')');
                $('.selected_id').val(node.id);
                save_element_selected(node.id);
            }
        });

        $('#btn-expand-all').on('click', function () {
            $handle.treeview('expandAll', {levels: 99, silent: 0});
        });

        $('#btn-collapse-all').on('click', function () {
            $handle.treeview('collapseAll', {silent: 0});
        });
        $.ajax({
            async: false,
            type: 'GET',
            url: 'ajax.php?get_position_folder=true',
            dataType: "json",
            'success': function (data) {
                $handle.treeview('selectNode', data);
            }
        });
    }).fail(function (response) {
        console.log(response);
    });
}
refresh();

/*
 * Return message to 'History operation' in frontend
 */
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

/**
 * Folder folder
 */

/*
 * Used to rename folder
 */
function rename() {
    var id = $("input[name='id']").val();
    var new_name = $("input[name='new_name']").val();
    $("input[name='new_name']").select();

    $.post("action.php?id=rename", {
        id: id,
        new_name: new_name
    }, function (response) {
        message(response);
    });
}

/*
 * Used to remove folder
 */
function remove() {
    var id = $("input[name='id']").val();

    $.post("action.php?id=remove", {
        id: id
    }, function (response) {
        message(response);
    });

    $('.selected').val('');
    $('.selected').text('');
    $('.selected_id').val('');
}

/*
 * Used to add folder
 */
function add() {
    var id = $("input[name='id']").val();
    var add_name = $("input[name='add_name']").val();
    $("input[name='add_name']").select();

    if ($("input[name='root']").is(':checked')) {
        id = 0;
    }

    $.post("action.php?id=add", {
        parent_id: id,
        add_name: add_name

    }, function (response) {
        message(response);
    });
}

/*
 * Used to move folder
 */
function move($move) {
    var id = $("input[name='id']").val();
    ($move == 'left') ? $src = "action.php?id=move_left" : false;
    ($move == 'right') ? $src = "action.php?id=move_right" : false;
    ($move == 'down') ? $src = "action.php?id=move_down" : false;
    ($move == 'up') ? $src = "action.php?id=move_up" : false;

    $.post($src, {
        id: id
    }, function (response) {
        message(response);
    });
}

/*
 * Lock scroll page by keys HOME and END on keyboard
 */
$(document).keydown(function (event) {
    if (event.which == "36" || event.which == "35" || event.which == "38" || event.which == "40") {
        event.preventDefault();
        return true;
    }
});

/*
 * Support of keys to operate on the tree
 */
document.addEventListener("keydown", function (event) {
    //key left
    (event.which == '37') ? move('left') : false;
    //key up
    (event.which == '38') ? move('up') : false;
    //key down
    (event.which == '40') ? move('down') : false;
    //key right
    (event.which == '39') ? move('right') : false;
    //key delete
    (event.which == '46') ? remove() : false;
    //key HOME
    (event.which == '36') ? file_move('up') : false;
    //key END
    (event.which == '35') ? file_move('down') : false;
});

/*
 * Clear 'history operation'
 */
function clear_history() {
    document.getElementById("history").innerHTML = '';
}

/**
 * Files operation
 */

/*
 * Used to add file
 */
function file_add() {
    var id = $("input[name='id']").val();
    var new_file = $("input[name='new_file']").val();


    $.post("action.php?id=file_add", {
        id: id,
        new_file: new_file

    }, function (response) {
        message(response);
    });
}

/*
 * Used to remove file
 */
function file_remove($id) {
    $.post("action.php?id=file_remove", {
        id: $id
    }, function (response) {
        message(response);
    });
}

/*
 * Used to selected file on list
 */
function file_selected($id) {
    $("input[class='selected_file']").val($id);
    $('.selected_file').text($id);

    $.post("ajax.php", {
        id: $id,
        get_file_name: 'true'
    }, function (response) {
        $('.selected_file').text(response + '(id:' + $id + ')');
        $("input[name='file_new_name']").val(response);
    });
}

/*
 * Used to rename file
 */
function file_rename() {
    var id = $("input[class='selected_file']").val();
    var file_new_name = $("input[name='file_new_name']").val();

    $.post("action.php?id=file_rename", {
        id: id,
        file_new_name: file_new_name
    }, function (response) {
        message(response);
    });
}

/*
 * Used to move file
 */
function file_move($move) {
    var id = $("input[class='selected_file']").val();
    var folder = $("input[name='id']").val();

    $.post("action.php?id=file_move_" + $move, {
        id: id,
        folder: folder
    }, function (response) {
        message(response);
    });
}

/*
 * Used to save current selected element to PHP session
 * Needed when refreshing list - must remember the last selection
 */
function save_element_selected($id) {
    $.post("ajax.php?save_element_selected=true", {id: $id});
}
