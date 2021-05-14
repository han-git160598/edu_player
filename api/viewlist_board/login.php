<?php

if (isset($_REQUEST['username']) && !(empty($_REQUEST['username']))) {
    if (is_username($_REQUEST['username'])) {
        $username = $_REQUEST['username'];
    } else {
        returnError("username không đúng định dạng");
    }
} else {
    returnError("Nhập username");
}

if (isset($_REQUEST['password']) && !(empty($_REQUEST['password']))) {
    if (is_password($_REQUEST['password'])) {
        $password = md5($_REQUEST['password']);
    } else {
        returnError("password không đúng định dạng");
    }
} else {
    returnError("Nhập password");
}


$sql = "SELECT 
            tbl_account_account.id as id_account,
            tbl_account_account.account_username as account_username,
            tbl_account_account.account_fullname as account_fullname,
            tbl_account_account.account_phone as account_phone,
            tbl_account_account.account_email as account_email,
            tbl_account_account.account_status as account_status,
            tbl_account_account.id_type as id_type,
            tbl_account_type.type_account as type_account

            FROM `tbl_account_account`
            LEFT JOIN tbl_account_type ON tbl_account_type.id = tbl_account_account.id_type
            WHERE `tbl_account_account`.`account_username` = '{$username}' 
            AND `tbl_account_account`.`account_password` = '{$password}'
            ";

$result = db_qr($sql);
$nums = db_nums($result);
if ($nums > 0) {
    $user_arr = array();
    $user_arr['success'] = 'true';
    $user_arr['data'] = array();
    while ($row = db_assoc($result)) {
        if ($row['account_status'] == "N") {
            returnError("Tài khoản này đã bị khóa");
        }

      //  $account_token = md5($username . time());
        $query = "UPDATE tbl_account_account SET
                   force_sign_out  = '0',
                   WHERE id = '" . $row['id_account'] . "'";
        db_qr($query);

        $user_item = array(
            'id' => $row['id_account'],
            'account_username' => $row['account_username'],
            'account_fullname' => $row['account_fullname'],
            'account_phone' => $row['account_phone'],
            'account_email' => $row['account_email'],
            'type_account' => $row['type_account'],
        );

        if ($row['id_type'] == '1') {
            $user_item['role_permission'] = getRolePermission($row['id_account']);
        }

        array_push($user_arr['data'], $user_item);
    }
    reJson($user_arr);
} else {
    $sql = "SELECT * FROM tbl_customer_customer
            WHERE customer_phone = '$username'
            AND customer_password = '$password'";
    $result = db_qr($sql);
    $nums = db_nums($result);
    if ($nums > 0) {
        $user_arr = array();
        $user_arr['success'] = 'true';
        $user_arr['data'] = array();
        while ($row = db_assoc($result)) {

            if($row['customer_status'] == 'N'){
                returnError("Tài khoản đã bị khóa");
            }

            $query = "UPDATE tbl_customer_customer SET
                       force_sign_out  = '0',
                       WHERE id = '" . $row['id'] . "'";
            db_qr($query);

            $user_item = array(
                'id_customer' => $row['id'],
            
                'customer_phone' => $row['customer_phone'],
                'customer_name' => $row['customer_fullname'],
                'customer_avatar_img' => (isset($row['customer_avatar']) && !empty($row['customer_avatar']))?$row['customer_avatar']:"",

            );

            array_push($user_arr['data'], $user_item);
        }
        reJson($user_arr);
    }
}

returnError("Tài khoản hoặc mật khẩu không chính xác");
