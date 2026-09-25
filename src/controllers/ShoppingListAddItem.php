<?php
require __DIR__.'/../model/ShoppingList.php';
require __DIR__.'/../model/ListItem.php';
require_once __DIR__.'/../model/db.php';

$_DB = new DB();

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_item_name'])) {
    addItem($_POST['new_item_name']);
    
}

function addItem($name) {
    $sanitisedName = htmlspecialchars($name);
    $item = new ListItem(uniqid(), $sanitisedName);

    global $_DB;
    $_DB->addListItem($item);
}

?>