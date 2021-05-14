<?php
$idCategory ='';
$notification_arr['success'] = 'true';
$notification_arr['data'] = array();
// get total slide
if(isset($_REQUEST['id_topic']) && !(empty($_REQUEST['id_topic']))){

     $idCategory = $_REQUEST['id_topic'];
    
     $sql = "SELECT * FROM tbl_product_category WHERE category_parent = '$idCategory' ORDER BY tbl_product_category.id ASC";
$result = mysqli_query($conn, $sql);

// Get row count
$num = mysqli_num_rows($result);

$notification_arr['success'] = 'true';
$notification_arr['data'] = array();
//$news_item['category_son']= array();
if ($num > 0) {
    while ($row = $result->fetch_assoc()) {
        $news_item = array(
            'category_id' => $row['id'],
            'category_parent' => $row['category_parent'],
            'category_vn_title' => $row['category_vn_title'],
            'category_en_title' => $row['category_en_title'],
            'category_img' => $row['category_img'],  
        );
        
        // Push to "data"
        array_push($notification_arr['data'], $news_item);
    }
}
// Turn to JSON & output
echo json_encode($notification_arr);


}
else {
    $sql = "SELECT count(tbl_product_category.id) as category_total  FROM tbl_product_category
            WHERE tbl_product_category.category_parent = '0'
        ";
// echo $sql;
// exit();
$result = mysqli_query($conn, $sql);

// while ($row = $result->fetch_assoc()) {
//     $notification_arr['total'] = $row['category_total'];
// }

// $limit = 100;
// $page = 1;
// if (isset($_REQUEST['limit']) && $_REQUEST['limit'] != '') {
//     $limit = $_REQUEST['limit'];
// }
// if (isset($_REQUEST['page']) && $_REQUEST['page'] != '') {
//     $page = $_REQUEST['page'];
// }

//$notification_arr['total_page'] = strval(ceil($notification_arr['total'] / $limit));

// $notification_arr['limit'] = strval($limit);
// $start = ($page - 1) * $limit;

// query


$sql = "SELECT * FROM tbl_product_category 
        WHERE tbl_product_category.category_parent = '0'";



//$sql .= " ORDER BY tbl_product_category.id DESC LIMIT $start,$limit ";
$sql .= " ORDER BY tbl_product_category.id DESC ";

$result = mysqli_query($conn, $sql);

// Get row count
$num = mysqli_num_rows($result);

$notification_arr['success'] = 'true';
// $notification_arr['page'] = strval($page);
$notification_arr['data'] = array();
//$news_item['category_son']= array();
if ($num > 0) {
    while ($row = $result->fetch_assoc()) {
        $news_item = array(
            'category_id' => $row['id'],
            'category_vn_title' => $row['category_vn_title'],
            'category_en_title' => $row['category_en_title'],
            'category_img' => $row['category_img'],  
           // 'category_parent' => $row['category_parent'],
        );
        
        // Push to "data"
        array_push($notification_arr['data'], $news_item);
    }
}
// Turn to JSON & output
echo json_encode($notification_arr);

}
?>