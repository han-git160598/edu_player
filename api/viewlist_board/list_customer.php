<?php

$sql = "SELECT * FROM `tbl_customer_customer` WHERE 1=1";

$customer_disable = 'Y';

if (isset($_REQUEST['customer_disable']) && !empty($_REQUEST['customer_disable'])) {
    $customer_disable = htmlspecialchars($_REQUEST['customer_disable']);
}
if (isset($_REQUEST['id_customer']) && !empty($_REQUEST['id_customer'])) {
    $id_customer = htmlspecialchars($_REQUEST['id_customer']);

    $sql .= " AND `id` = '$id_customer'";
}

$sql .= " AND `customer_status` = '$customer_disable'";

if (isset($_REQUEST['filter'])) {
    if ($_REQUEST['filter'] == '') {
        unset($_REQUEST['filter']);
    } else {
        $filter = htmlspecialchars($_REQUEST['filter']);
        $sql .= " AND ( `customer_fullname` LIKE '%{$filter}%'";
        $sql .= " OR `customer_phone` LIKE '%{$filter}%' )";
    }
}
// if (isset($_REQUEST['date_begin'])) {
//     if ($_REQUEST['date_begin'] == '') {
//         unset($_REQUEST['date_begin']);
//     } else {
//         $date_begin = $_REQUEST['date_begin'];
//     }
// }
// if (isset($_REQUEST['date_end'])) {
//     if ($_REQUEST['date_end'] == '') {
//         unset($_REQUEST['date_end']);
//     } else {
//         $date_end = $_REQUEST['date_end'];
//     }
// }



// if (!empty($date_begin) && !empty($date_end)) {
//     $sql .= " AND (DATE(`customer_registered`) >= '$date_begin' AND DATE(`customer_registered`) <= '$date_end')";
// }
// if (!empty($customer_introduce)) {
//     $sql .= " AND  `customer_introduce` = '$customer_introduce' ";
// }

$customer_arr = array();

$total = count(db_fetch_array($sql));
$limit = 20;
$page = 1;

if (isset($_REQUEST['limit']) && !empty($_REQUEST['limit'])) {
    $limit = $_REQUEST['limit'];
}
if (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) {
    $page = $_REQUEST['page'];
}


$total_page = ceil($total / $limit);
$start = ($page - 1) * $limit;
$sql .= " ORDER BY `tbl_customer_customer`.`id` DESC LIMIT {$start},{$limit}";


$customer_arr['success'] = 'true';

$customer_arr['total'] = strval($total);
$customer_arr['total_page'] = strval($total_page);
$customer_arr['limit'] = strval($limit);
$customer_arr['page'] = strval($page);
$customer_arr['data'] = array();
$result = db_qr($sql);

$nums = db_nums($result);


if ($nums > 0) {
    while ($row = db_assoc($result)) {
        $customer_item = array(
            'id_customer' => $row['id'],
            'customer_name' => htmlspecialchars_decode($row['customer_fullname']),
            'customer_phone' => htmlspecialchars_decode($row['customer_phone']),
            'customer_disable' => htmlspecialchars_decode($row['customer_status']),
            'customer_avatar' => htmlspecialchars_decode($row['customer_avatar']),
        );

        array_push($customer_arr['data'], $customer_item);
    }
    reJson($customer_arr);
} else {
    returnError("Không có khách hàng");
}
