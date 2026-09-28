<?php
$list_size = $controller->getListSize();
echo(<<<HTML
<div id="addItemContainer">
    <form method = "post" action = ""> 
        <label for="new_item[name]">New Item:</label>
        <input type="text" name="new_item[name]" placeholder="Item Name" required autofocus />
        <label for="new_item[price]">Price:</label>
        <input type="number" min="0" max="9999.99" step="0.01" name="new_item[price]" />
        <input type="hidden" name="new_item[position]" value=$list_size/>
        <input type="submit" value="Add Item"/>
    </form>
</div>
HTML);

