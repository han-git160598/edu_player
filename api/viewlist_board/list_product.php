<?php

$sql = "SELECT 
            `tbl_product_product`.`id` as `id_product`,
            `tbl_product_product`.`product_name` as `product_name`,
            `tbl_product_product`.`product_img` as `product_img`,
            `tbl_product_product`.`product_music_file` as `product_music_file`,

            `tbl_product_category`.`id` as `id_category`,
            `tbl_product_category`.`category_vn_title` as `category_vn_title`,
            `tbl_product_category`.`category_en_title` as `category_en_title`,
            `tbl_product_category`.`category_parent` as `category_parent`,

            `tbl_product_favourite`.`id` as `id_favourite`,
            `tbl_product_favourite`.`id_customer` as `id_customer`

            FROM `tbl_product_product`
            LEFT JOIN `tbl_product_favourite` ON `tbl_product_favourite`.`id_product` = `tbl_product_product`.`id`
            LEFT JOIN `tbl_product_category` ON `tbl_product_category`.`id` = `tbl_product_product`.`id_category`
            WHERE 1=1
            ";
            

if(isset($_REQUEST['id_customer']) && !(empty($_REQUEST['id_customer']))){
    $id_customer = $_REQUEST['id_customer'];
    $sql .="AND (`tbl_product_favourite`.`id_customer` = '{$id_customer}') ";
}

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

$sql .= " ORDER BY `tbl_product_product`.`id` DESC";


$product_arr['success'] = 'true';
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
            'favourite_status' => (!empty($row['id_favourite']))?"Y":"N"
        );

        array_push($product_arr['data'], $product_item);
    }
    reJson($product_arr);
} else {
    returnError("Không có dữ liệu");
}
