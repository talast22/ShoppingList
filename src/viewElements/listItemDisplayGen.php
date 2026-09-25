<?php
function createListItemDisplay($item) {
    $item_id = $item->getId();
    $item_name = $item->getName();
    
    return <<<END
    <tr> 
    <td>$item_name</td>
    <td>
        <form method = "post" action = "">
            <input type='hidden' name='delete_item_id' value='$item_id'>
            <input type='submit' value='Remove'>
        </form>
    </td>
    </tr>
    END;
}
