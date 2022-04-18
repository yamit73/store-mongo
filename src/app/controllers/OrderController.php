<?php

use Phalcon\Mvc\Controller;

class OrderController extends Controller
{
   /**
    * View all orders
    * Apply filter to view orders
    * @return void
    */
    public function viewAction()
    {
        $orderModel=new Orders();
        $this->view->orders=$orderModel->getOrders();

        if ($this->request->isPost()) {
            $formData=$this->request->getPost();
            $filter=$this->filterOrder($formData);
            $this->view->orders=$orderModel->getFilteredOrders($filter);
        }
        
    }
    /**
     * Update order status
     * It will update onle when request is send through ajax
     *
     * @return void
     */
    public function updateAction()
    {
        if ($this->request->isAjax()) {
            $id=$this->request->getPost('id');
            $status=$this->request->getPost('status');
            $orderModel=new Orders();
            return $orderModel->updateStatus($status, $id)->getMatchedCount();
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
     * filter date according to selected filter in order/view controller
     * @param [array] $formData
     * @return array
     */
    private function filterOrder($formData)
    {
        $filter=[];
        $filter['status']=$formData['order_status'];
        if ($formData['order_date']=='Custom') {
            $filter['date']['from']=new MongoDB\BSON\UTCDateTime(new \DateTimeImmutable(date("Y-m-d H:i:s",strtotime($formData['from_date']))));
            $filter['date']['to']=new MongoDB\BSON\UTCDateTime(new \DateTimeImmutable(date("Y-m-d H:i:s",strtotime($formData['to_date']))));
        } elseif ($formData['order_date']=='This week') {
            $filter['date']['from']=new MongoDB\BSON\UTCDateTime(new \DateTimeImmutable(date("Y-m-d H:i:s", strtotime("last monday"))));
            $filter['date']['to']=new MongoDB\BSON\UTCDateTime(new \DateTimeImmutable(date("Y-m-d H:i:s", strtotime("now"))));
        } elseif ($formData['order_date']=='This Month') {
            $filter['date']['from']=new MongoDB\BSON\UTCDateTime(new \DateTimeImmutable(date("Y-m-d H:i:s", strtotime("first day of this month"))));
            $filter['date']['to']=new MongoDB\BSON\UTCDateTime(new \DateTimeImmutable(date("Y-m-d H:i:s", strtotime("now"))));
        }  elseif ($formData['order_date']=='Today') {
            $filter['date']['from']=new MongoDB\BSON\UTCDateTime(new \DateTimeImmutable(date("Y-m-d H:i:s", strtotime("today"))));
            $filter['date']['to']=new MongoDB\BSON\UTCDateTime(new \DateTimeImmutable(date("Y-m-d H:i:s", strtotime("now"))));
        }
        return $filter;
    }
}
