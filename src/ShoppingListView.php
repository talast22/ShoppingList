<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="style.css">
    </head>
<body> 
<h1 name ="addItemContainer">Shopping List</h1> 


<?php
require_once __DIR__.'/controllers/ShoppingListController.php';
include_once __DIR__.'/viewElements/listItemRowGen.php'; 


// Setup controller
$controller = new ShoppingListViewController(null);
$controller-> handlePOSTData();

//Display the list of items
$shoppingListItems = $controller->getShoppingListItems();
if(!empty($shoppingListItems)) {

    echo('<table>');


    foreach ($shoppingListItems as $item) {
        echo(createListItemRow($item));
    }

    echo('<tr><td id="totalCost">Total Cost: </td> <td>' . $controller->getTotalCost() . '</td></tr>');

    echo('</table><br>');
};

//'Add new item' widget 

include_once __DIR__.'/viewElements/addItemWidget.php';

?>


</body>
</html>