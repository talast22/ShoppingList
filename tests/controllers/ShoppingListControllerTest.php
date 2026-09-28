<?php
require_once(__DIR__.'/../../src/controllers/ShoppingListController.php');
require_once(__DIR__.'/../../src/model/ListItem.php');

use PHPUnit\Framework\TestCase;

class ShoppingListControllerTest extends TestCase {

    private const string ITEM_ID = 'dummy_id';
    private const string ITEM_NAME = 'dummy item name';
    private const float ITEM_PRICE = 1.5;
    private const int ITEM_POSITION = 5;


    private $shoppingListController;
    private $mockDB;

    protected function setUp(): void {
        $this->mockDB = $this->createMock(DB::class);
        $this->shoppingListController = new ShoppingListViewController($this->mockDB);
        $_POST = [];
    }

    public function testHandlePOSTData_addItem() {
        
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $new_item_array = array('name' => self::ITEM_NAME, 'price' => self::ITEM_PRICE, 'position' => self::ITEM_POSITION);
        $_POST = array('new_item' => $new_item_array);

        $this->mockDB
            ->expects($this->once())
            ->method('addListItem');

        $this->shoppingListController->handlePOSTData();
        
    }

    public function testHandlePOSTData_removeItem() {
        
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = array('removed_item_id' => self::ITEM_ID);

        $this->mockDB
            ->expects($this->once())
            ->method('deleteListItem')
            ->with(self::ITEM_ID);

        $this->shoppingListController->handlePOSTData();
        
    }

    public function testHandlePOSTData_collectItem() {
        
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = array('collected_item_id' => self::ITEM_ID, 'collected_item_state' => false);

        $this->mockDB
            ->expects($this->once())
            ->method('updateItemCollected')
            ->with(self::ITEM_ID, true);

        $this->shoppingListController->handlePOSTData();
        
    }

    public function testHandlePOSTData_uncollectItem() {
        
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = array('collected_item_id' => self::ITEM_ID, 'collected_item_state' => true);

        $this->mockDB
            ->expects($this->once())
            ->method('updateItemCollected')
            ->with(self::ITEM_ID, false);

        $this->shoppingListController->handlePOSTData();
        
    }

    public function testHandlePOSTData_increaseItemPosition() {

        $starting_item_position = self::ITEM_POSITION;
        
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = array('increase_position' => "", 'moved_item_position' => $starting_item_position);

        $this->mockDB
            ->expects($this->once())
            ->method('swapItemPositions')
            ->with($starting_item_position, $starting_item_position +1);

        $this->shoppingListController->handlePOSTData();
        
    }

    public function testHandlePOSTData_reduceItemPosition() {

        $starting_item_position = self::ITEM_POSITION;
        
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = array('reduce_position' => "", 'moved_item_position' => $starting_item_position);

        $this->mockDB
            ->expects($this->once())
            ->method('swapItemPositions')
            ->with($starting_item_position, $starting_item_position - 1);

        $this->shoppingListController->handlePOSTData();
        
    }

    public function testGetTotalCost() {

        $test_prices = array(0, 23.33, 4.5, 9999);
        $expected_total = number_format(array_sum($test_prices), 2);
        
        $test_items = [];

        foreach( $test_prices as $price ) {
            $test_items[] = new ListItem(self::ITEM_ID, self::ITEM_NAME, $price, false, self::ITEM_POSITION);
        };

        $this->mockDB
            ->method('getListItems')
            ->willReturn($test_items);

        $result = $this->shoppingListController->getTotalCost();

        $this->assertEquals($expected_total, $result, 'Returned total item cost does not match the sum of individual prices');

    }

};
