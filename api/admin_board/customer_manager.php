<?php

if (isset($_REQUEST['type_manager'])) {
    if ($_REQUEST['type_manager'] == '') {
        unset($_REQUEST['type_manager']);
        returnError("Nhập type_manager");
    } else {
        $type_manager = $_REQUEST['type_manager'];
    }
} else {
    returnError("Nhập type_manager");
}

switch ($type_manager) {
    case 'resset_password_account': {
            $idUser = '';
            if (isset($_REQUEST['id_user'])) {
                if ($_REQUEST['id_user'] == '') {
                    unset($_REQUEST['id_user']);
                    returnError("Nhập id_user");
                }
            } else {
                returnError("Nhập id_user");
            }

            if (isset($_REQUEST['password_reset'])) {
                if ($_REQUEST['password_reset'] == '') {
                    unset($_REQUEST['password_reset']);
                    returnError("Nhập password_reset");
                }
            } else {
                returnError("Nhập password_reset");
            }

            $id_account = $_REQUEST['id_user'];
            $password_reset = $_REQUEST['password_reset'];

            $sql_check_account_exists = "SELECT * FROM tbl_customer_customer WHERE id = '" . $id_account . "'";

            $result_check = mysqli_query($conn, $sql_check_account_exists);
            $num_result_check = mysqli_num_rows($result_check);

            if ($num_result_check > 0) {

                $query = "UPDATE tbl_customer_customer SET ";
                $query .= "customer_password  = '" . md5(mysqli_real_escape_string($conn, $password_reset)) . "' ";
                $query .= "WHERE id = '" . $id_account . "'";
                // check execute query
                if ($conn->query($query)) {
                    returnSuccess("Cập nhật mật khẩu thành công!");
                } else {
                    returnError("Cập nhật mật khẩu không thành công!");
                }
            } else {
                returnError("Không tìm thấy tài khoản!");
            }
            exit();
            break;
        }
    case 'delete': {
            if (isset($_REQUEST['id_customer'])) {
                if ($_REQUEST['id_customer'] == '') {
                    unset($_REQUEST['id_customer']);
                    returnError("Nhập id_customer");
                } else {
                    $id_customer = $_REQUEST['id_customer'];
                }
            } else {
                returnError("Nhập id_customer");
            }

            $success = array(); 
            

            $sql_favourite = "DELETE FROM `tbl_product_favourite` WHERE `id_customer` = '{$id_customer}'";
            db_qr($sql_favourite);
            
            $sql = "DELETE FROM `tbl_customer_customer` WHERE `id` = '{$id_customer}'";
            if (db_qr($sql)) {
                $success['delete_customer'] = "true";
            }

            if (!empty($success)) {
                returnSuccess("Xóa thành công");
            } else {
                returnError("Xóa thất bại");
            }
            break;

            break;
        }
    case 'update': {

            if (isset($_REQUEST['id_customer'])) {
                if ($_REQUEST['id_customer'] == '') {
                    unset($_REQUEST['id_customer']);
                    returnError("Nhập id_customer");
                } else {
                    $id_customer = $_REQUEST['id_customer'];
                }
            } else {
                returnError("Nhập id_customer");
            }

            $success = array();
            if (isset($_REQUEST['customer_name']) && !empty($_REQUEST['customer_name'])) { //*
                $customer_name = htmlspecialchars($_REQUEST['customer_name']);
                $sql = "UPDATE `tbl_customer_customer` SET";
                $sql .= " `customer_fullname` = '{$customer_name}'";
                $sql .= " WHERE `id` = '{$id_customer}'";

                if (mysqli_query($conn, $sql)) {
                    $success['customer_name'] = "true";
                }
            }



            if (isset($_FILES['customer_avatar_img'])) {
                $sql = "SELECT * FROM `tbl_customer_customer` WHERE `id` = '{$id_customer}'";
                $result = mysqli_query($conn, $sql);
                $nums = mysqli_num_rows($result);
                if ($nums > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $customer_avatar_img = $row['customer_avatar'];
                        if (file_exists("../" . $customer_avatar_img)) {
                            @unlink("../" . $customer_avatar_img);
                        }
                    }
                }
                $customer_avatar_img = 'customer_avatar_img';
                $dir_save_customer_avatar_img = "images/customer_avatar/";
                $dir_save_cert_img = handing_file_img($customer_avatar_img, $dir_save_customer_avatar_img);
                $sql = "UPDATE `tbl_customer_customer`
                    SET `customer_avatar` = '{$dir_save_cert_img}' 
                    WHERE `id` = '{$id_customer}'";
                if (mysqli_query($conn, $sql)) {
                    $success['customer_avatar_img'] = 'true';
                }
            }
            // print_r($success);
            // exit();
           
            if (!empty($success)) {
                // if (isset($success['customer_disable']) && !empty($success['customer_disable']) && $customer_disable == 'Y') {
                //     $title = "Thông báo khóa tài khoản!!!";
                //     $bodyMessage = "Tài khoản đã bị khóa";
                //     $action = "customer_disable";
                //     $type_send = 'topic';
                //     $to = 'KSE_customer_disable_'.$id_customer;
                //     pushNotification($title, $bodyMessage, $action, $to, $type_send);
                // }
                returnSuccess("Cập nhật thành công");
            } else {
                returnError("Không có thông tin cập nhật");
            }
            break;
        }
    case 'create': {

            if (isset($_REQUEST['customer_name'])) {   //*
                if ($_REQUEST['customer_name'] == '') {
                    unset($_REQUEST['customer_name']);
                    returnError("Nhập tên khách hàng");
                } else {
                    $customer_name = htmlspecialchars($_REQUEST['customer_name']);
                }
            } else {
                returnError("Nhập tên khách hàng");
            }


            if (isset($_REQUEST['customer_phone'])) {  //*
                if ($_REQUEST['customer_phone'] == '') {
                    unset($_REQUEST['customer_phone']);
                    returnError("Nhập số điện thoại");
                } else {
                    $customer_phone = htmlspecialchars($_REQUEST['customer_phone']);
                }
            } else {
                returnError("Nhập số điện thoại");
            }

            if (isset($_REQUEST['customer_password'])) {  //*
                if ($_REQUEST['customer_password'] == '') {
                    unset($_REQUEST['customer_password']);
                    returnError("Nhập số mật khẩu");
                } else {
                    $customer_password = md5($_REQUEST['customer_password']);
                }
            } else {
                returnError("Nhập số mật khẩu");
            }
   
            

            $sql = "SELECT * FROM `tbl_customer_customer` 
                            WHERE `customer_phone` = '{$customer_phone}'
                            ";
            $result = db_qr($sql);
            $nums = db_nums($result);
            if ($nums > 0) {
                returnError("Đã tồn tại khách hàng này");
            }
            
            $dir_save_avatar_img = '';
            if (isset($_FILES['customer_avatar_img'])) { // up product_img
                $customer_avatar_img = 'customer_avatar_img';
                $dir_save_customer_avatar_img = "images/customer_avatar/"; // sửa đường dẫn
                $dir_save_avatar_img = handing_file_img($customer_avatar_img, $dir_save_customer_avatar_img);
            } else {
                //returnError("Chụp ảnh CMND mặt trước");
            }
            $sql = "INSERT INTO `tbl_customer_customer` SET 
                                                `customer_fullname` = '{$customer_name}',
                                                `customer_phone` = '{$customer_phone}',
												`customer_password` = '{$customer_password}'
                                                ";

            if (isset($dir_save_avatar_img) && !empty($dir_save_avatar_img)) {
                $sql .= " ,`customer_avatar` = '{$dir_save_avatar_img}'";
            }
        

            if (mysqli_query($conn, $sql)) {
                returnSuccess("Tạo khách hàng thành công");
            } else {
                returnError("Tạo khách hàng không thành công");
            }
            break;
        }

    case 'list_customer_detail': {
            include_once "./viewlist_board/list_customer_detail.php";
            break;
        }


    case 'list_customer': {
            include_once "./viewlist_board/list_customer.php";
            break;
        }
    default: {
            returnError("Khong ton tai type_manager");
            break;
        }
}
