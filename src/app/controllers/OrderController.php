<?php

use Phalcon\Mvc\Controller;

class OrderController extends Controller
{
   /**
    * 
    *
    * @return void
    */
    public function viewAction()
    {
        $orderModel=new Orders();
        $this->view->orders=$orderModel->getOrders();
        if ($this->request->isPost()) {
            $formData=$this->request->getPost();
            // echo '<pre>';
            // print_r($formData);die;
            $filter=$this->filterOrder($formData);
            echo '<pre>';
            print_r($filter);die;
        }
        
    }

    /**
     * Add order to the collection
     * product name, product id, order date, status, customer name, quantity
     * @return void
     */
    public function addAction()
    {
        $productModel=new Products();
        $this->view->products=$productModel->getProducts();
        if ($this->request->isPost()) {
            $orderModel=new Orders();
            $order=$this->request->getPost();
            $order['product_name']=$productModel->getAdditonalin($order['product_id'])['name'];
            //Add default status as Paid and order date
            $order['status']='Paid';
            $order['date']= new MongoDB\BSON\UTCDateTime(new \DateTimeImmutable(date("Y-m-d H:i:s")));
            $orderModel->add($order);
        }
    }
    /**
     * function
     * filter data according to selected filter in order/view controller
     * @param [array] $formData
     * @return array
     */
    private function filterOrder($formData)
    {
        $filter=[];
        $filter['status']=$formData['order_status'];
        if ($formData['order_date']=='Custom') {
            $filter['date']['from']=new MongoDB\BSON\UTCDateTime(new \DateTimeImmutable(date("Y-m-d H:i:s",$formData['from_date'])));
            $filter['date']['to']=new MongoDB\BSON\UTCDateTime(new \DateTimeImmutable(date("Y-m-d H:i:s",$formData['to_date'])));
        } elseif ($formData['order_date']=='This week') {
            $filter['date']['form']=new MongoDB\BSON\UTCDateTime(new \DateTimeImmutable(date("Y-m-d H:i:s", strtotime("this week"))));
            $filter['date']['to']=new MongoDB\BSON\UTCDateTime(new \DateTimeImmutable(date("Y-m-d H:i:s", strtotime("this week +6 day"))));
        } elseif ($formData['order_date']=='This week') {
            $filter['date']['form']=new MongoDB\BSON\UTCDateTime(new \DateTimeImmutable(date("Y-m-d H:i:s", strtotime("this month"))));
            $filter['date']['to']=new MongoDB\BSON\UTCDateTime(new \DateTimeImmutable(date("Y-m-d H:i:s")));
        }
        return $filter;
    }
}
