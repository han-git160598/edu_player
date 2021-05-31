<?php

$sql = "SELECT 
            `tbl_product_product`.`id` as `id_product`,
            `tbl_product_product`.`product_name` as `product_name`,
            `tbl_product_product`.`product_img` as `product_img`,
            `tbl_product_product`.`product_music_file` as `product_music_file`,
            `tbl_product_product`.`id_category` as `id_category`

        
            FROM `tbl_product_favourite`
            LEFT JOIN `tbl_product_product` ON `tbl_product_product`.`id` = `tbl_product_favourite`.`id_product`
            LEFT JOIN `tbl_customer_customer` ON `tbl_customer_customer`.`id` = `tbl_product_favourite`.`id_customer`
            WHERE 1=1
            ";



if(isset($_REQUEST['id_customer']) && !(empty($_REQUEST['id_customer']))){
    $id_customer = $_REQUEST['id_customer'];
    $sql .="AND (`tbl_customer_customer`.`id` = '{$id_customer}') ";
}else{
    returnError("Nhập id_customer");
}


$product_arr = array();

// $total = count(db_fetch_array($sql));
// $limit = 100;
// $page = 1;
// if (isset($_REQUEST['limit']) && !empty($_REQUEST['limit'])) { 
//     $limit = $_REQUEST['limit'];
// }

// if (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) {
//     $page = $_REQUEST['page'];
// }


// $total_page = ceil($total / $limit);
// $start = ($page - 1) * $limit;
//$sql .= " ORDER BY `tbl_product_product`.`id` DESC LIMIT {$start},{$limit}";
$sql .= " ORDER BY `tbl_product_product`.`id` DESC";


$product_arr['success'] = 'true';

// $product_arr['total'] = strval($total);
// $product_arr['total_page'] = strval($total_page);
// $product_arr['limit'] = strval($limit);
// $product_arr['page'] = strval($page);
$product_arr['data'] = array();
$result = db_qr($sql);

$nums = db_nums($result);


if ($nums > 0) {
    while ($row = db_assoc($result)) {
        $product_item = array(
            'id_product' => $row['id_product'],
            'product_name' => htmlspecialchars_decode($row['product_name']),
            'product_img' => htmlspecialchars_decode($row['product_img']),
            'product_music_file' => htmlspecialchars_decode($row['product_music_file']),
            'category_en_title' => htmlspecialchars_decode($row['category_en_title']),
            'id_category' => htmlspecialchars_decode($row['id_category']),
            'category_parent' => htmlspecialchars_decode($row['category_parent']),
            'category_vn_title' => htmlspecialchars_decode($row['category_vn_title']),
        );

        array_push($product_arr['data'], $product_item);
    }
    reJson($product_arr);
} else {
    returnError("Không có dữ liệu");
}
