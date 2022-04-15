<?php

use Phalcon\Mvc\Controller;

class ProductController extends Controller
{
    /**
     * get all products from database to view
     * Handle the search product request
     *
     * @return void
     */
    public function viewAction()
    {
        $productModel=new Products();
        $searchText=$this->request->getPost('search');
        if (isset($searchText)) {
            $this->view->products= $productModel->searchProduct($searchText);
        } else {
            $this->view->products= $productModel->getProducts();
        }
    }

    /**
     * add product to collection
     *
     * @return void
     */

    public function addAction()
    {
    //    echo '<pre>';
    //    print_r($this->request->getPost());die;
       if ($this->request->isPost()) {
           $formData=$this->request->getPost();
           //Call for filter function
           $product=$this->filterProduct($formData);
           $productModel=new Products();
           $productModel->add($product);
       }
    }

    /**
     * function to get addtional info to show in modal
     *
     * @return void
     */
    public function getAdditionalInfoAction()
    {
        if ($this->request->isAjax()) {
            $id=$this->request->getPost('id');
            $productModel=new Products();
            return json_encode($productModel->getAdditonalin($id));
        }
    }
    
    /**
     * Delete function
     *
     * @param [string] $id, ID of the selected product
     * @return void
     */
    public function deleteAction($id)
    {
        $productModel=new Products();
        $productModel->deleteProduct($id);
        $this->response->redirect('product/view');
    }

    /**
     * Filter function
     * take product data and transform it to appropriate form
     * To insert into collection
     *
     * @param [array] $formData, Product data recieved on submit of form
     * @return [array] $product , Modified products array
     */
    private function filterProduct($formData)
    {
        $product=array();
        $product['name']=$formData['name'];
        $product['category']=$formData['category'];
        $product['price']=$formData['price'];
        $product['stock']=$formData['stock'];
        $product['meta']=array();
        $product['variation']=array();
        $i=1;
        $j=1;
        while ($i<$formData['noOfMetaFields']) {
            $metaField=[$formData['lableName'.$i.'']=>$formData['lableValue'.$i.'']];
            array_push($product['meta'],$metaField);
            $i+=1;
        }
        while ($j<$formData['noOfVariationFields']) {
            $variationField=[$formData['variationKey'.$j.'']=>$formData['variationValue'.$j.''], 'price'=>$formData['variationprice'.$j.'']];
            array_push($product['variation'],$variationField);
            $j+=1;
        }

        return $product;
    }
}
