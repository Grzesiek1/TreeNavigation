<div class="container">
    <div class="row">
        <div class="a col-lg-12 col-lg-offset-0">

            <div class="row frame grid-divider">

                <div class="a col-xs-6 col-lg-offset-0">
                    <h3>Structure:</h3>
                    <div id="tree"></div>
                </div>

                <div id="navigation_tree" class="a col-xs-6 col-xs-offset-0">
                    <div class="col-padding">
                        <h3>Structure navigation:</h3>
                        <b>Selected element:</b> <span id="selected_h" class="selected"></span>
                        <input type="hidden" class="selected_id" name="id"/>
                        <br>
                        <button onclick="move_left();" type="button">Left</button>
                        <button onclick="move_up();" type="button">Up</button>
                        <button onclick="move_down();" type="button">Down</button>
                        <button onclick="move_right();" type="button">Right</button>
                        <button onclick="remove();" type="button">Remove</button>

                        <br><br>
                        <b>Move to (Enter parent "id"):</b> <br>
                        <input type="number" name="new_parent_id"/>
                        <button onclick="move_to();" type="button">Move</button>

                        <br><br>
                        <b>Rename (Enter a new "name"):</b> <br>
                        <input class="selected" name="new_name"/>
                        <button onclick="rename();" type="button">Rename</button>

                        <div><b>Add new element (Enter a unique "name")</b><br>
                        <span style="font-size:10px;">(First click the place where you want to add)</span></div>

                        <table>
                            <tr>
                                <td>Name:</td>
                                <td><input placeholder="New element name" type="text" name="add_name"/></td>
                                <td><input type="checkbox" name="root"/></td>
                                <td>Add to main branch</td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <button onclick="add();" type="button">Add element</button>
                                </td>
                            </tr>
                        </table>

                        <div>
                            <button type="button" class="btn btn-danger pull-right" id="btn-collapse-all">Collapse All
                            </button>
                            <button type="button" class="btn btn-success pull-right" id="btn-expand-all">Expand All
                            </button>
                        </div>

                        <br>
                        <hr>

                        <h3>Operation history: <button onclick="clear_history();" type="button">Clear history</button></h3>
                        <textarea id="history" readonly></textarea>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>