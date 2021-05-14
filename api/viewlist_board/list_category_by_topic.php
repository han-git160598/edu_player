<?php
$sql = "SELECT id,category_vn_title,category_en_title,category_img,category_parent,category_hot
        FROM tbl_product_category 
        WHERE category_parent != '0' ";

if (isset($_REQUEST['category_parent']) && !empty($_REQUEST['category_parent'])) {
    $category_parent = $_REQUEST['category_parent'];
    $sql .= " AND category_parent = '$category_parent'";
}

$result_arr = array();
$result_arr['success'] = "true";
$result_arr['data'] = array();
$result = db_qr($sql);
$nums = db_nums($result);
if($nums > 0){
    
  
    while($row = db_assoc($result)){
        $result_item = array(
            'id' => $row['id'],
            'category_vn_title' => $row['category_vn_title'],
            'category_en_title' => $row['category_en_title'],
            'category_img' => $row['category_img'],
            'category_hot' => $row['category_hot'],
            'category_parent' => $row['category_parent']
        );
        array_push($result_arr['data'], $result_item);
    }
}
reJson($result_arr);