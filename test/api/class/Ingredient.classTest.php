<?php
/**
 * Generated by PHPUnit_SkeletonGenerator on 2015-04-02 at 11:53:17.
 */
class IngredientTest extends PHPUnit_Framework_TestCase {

    /**
     * @var Ingredient
     */
    protected $ingredient;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->ingredient = new Ingredient(1, 'Testingredient');
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        $del_query = 'DELETE FROM ingredient
                WHERE id <> 1 AND id <> 2';
        DB::con()->query($del_query);
        
        $alter_query = 'ALTER TABLE ingredient AUTO_INCREMENT=2';
        DB::con()->query($alter_query);
    }

    /**
     * @covers Ingredient::get_by_id
     */
    public function testGet_by_id() {
        $testing = Ingredient::get_by_id(1);

        $this->assertEquals($this->ingredient, $testing);
    }

    /**
     * @covers Ingredient::get_by_name
     * Expecting only one ingredient in returned array
     */
    public function testGet_by_name() {
        $testing = Ingredient::get_by_name("Testingredient");

        $this->assertEquals($this->ingredient, $testing[0]);
        $this->assertEquals(1, count($testing));
    }

    /**
     * @covers Ingredient::create
     */
    public function testCreate() {
        $testing = new Ingredient(3, "TestCreateIng");
        $new_ingredient = Ingredient::create("TestCreateIng");

        $this->assertEquals($testing, $new_ingredient);
    }

    /**
     * @covers Ingredient::set_name
     */
    public function testSet_name() {
        $this->ingredient->set_name('NewTest');
        $this->assertEquals('NewTest', $this->ingredient->get_name());

        //revert changes
        $this->ingredient->set_name('Testingredient');
        $this->assertEquals('Testingredient', $this->ingredient->get_name());
    }

}
