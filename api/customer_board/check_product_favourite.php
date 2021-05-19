
<?php

if(isset($_REQUEST['id_customer']) && !(empty($_REQUEST['id_customer']))){
    $id_customer = $_REQUEST['id_customer'];
}else{
    returnError("Nhập id_customer");
}
if(isset($_REQUEST['id_product']) && !(empty($_REQUEST['id_product']))){
    $id_product = $_REQUEST['id_product'];
    
}else{
    returnError("Nhập id_product");
}



$sql = "SELECT * FROM tbl_product_favourite 
        WHERE id_customer = '$id_customer' AND id_product ='$id_product' ";

$product_arr = array();
$product_arr['success'] = 'true';
$product_arr['data'] = array();
$result = db_qr($sql);

$nums = db_nums($result);


if ($nums > 0) {
    $product_arr['success'] = 'true';
    
}else {
    $product_arr['success'] = 'false';
}

reJson($product_arr);