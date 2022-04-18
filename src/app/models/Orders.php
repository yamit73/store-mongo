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

    public function updateStatus($status, $id)
    {
        return $this->collection->updateOne(['_id'=>new MongoDB\BSON\ObjectID($id)],['$set'=>['status'=>$status]]);
    }

    /**
     * To get details of all the orders
     *
     * @return void
     */
    public function getOrders()
    {
        return $this->collection->find();
    }

    /**
     * To get details of orders with filters
     *
     * @return void
     */
    public function getFilteredOrders($filter)
    {
        return $this->collection->find(['$and'=>[['status'=>$filter['status']],['date' => ['$gt' => $filter['date']['from'], '$lt' => $filter['date']['to']]]]]); 
    }
}
