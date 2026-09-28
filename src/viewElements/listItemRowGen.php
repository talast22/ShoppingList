<?php
function createListItemRow($item) {
    $item_id = $item->getId();
    $item_name = $item->getName();
    $price = number_format($item->getPrice(), 2);
    $collected = $item->isCollected();
    $list_position = $item->getListOrder();

    $collect_button_text = $collected ? "Unmark" : "Got it!";
    $conditional_strikethrough = $collected ?"line-through":"";
    $background_colour = $collected ?"#7ED950":"#DED06A";

    global $controller;
    $move_up_button_disabled = $list_position == 0 ? "disabled":"";
    $move_down_button_disabled =  $list_position == ($controller->getListSize() -1) ? "disabled":"";
    
    return <<<END
    <tr style="background-color: $background_colour;"> 
    <td>
        <form method="post" action="">
            <input type='hidden' name='moved_item_position' value='$list_position'>
            <button name="reduce_position" $move_up_button_disabled>↑</button>
            <button name="increase_position" $move_down_button_disabled type="submit">↓</button>
        </form>
    </td>
    <td style="text-decoration-line: $conditional_strikethrough">$item_name</td>
    <td>$price</td>
    <td>
        <form method="post" action="">
            <input type='hidden' name='collected_item_id' value='$item_id'>
            <input type='hidden' name='collected_item_state' value='$collected'>
            <input type='submit' value='$collect_button_text'>
        </form>
    </td>
    <td>
        <form method="post" action="">
            <input type='hidden' name='removed_item_id' value='$item_id'>
            <input type='submit' value='Remove'>
        </form>
    </td>
    </tr>
    END;
}
