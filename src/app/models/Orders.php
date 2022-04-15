<?php

use Phalcon\Mvc\Model;
/**
 * Orders model class
 */
class Orders extends Model
{
    //Collection
    public $collection;
    /**
     * Constructor to initialize the collection
     *
     * @return void
     */
    public function initialize()
    {
        $this->collection=$this->di->get('mongo')->orders;
    }

    /**
     * Add Orders to collection
     *
     * @param [array] $product
     * takes order information as array
     * @return void
     */
    public function add($order)
    {
        $this->collection->insertOne($order);
    }

    /**
     * To get details of all the products
     *
     * @return void
     */
    public function getOrders()
    {
        return $this->collection->find();
    }
}
