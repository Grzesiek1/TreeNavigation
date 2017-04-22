<script type="text/javascript">
    $(function () {
        $('#trees').treeview({
            data: {$json_data}
        });
    });
</script>

<main>

    <h2>Structure:</h2>
    <div id="trees"></div>

    <form method="post" action="action.php?id=add">
        Element "name": <input type="text" name="name"/>
        <input type="submit" value="Add element"/>
    </form>

</main>