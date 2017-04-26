<div class="container">
    <div class="row">
        <div class="a col-lg-12 col-lg-offset-0">

            <div class="row frame grid-divider">

                <div class="a col-lg-7 col-lg-offset-0">
                    <h3>Structure:
                        <button type="button" class="btn btn-danger pull-right" id="btn-collapse-all">Collapse All
                        </button>
                        <button type="button" class="btn btn-success pull-right" id="btn-expand-all">Expand All
                        </button>
                    </h3>
                    <div id="tree"></div>
                </div>

                <div id="navigation_tree" class="a col-lg-5 col-xs-offset-0">
                    <div class="col-padding">
                        <h3>Structure navigation:</h3>
                        <b>Selected element:</b> <span id="selected_h" class="selected"></span>
                        <input type="hidden" class="selected_id" name="id"/>
                        <br>
                        <button onclick="move('left');" type="button">Left</button>
                        <button onclick="move('up');" type="button">Up</button>
                        <button onclick="move('down');" type="button">Down</button>
                        <button onclick="move('right');" type="button">Right</button>
                        <button onclick="remove();" type="button">Remove</button>

                        <br>
                        <b>You can use keys on keyboard</b> <br>
                        <span style="font-size:10px;">(<span class="red">DELETE</span> remove folder, <span class="red">Directional arrows</span> to move folder</span>

                        <br>
                        <b>Rename folder:</b> <br>
                        <input class="selected" placeholder="Enter new name folder" name="new_name"/>
                        <button onclick="rename();" type="button">Rename</button>

                        <div><b>Add new folder:</b></div>

                        <table>
                            <tr>
                                <td><input placeholder="Name new folder" type="text" name="add_name"/></td>
                                <td><input type="checkbox" name="root"/></td>
                                <td>Add to main branch</td>
                            </tr>

                            <tr>
                                <td colspan="3">
                                    <button onclick="add();" type="button">Add folder</button>
                                    <span style="font-size:10px;">(First click the place where you want to add)</span>
                                </td>

                            </tr>
                        </table>
                        <hr>
                        <h3>Structure files:</h3>

                        <b>Add files to <span id="selected_h" class="selected"></span></b> <br>
                        <input name="new_file"/>
                        <button onclick="file_add();" type="button">Add element</button>

                        <h3>Operation history:
                            <button onclick="clear_history();" type="button">Clear history</button>
                        </h3>
                        <textarea id="history" readonly></textarea>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>