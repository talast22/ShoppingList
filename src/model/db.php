<?php

    define("DB_HOST", "localhost");
    define("DB_NAME","ShoppingListDB");
    define("DB_CHARSET", "utf8mb4");
    define("DB_USER", "root");
    define("DB_PASSWORD", "welcome");

    define ("LIST_ITEM_TABLE", "listItem");

    //include "DBSetupSQL.php";

class DB {

    private $conn;
    
    function __construct() {
        $setup_conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD);

        $sql = "CREATE DATABASE IF NOT EXISTS ". DB_NAME;
        if ($setup_conn->query($sql) !== TRUE) {
        echo "Error creating database: " . $setup_conn->error;
        }

        $setup_conn->close();

        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

        $this->conn->query("CREATE TABLE IF NOT EXISTS `". LIST_ITEM_TABLE ."` (
        `id` varchar(255) NOT NULL,
        `name` varchar(255) NOT NULL
        )");
    }

    function __destruct(){
        $this->conn->close();
    }

    function addListItem($item) {
        $item_id = $item->getId();
        $item_name = $item->getName();

        $stmt = $this->conn->prepare("INSERT INTO " . LIST_ITEM_TABLE . " (id, name) VALUES (?, ?)");
        $stmt->bind_param("ss", $item_id, $item_name);
        $stmt->execute();
        $stmt->close();
    }

    function removeListItem($item_id) {
        $stmt = $this->conn->prepare("DELETE FROM " . LIST_ITEM_TABLE . " WHERE id = ?");
        $stmt->bind_param("s", $item_id);
        $stmt->execute();
        $stmt->close();
    }

    function getListItems() {
        $result = $this->conn->query("SELECT * FROM listItem");
        $items = [];
        while ($row = $result->fetch_assoc()) {
            $items[] = new ListItem($row['id'], $row['name']);
        }
        return $items;
    }
}



?>