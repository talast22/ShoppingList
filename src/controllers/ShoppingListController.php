<?php

require __DIR__.'/../model/ListItem.php';
require_once __DIR__.'/../model/db.php';

class ShoppingListViewController {

    private $db;
    private $shoppingList;

    public function __construct($db) {
        if ($db == null) {
            $db = new DB();
        };

        $this->db = $db;
    }

    public function handlePOSTData() {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if(isset($_POST['new_item'])) {
                $this->addItem($_POST['new_item']['name'], $_POST['new_item']['price'], $_POST['new_item']['position']);
            }

            if (isset($_POST['removed_item_id'])) {
                $this->removeItem($_POST['removed_item_id']);
            }

            if (isset($_POST['collected_item_id'])) {
                $this->toggleItemCollected($_POST['collected_item_id'], $_POST['collected_item_state']);
            }

            if(isset($_POST['reduce_position'])) {
                $this->reduceItemPosition($_POST['moved_item_position']);
            }

            if(isset($_POST['increase_position'])) {
                $this->increaseItemPosition($_POST['moved_item_position']);
            }
        }
    }

    public function getShoppingListItems() {

        if ($this->shoppingList == null) {
            $this->shoppingList = $this->db->getListItems();
        }
        return $this->shoppingList;
    }

    public function getTotalCost() {
        $listItems = $this->getShoppingListItems();
        $totalCost = 0;
        foreach ($listItems as $item) {
            $totalCost += $item->getPrice();
        }
        return number_format($totalCost, 2);
    }

    public function getListSize() {
        return count($this->getShoppingListItems());
    }

    private function addItem($name, $price, $position) {
        $sanitisedName = htmlspecialchars($name);
        $item = new ListItem(uniqid(), $sanitisedName, $price, false, $position);

        $this->db->addListItem($item);
    }

    private function removeItem($item_id) {
        $this->db->deleteListItem($item_id);
    }

    private function toggleItemCollected($item_id, $item_collected) {
        $this->db->updateItemCollected($item_id, !$item_collected);
    }

    private function reduceItemPosition($currentPostion) {
        $this->db->swapItemPositions($currentPostion, $currentPostion - 1);
    }

    private function increaseItemPosition($currentPostion) {
        $this->db->swapItemPositions($currentPostion, $currentPostion + 1);
    }

}