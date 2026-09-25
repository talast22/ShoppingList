<?php
require_once __DIR__.'/controllers/ShoppingListController.php';
include_once __DIR__.'/viewElements/listItemDisplayGen.php'; ?> 


<html>
<h1 style="text-align: center;">Shopping List</h1> 
<body>

<!-- Add new item widget -->
 <?php include_once __DIR__.'/viewElements/addItemWidget.php'; ?> 
<br/>


<!-- Display the list of item boxes-->
<?php 
$shoppingListItems = getShoppingListItems();
if(!empty($shoppingListItems)) {

    echo(<<<HTML
        <table style=" margin-left: auto;  margin-right: auto; width: 80%; border: 1px solid black; border-collapse: collapse;">
            <tr>
            <th>Item</th>
            </tr>
        HTML);


    foreach ($shoppingListItems as $item) {
        echo(createListItemDisplay($item));
    }

    echo('</table>');
};


?>


</body>
</html>