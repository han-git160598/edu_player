<?php

$sql = "SELECT tbl_product_product.id,
               tbl_product_product.product_name_en,
               tbl_product_product.product_name_vn,
               tbl_product_product.product_spelling,
               tbl_product_product.product_img,
               tbl_product_product.product_en_file,
               tbl_product_product.product_vn_file,
               tbl_product_product.product_ex_en_file,
               tbl_product_product.product_ex_vn_file,
               tbl_product_product.product_ex_en,
               tbl_product_product.product_ex_vn


        FROM tbl_product_product
        LEFT JOIN tbl_product_category ON tbl_product_category.id = tbl_product_product.id_category
        WHERE 1=1 ";
            

if(isset($_REQUEST['id_category']) && !(empty($_REQUEST['id_category']))){
    $id_category = $_REQUEST['id_category'];
    $sql .="AND (`tbl_product_product`.`id_category` = '{$id_category}') ";
}

if(isset($_REQUEST['id_product']) && !(empty($_REQUEST['id_product']))){
    $id_product = $_REQUEST['id_product'];
    $sql .="AND (`tbl_product_product`.`id` = '{$id_product}') ";
}

if (isset($_REQUEST['filter'])) {
    if ($_REQUEST['filter'] == '') {
        unset($_REQUEST['filter']);
    } else {
        $filter = htmlspecialchars($_REQUEST['filter']);
        $sql .= " AND (`tbl_product_product`.`product_name_en` LIKE '%{$filter}%'";
        $sql .=" OR `tbl_product_product`.`product_name_vn` LIKE '%{$filter}%')";
        $sql .=" OR `tbl_product_category`.`category_en_title` LIKE '%{$filter}%'";
        $sql .=" OR `tbl_product_category`.`category_vn_title` LIKE '%{$filter}%'";
    }
}



$product_arr = array();

$sql .= " ORDER BY `tbl_product_product`.`id` DESC";



$product_arr['success'] = 'true';
$product_arr['data'] = array();
$result = db_qr($sql);

$nums = db_nums($result);

if ($nums > 0) {
    while ($row = db_assoc($result)) {
        $product_item = array(
            'id_product' => $row['id'],
            'product_name_en' => htmlspecialchars_decode($row['product_name_en']),
            'product_name_vn' => htmlspecialchars_decode($row['product_name_vn']),
            'product_spelling' => htmlspecialchars_decode($row['product_spelling']),
            'product_img' => htmlspecialchars_decode($row['product_img']),
            'product_en_file' => htmlspecialchars_decode($row['product_en_file']),
            'product_vn_file' => htmlspecialchars_decode($row['product_vn_file']),
            'product_ex_en_file' => htmlspecialchars_decode($row['product_ex_en_file']),
            'product_ex_vn_file' => htmlspecialchars_decode($row['product_ex_vn_file']),
            'product_ex_en' => htmlspecialchars_decode($row['product_ex_en']),
            'product_ex_vn' => htmlspecialchars_decode($row['product_ex_vn']),
        );

        array_push($product_arr['data'], $product_item);
    }
    reJson($product_arr);
} 
reJson($product_arr);
