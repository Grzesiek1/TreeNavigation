<main>
    <ul>
        {foreach from=$id key=k item=foo }
            <li>{$id[$k]}.{$name[$k]}</li>
        {/foreach}
    </ul>
</main>
