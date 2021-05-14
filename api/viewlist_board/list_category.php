<?php

$filter = '';
if (isset($_REQUEST['filter'])) {
    if ($_REQUEST['filter'] == '') {
        unset($_REQUEST['filter']);
    } else {
        $filter = $_REQUEST['filter'];
    }
}


      
    
$id_category='';
if (isset($_REQUEST['id_category']) && $_REQUEST['id_category'] != '') {
    $id_category = $_REQUEST['id_category'];
}


// // get total slide
// $sql = "SELECT count(tbl_product_category.id) as category_total  FROM tbl_product_category
//             WHERE tbl_product_category.category_parent = '0'
//         ";
// // echo $sql;
// // exit();
// $result = mysqli_query($conn, $sql);

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

// $notification_arr['total_page'] = strval(ceil($notification_arr['total'] / $limit));

// $notification_arr['limit'] = strval($limit);
// $start = ($page - 1) * $limit;

// query


$sql = "SELECT * FROM tbl_product_category 
        WHERE 1=1";


if (isset($_REQUEST['category_hot']) && $_REQUEST['category_hot'] != '') {
    $category_hot = $_REQUEST['category_hot'];
    $sql .= " AND category_hot = '$category_hot' ";
}else{
    if (isset($_REQUEST['filter']) && $_REQUEST['filter'] != '') {
        $filter = $_REQUEST['filter'];
        $sql .= "
        AND  category_parent != '0'
        AND (category_vn_title LIKE '%" . $filter . "%' OR category_en_title LIKE '%" . $filter . "%' )";
    }else{
        if (!empty($id_category)) {
            $notification_arr['total']='1';
            $sql .= " AND id = '".$id_category."'";
        }else{
           $sql.=" AND tbl_product_category.category_parent = '0' ";
        }
    }
}






// if (!empty($home_action) ) {
//     $sql .= " AND tbl_product_category.category_en_title = '".$filter."'";
// }

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
            'id' => $row['id'],
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
?>