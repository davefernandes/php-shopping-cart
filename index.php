<?php
error_reporting(E_STRICT);
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);

require_once('Cart.php');

session_start();

if( !isset($_SESSION['SHOPPING_CART']) ) {
    $_SESSION['SHOPPING_CART'] = new Cart();
}

// ###################################################
// ############### ALL PRODUCTS ######################

$products = [
    [ "name" => "Sledgehammer", "price" => 125.75 ],
    [ "name" => "Axe", "price" => 190.50 ],
    [ "name" => "Bandsaw", "price" => 562.131 ],
    [ "name" => "Chisel", "price" => 12.9 ],
    [ "name" => "Hacksaw", "price" => 18.45 ],
];

// ############### ALL PRODUCTS ######################
// ###################################################
 

if( isset($_GET['mode']) && trim($_GET['mode'] === 'add') ) {
    $productid = (isset($_GET['productid']) && intval($_GET['productid']) >= 0 && intval($_GET['productid']) < count($products) ) ? intval($_GET['productid']) : null;

    if( $productid === null ) {
        header("location: index.php?msg=1");
        exit;
    } else {

        try {
            $_SESSION['SHOPPING_CART']->add($productid,$products[$productid]['name'],$products[$productid]['price'],1);
        }
        catch( Exception $e) {
            print_r($e->getMessage());
            exit;
        }

        header("location: index.php?msg=2");
        exit;
    }
}

if( isset($_GET['mode']) && trim($_GET['mode'] === 'remove') ) {
    $productid = (isset($_GET['productid']) && intval($_GET['productid']) >= 0 && intval($_GET['productid']) < count($products) ) ? intval($_GET['productid']) : null;

    if( $productid === null ) {
        header("location: index.php?msg=1");
        exit;
    } else {

        try {
            $_SESSION['SHOPPING_CART']->remove($productid);
        }
        catch( Exception $e) {
            print_r($e->getMessage());
            exit;
        }

        header("location: index.php?msg=3");
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Basic Shopping Cart</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
</head>
<body>    
    <div class="container mb-5">
        <h1 class="my-3">Basic Shopping Cart</h1>
        <div class="row my-2">
            <div class="col-12">
                <h2>Products</h2>
            </div>
            <?php foreach( $products as $product_key => $product_item ) { ?>
                <div class="col-6 col-md-4 my-2">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?= $product_item['name']; ?></h5>
                            <p class="card-text">Price: <?= number_format($product_item['price'],2); ?></p>
                            <a href="index.php?mode=add&productid=<?= $product_key; ?>" class="btn btn-primary">Add to Cart</a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

        <?php if( isset($_GET['msg']) && intval($_GET['msg']) != 0 ) { ?>
            <div class="row my-2">
                <div class="col-12">                    
                    <?php if( intval($_GET['msg']) == 1 ) { ?>
                        <div class="alert alert-warning text-center">
                            Invalid Product Detected
                        </div>
                    <?php } else if( intval($_GET['msg']) == 2 ) { ?>
                        <div class="alert alert-success text-center">
                            Cart Updated
                        </div>
                    <?php } else if( intval($_GET['msg']) == 3 ) { ?>
                        <div class="alert alert-success text-center">
                            Item Removed
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>

        <?php 
        try {
            $allitems = $_SESSION['SHOPPING_CART']->getItems();
            if( count($allitems) > 0 ) { ?>
                <div class="row my-2">
                    <div class="col-12">
                        <h2>My Cart</h2>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                            <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach( $allitems as $cart_item ) { ?>
                                <tr>
                                    <td><?= $cart_item['name']; ?></td>
                                    <td><?= number_format($cart_item['price'],2); ?></td>
                                    <td><?= $cart_item['qty']; ?></td>
                                    <td><?= number_format($cart_item['total'],2); ?></td>
                                    <td>
                                        <a href="index.php?mode=remove&productid=<?= $cart_item['id']; ?>" class="btn btn-sm btn-danger">Remove</a>
                                    </td>
                                </tr>       
                            <?php } ?>               
                            </tbody>
                            </table>
                            <h3>Cart Total: <?= number_format($_SESSION['SHOPPING_CART']->getTotal(),2); ?></h3>
                            </div>
                        </div>
                    </div>
                    
                </div>
            <?php } 
        }
        catch( Exception $e) {
            print_r($e->getMessage());
            exit;
        }
        ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</body>
</html>