<?php

use Phalcon\Mvc\Controller;

class OrderController extends Controller
{
   /**
    * Insert documents into users collection
    *
    * @return void
    */
    public function viewAction()
    {
       
    }
    public function addAction()
    {
        $productModel=new Products();
        $this->view->products=$productModel->getProducts();
    }
}
