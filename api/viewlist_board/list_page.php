<?php

$sql = "SELECT 
            `tbl_product_product`.`id` as `id_product`,
            `tbl_product_product`.`product_name` as `product_name`,
            `tbl_product_product`.`product_img` as `product_img`,
            `tbl_product_product`.`product_duration` as `product_duration`,
            `tbl_product_product`.`product_music_file` as `product_music_file`,


            `tbl_product_category`.`id` as `id_category`,
            `tbl_product_category`.`category_vn_title` as `category_vn_title`,
            `tbl_product_category`.`category_img` as `category_img`,
            `tbl_product_category`.`category_en_title` as `category_en_title`,
            `tbl_product_category`.`category_parent` as `category_parent`";
         

$sql .=     " FROM `tbl_product_product` ";
            

$sql .= " LEFT JOIN `tbl_product_category` ON `tbl_product_category`.`id` = `tbl_product_product`.`id_category`
            WHERE 1=1
            ";
            

if(isset($_REQUEST['id_category']) && !(empty($_REQUEST['id_category']))){
    $id_category = $_REQUEST['id_category'];
    $sql .="AND (`tbl_product_product`.`id_category` = '{$id_category}') ";
}else {
    returnError("Nhập id_category");
}




$product_arr = array();

$sql .= " ORDER BY `tbl_product_product`.`id` ASC";


$total = count(db_fetch_array($sql));
$limit = 100;

$total_page = ceil($total / $limit);

$start=0 ;
$finish = 0 ;

// $product_arr['total'] = strval($total);
$product_arr['total_page'] = strval($total_page);
// $product_arr['limit'] = strval($limit);
// $product_arr['page'] = strval($page);



$product_arr['success'] = 'true';
$product_arr['data'] = array();
$result = db_qr($sql);
$nums = db_nums($result);

if ($nums > 0) {
    
    $row = db_assoc($result);
    
    for($i = 0; $i < $total_page ; $i++)
    {
        $start = $finish + 1;    // 1
        $finish = $start + $limit - 1 ; //  1 + 2
        $product_item = array(
            'page'=> $i+1,
            'title' => "[Phát ".$start .' - ' . $finish.']',
            'category_img' => htmlspecialchars_decode($row['category_img']),
        );  
         array_push($product_arr['data'], $product_item); 
    }


    reJson($product_arr);
       
} else {
    returnError("Không có dữ liệu");
}
