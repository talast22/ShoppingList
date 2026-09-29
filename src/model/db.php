<?php
    
define ("LIST_ITEM_TABLE", "listItem");   

include "DBConfig.php";
include "DBSetupSQL.php";

class DB {

    private $conn;
    
    public function __construct() {
        $setup_conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD);

        $sql = "CREATE DATABASE IF NOT EXISTS ". DB_NAME;
        if ($setup_conn->query($sql) !== TRUE) {
            echo "Error creating database: " . $setup_conn->error;
        }

        $setup_conn->close();

        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

        $this->conn->query(LIST_TABLE_SQL);
    }

    public function __destruct(){
        if($this->conn == null) {
            return;
        }
        $this->conn->close();
    }

    public function addListItem($item) {
        $item_id = $item->getId();
        $item_name = $item->getName();
        $item_price = $item->getPrice();
        $item_collected = (int) $item->isCollected();
        $item_listOrder = $item->getListOrder();

        $insert_item_statement = $this->conn->prepare("INSERT INTO " . LIST_ITEM_TABLE . " (id, name, price, collected, listOrder) VALUES (?, ?, ?, ?, ?)");
        $insert_item_statement->bind_param("ssdii", $item_id, $item_name, $item_price, $item_collected, $item_listOrder);
        $insert_item_statement->execute();
        $insert_item_statement->close();
    }

    public function deleteListItem($item_id) {
        // First, lower the 'list order' values of all subsequent items
        $query = "UPDATE ". LIST_ITEM_TABLE . " AS target, (SELECT listorder from ". LIST_ITEM_TABLE . " WHERE id = ?) AS deleted SET target.listorder = target.listorder - 1 WHERE target.listorder > deleted.listorder";
        $updateListOrderStatement = $this->conn->prepare($query);
        $updateListOrderStatement->bind_param("s", $item_id);
        $updateListOrderStatement->execute();
        $updateListOrderStatement->close();

        // Delete the item
        $deleteStatement = $this->conn->prepare("DELETE FROM " . LIST_ITEM_TABLE . " WHERE id = ?");
        $deleteStatement->bind_param("s", $item_id);
        $deleteStatement->execute();
        $deleteStatement->close();
    }

    public function getListItems() {
        $result = $this->conn->query("SELECT * FROM listItem ORDER BY listOrder");
        $items = [];
        while ($row = $result->fetch_assoc()) {
            $items[] = new ListItem($row['id'], $row['name'], $row['price'], (bool) $row['collected'], $row['listOrder']);
        }
        return $items;
    }

    public function updateItemCollected($item_id, $collected) {
        $collected_int = (int) $collected;
        $stmt = $this->conn->prepare("UPDATE " . LIST_ITEM_TABLE . " SET collected = ? WHERE id = ?");
        $stmt->bind_param("is", $collected_int, $item_id);
        $stmt->execute();
        $stmt->close();
    }

    /**
     * Swap the 'list order' values of the items with the given list positions.
     * I.e. if item 'A' is in position n and item 'B' is in position m, set A's list order value to be m and B's to be n
     * 
     * @param mixed $first_position The current postion in the list of the  first item
     * @return void
     */
    public function swapItemPositions($first_position, $second_position) {
        $position_sums = $first_position + $second_position;
        $updateStatment = $this->conn->prepare("UPDATE " . LIST_ITEM_TABLE . " SET listorder = (? - listorder) WHERE listorder IN (?, ?)");
        $updateStatment->bind_param("iii", $position_sums, $first_position, $second_position);
        $updateStatment->execute();
        $updateStatment->close();
    }
}

?>