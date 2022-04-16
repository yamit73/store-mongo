<?php

use Phalcon\Mvc\Model;
/**
 * Product model class
 */
class Products extends Model
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
        $this->collection=$this->di->get('mongo')->products;
    }

    /**
     * Add product to collection
     *
     * @param [array] $product
     * takes product information as array
     * @return void
     */
    public function add($product)
    {
        $this->collection->insertOne($product);
    }

    /**
     * To get details of all the products
     *
     * @return void
     */
    public function getProducts()
    {
        return $this->collection->find();
    }

    /**
     * Search a product with name
     *
     * @param [string] $product, product name
     * @return array
     */
    public function searchProduct($product)
    {
        return $this->collection->find(['name' => $product]);
    }

    /**
     * Get additional information
     *
     * @param [string] $id, product ID
     * @return array
     */
    public function getAdditonalin($id)
    {
        // die(print_r(['_id'=>new MongoDB\BSON\ObjectID($id)]));
        // echo '<pre>';
        // print_r($this->collection->findOne(['_id'=>new MongoDB\BSON\ObjectID($id)]));die;
        return $this->collection->findOne(['_id'=>new MongoDB\BSON\ObjectID($id)]);
    }

    /**
     * Delete a product from collection
     *
     * @param [string] $id
     * @return void
     */
    public function deleteProduct($id)
    {
        $this->collection->deleteOne(['_id'=>new MongoDB\BSON\ObjectID($id)]);
    }
}
