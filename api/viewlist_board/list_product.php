<?php

$sql = "SELECT 
            `tbl_product_product`.`id` as `id_product`,
            `tbl_product_product`.`product_name` as `product_name`,
            `tbl_product_product`.`product_img` as `product_img`,
            `tbl_product_product`.`product_music_file` as `product_music_file`,
            `tbl_product_product`.`id_category` as `id_category`,

            `tbl_product_category`.`category_vn_title` as `category_vn_title`,
            `tbl_product_category`.`category_en_title` as `category_en_title`,
            `tbl_product_category`.`category_parent` as `category_parent`
            FROM `tbl_product_product`
            LEFT JOIN `tbl_product_category` ON `tbl_product_category`.`id` = `tbl_product_product`.`id_category`
            WHERE 1=1
            ";
            

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
        $sql .= " AND (`tbl_product_product`.`product_name` LIKE '%{$filter}%'";
        $sql .=" OR `tbl_product_category`.`category_en_title` LIKE '%{$filter}%'";
        $sql .=" OR `tbl_product_category`.`category_vn_title` LIKE '%{$filter}%')";
    }
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
        );

        array_push($product_arr['data'], $product_item);
    }
    reJson($product_arr);
} else {
    returnError("Không có dữ liệu");
}
