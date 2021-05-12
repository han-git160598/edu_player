<?php

if (isset($_REQUEST['customer_phone']) && !(empty($_REQUEST['customer_phone']))) {
    $customer_phone = $_REQUEST['customer_phone'];
} else {
    returnError("Vui lòng nhập số điện thoại");
}

if (isset($_REQUEST['customer_name']) && !(empty($_REQUEST['customer_name']))) {
    $customer_name = $_REQUEST['customer_name'];
} else {
    returnError("Vui lòng điền Họ và Tên");
}

if (isset($_REQUEST['customer_password']) && !(empty($_REQUEST['customer_password']))) {
    if (is_password($_REQUEST['customer_password'])) {
        $customer_password = md5($_REQUEST['customer_password']);
    } else {
        returnError("Mật khẩu không đúng định dạng");
    }
} else {
    returnError("Vui lòng nhập mật khẩu");
}


$sql = "SELECT * FROM tbl_customer_customer WHERE customer_phone = '$customer_phone'";
$result = db_qr($sql);
if ((db_nums($result)) > 0) {
    returnError("Đã tồn tại tài khoản");
}

$sql = "INSERT INTO tbl_customer_customer SET 
        customer_phone = '$customer_phone', 
        customer_fullname = '$customer_name', 
        customer_password = '$customer_password'";


if (db_qr($sql)) {

    $id_customer = mysqli_insert_id($conn);
    $sql = "SELECT * FROM tbl_customer_customer WHERE id = '$id_customer'";

    $result_arr = array();
    $result_arr['success'] = "true";
    $result_arr['data'] = array();

    $result = db_qr($sql);
    $nums = db_nums($result);
    if ($nums > 0) {
        while ($row = db_assoc($result)) {
            $result_item = array(
                'id_customer' => $row['id'],
                'customer_phone' => $row['customer_phone'],
                'customer_name' => $row['customer_fullname'],
            );
        }
        array_push($result_arr['data'], $result_item);
    }
    reJson($result_arr);
} else {
    returnError("Đăng kí không thành công");
}
