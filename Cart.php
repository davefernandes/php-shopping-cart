<?php

class Cart
{
    private $cartSession;

    /**
    ** Cart contructor
    */
    public function __construct() {
        $this->cartSession = array();
    }


    /**
    ** Check if Product already exists
    */
    public function alreadyExists($key) {
        foreach( $this->cartSession as $k_item=>$v_item ) {
            if( $v_item['id'] == $key ) {
                return $k_item;
            }
        }
        return null;
    }


    /**
    ** Add new item
    */
    public function add($key,$name=null,$price=null,$qty=null) {

        if( $this->alreadyExists($key) !== null ) {
            $this->update($key,$qty);
            return;
        } else {
            if( $name === null || $price === null || $qty === null ) {
                throw new Exception('Product Details not provided');
                return;
            }
            array_push($this->cartSession,[
                'id' => $key,
                'name' => $name,
                'price' => round($price,2),
                'qty' => $qty,
                'total' => round($price*$qty,2)
            ]);
        }
        
        return;
    }


    /**
    ** Update Quantity of an existing item
    */
    public function update($key,$qty=null) {
        
        $getIndex = $this->alreadyExists($key);
        if( $getIndex !== null ) {
            if( $qty === null ) {
                throw new Exception('Quantity not provided');
            }
            $price = $this->cartSession[$getIndex]['price'];
            $newQuantity = $this->cartSession[$getIndex]['qty'] + $qty;
            $this->cartSession[$getIndex]['qty'] = $newQuantity;
            $this->cartSession[$getIndex]['total'] = round($price*$newQuantity,2);
        } else {
            throw new Exception('Cannot find Item to Update');
        }
        
        return;
    }


    /**
    ** Remove an item
    */
    public function remove($key) {
        
        $getIndex = $this->alreadyExists($key);

        if( $getIndex !== null ) {
            //using splice to re-index
            array_splice($this->cartSession,$getIndex,1);
        } else {
            throw new Exception('Cannot find Item to Remove');
        }
        
        return;
    }

    /**
    ** Get all Items
    */
    public function getItems() {
        
        return $this->cartSession;

    }


    /**
    ** Get Cart Total
    */
    public function getTotal() {
        
        $total = 0;
        foreach( $this->cartSession as $item ) {
            $total += $item['total'] ;
        }

        return round($total,2);
    }

}