<?php
    $sql = "SELECT * FROM tbl_product_category 
    WHERE 1=1";

$id_category='';
if (isset($_REQUEST['id_category']) && $_REQUEST['id_category'] != '') {
    $id_category = $_REQUEST['id_category'];
    $sql .= " AND id = $id_category ";
}

if (isset($_REQUEST['filter']) && $_REQUEST['filter'] != '') {
    $filter = $_REQUEST['filter'];
    $sql .= " AND (category_vn_title LIKE '%" . $filter . "%' OR category_en_title LIKE '%" . $filter . "%' )";
}

$sql .= " ORDER BY tbl_product_category.id DESC ";

$result = mysqli_query($conn, $sql);

// Get row count
$num = mysqli_num_rows($result);

$notification_arr['success'] = 'true';
$notification_arr['data'] = array();
if ($num > 0) {
    while ($row = $result->fetch_assoc()) {
        $news_item = array(
            'id_category' => $row['id'],
            'category_vn_title' => $row['category_vn_title'],
            'category_en_title' => $row['category_en_title'],
            'category_img' => $row['category_img'],  
        );
        // Push to "data"
        array_push($notification_arr['data'], $news_item);
    }
}

echo json_encode($notification_arr);
exit();

   