<?php
class ListItem {
    private $id;
    private $name;

    private $price;

    private $collected;

    private $listOrder;

    public function __construct($id, $name, $price, $collected, $listOrder) {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->collected = $collected;
        $this->listOrder = $listOrder;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function isCollected() {
        return $this->collected;
    }

    public function setCollected($collected) {
        $this->collected = (bool) $collected;
    }

    public function getPrice() {
        return $this->price;
    }

    public function getListOrder() {
        return $this->listOrder;
    }
}