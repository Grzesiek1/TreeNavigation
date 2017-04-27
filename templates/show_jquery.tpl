<div class="container">
    <div class="row">
        <div class="col-lg-12 col-lg-offset-0">

            <div class="row frame grid-divider">
                <div class="col-lg-8 col-lg-offset-0">
                    <h3>Structure:
                        <button type="button" class="btn btn-danger pull-right" id="btn-collapse-all">Collapse All
                            Folders
                        </button>
                        <button type="button" class="btn btn-success pull-right" id="btn-expand-all">Expand All Folders
                        </button>
                    </h3>
                    <div id="tree"></div>
                </div>

                <div id="navigation_tree" class="col-lg-4 col-xs-offset-0">
                    <div class="col-padding">
                        <div id="nav_folder">
                            <h3>Folder navigation:</h3>
                            <b>Selected folder:</b> <span id="selected_h" class="selected"></span>
                            <input type="hidden" class="selected_id" name="id"/>
                            <br>
                            <button onclick="move('left');" type="button">Left</button>
                            <button onclick="move('up');" type="button">Up</button>
                            <button onclick="move('down');" type="button">Down</button>
                            <button onclick="move('right');" type="button">Right</button>
                            <button onclick="remove();" type="button">Remove</button>

                            <br>
                            <b>You can use keys on keyboard</b> <br>
                            <span style="font-size:10px;">(<span class="red">DELETE</span> remove folder, <span
                                        class="red">Directional arrows</span> to move folder</span>)

                            <br>
                            <b>Rename folder:</b> <br>
                            <input class="selected" placeholder="Enter new name folder" name="new_name"/>
                            <button onclick="rename();" type="button">Rename folder</button>

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
                        </div>

                        <div>
                            <h3>Files navigation:</h3>

                            <b>Rename file: <span class="selected_file"></span></b> <br>
                            <input placeholder="Enter new name file" name="file_new_name"/>
                            <button onclick="file_rename();" type="button">Rename file</button>

                            <br>

                            <b>Add files to folder name: <span id="selected_h" class="selected"></span></b> <br>
                            <input placeholder="New name file" name="new_file"/>
                            <button onclick="file_add();" type="button">Add file</button>

                            <br>

                            <b>Selected file:</b> <span class="selected_file"></span>
                            <input type="hidden" class="selected_file" name="file_id"/><br>
                            <button onclick="file_move('up');" type="button">File move up</button>
                            <button onclick="file_move('down');" type="button">File move down</button>
                            <br>
                        <span style="font-size:10px;">(<span class="red">Home</span> move file up, <span
                                    class="red">End</span> move file to down</span>)

                        </div>

                        <div id="history_block">
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
</div>