<?php

$filter = '';
if (isset($_REQUEST['filter'])) {
    if ($_REQUEST['filter'] == '') {
        unset($_REQUEST['filter']);
    } else {
        $filter = $_REQUEST['filter'];
    }
}


$type_target='';
if (isset($_REQUEST['type_target']) && $_REQUEST['type_target'] != '') {
    $type_target = $_REQUEST['type_target'];
}else {
    returnError("Nhập type_target");
}

switch($type_target)
{
    case 'admin':{
        $sql = "SELECT * FROM tbl_product_category 
        WHERE 1=1";

$id_category='';
if (isset($_REQUEST['id_category']) && $_REQUEST['id_category'] != '') {
    $id_category = $_REQUEST['id_category'];
}

if (isset($_REQUEST['category_hot']) && $_REQUEST['category_hot'] != '') {
    $category_hot = $_REQUEST['category_hot'];
    $sql .= " AND category_hot = '$category_hot' ";
}else{
    if (isset($_REQUEST['filter']) && $_REQUEST['filter'] != '') {
        $filter = $_REQUEST['filter'];
        $sql .= "
        AND  category_parent = '0'
        AND (category_vn_title LIKE '%" . $filter . "%' OR category_en_title LIKE '%" . $filter . "%' )";
    }else{
        if (!empty($id_category)) {
            $sql .= " AND id = '".$id_category."'";
        }else{
            $sql.=" AND tbl_product_category.category_parent = '0' ";
        }
    }
}



$sql .= " ORDER BY tbl_product_category.id DESC ";

$result = mysqli_query($conn, $sql);

// Get row count
$num = mysqli_num_rows($result);

$notification_arr['success'] = 'true';
$notification_arr['data'] = array();
//$news_item['category_son']= array();
if ($num > 0) {
    while ($row = $result->fetch_assoc()) {
        $news_item = array(
            'id_category' => $row['id'],
            'category_vn_title' => $row['category_vn_title'],
            'category_en_title' => $row['category_en_title'],
            'category_img' => $row['category_img'],  
            'category_parent' => $row['category_parent'],
            'category_hot' => $row['category_hot'],
            'category_son' => (isset($id_category) && !empty($id_category))?getCategorySon($row['id'], $conn):"" 
        );
        
        // Push to "data"
        array_push($notification_arr['data'], $news_item);
    }
}
// Turn to JSON & output
echo json_encode($notification_arr);
exit();

        break;
    }
    case 'customer':{
        $sql = "SELECT * FROM tbl_product_category 
        WHERE 1=1";

    $id_category='';

    if (isset($_REQUEST['id_category']) && $_REQUEST['id_category'] != '') {
        $id_category = $_REQUEST['id_category'];
        $sql .= " AND category_parent = '".$id_category."'";
    }

    if (isset($_REQUEST['filter']) && $_REQUEST['filter'] != '') {
        $filter = $_REQUEST['filter'];
        $sql .= "
        AND  category_parent = '0'
        AND (category_vn_title LIKE '%" . $filter . "%' OR category_en_title LIKE '%" . $filter . "%' )";
    }
    

    $sql .= " ORDER BY tbl_product_category.id DESC ";

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
                'category_vn_title' => $row['category_vn_title'],
                'category_en_title' => $row['category_en_title'],
                'category_img' => $row['category_img'],  
                'category_parent' => $row['category_parent'],
                'category_hot' => $row['category_hot'],
              //  'category_son' => (isset($id_category) && !empty($id_category))?getCategorySon($row['id'], $conn):"" 
            );
            
            // Push to "data"
            array_push($notification_arr['data'], $news_item);
        }
    }
    // Turn to JSON & output
    echo json_encode($notification_arr);
    exit();
        break;
    }
}

?>