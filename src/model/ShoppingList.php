<?php
class ShoppingList {
    private $items;

    public function __construct() {
        $this->items = [];
    }

    public function addItem(ListItem $item) {
        $this->items[] = $item;
    }

    public function getItems() {
        return $this->items;
    }
}

?>