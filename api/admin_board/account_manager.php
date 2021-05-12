<?php
$typeManager = '';

if (isset($_REQUEST['type_manager'])) {
    if ($_REQUEST['type_manager'] == '') {
        unset($_REQUEST['type_manager']);
    }
}

if (!isset($_REQUEST['type_manager'])) {
    returnError("type_manager is missing!");
}
$typeManager = $_REQUEST['type_manager'];

switch ($typeManager) {

    case 'list_account':
        $id_account = '';
        if (isset($_REQUEST['id_account'])) {
            if ($_REQUEST['id_account'] == '') {
                unset($_REQUEST['id_account']);
            } else {
                $id_account = $_REQUEST['id_account'];
            }
        }
        $id_type = '';
        if (isset($_REQUEST['id_type'])) {
            if ($_REQUEST['id_type'] == '') {
                unset($_REQUEST['id_type']);
            } else {
                $id_type = $_REQUEST['id_type'];
            }
        }
        $filter = '';
        if (isset($_REQUEST['filter'])) {
            if ($_REQUEST['filter'] == '') {
                unset($_REQUEST['filter']);
            } else {
                $filter = $_REQUEST['filter'];
            }
        }

        $employee_arr = array();
        // get total customer
        $sql = "SELECT count( tbl_account_account.id) as employee_total  FROM  tbl_account_account WHERE 1=1 ";

        if (!empty($filter)) {
            $sql .= " AND (account_fullname LIKE '%{$filter}%'
                          OR account_username LIKE '%{$filter}%' 
                          OR account_phone LIKE '%{$filter}%')";
        }
        if (!empty($id_account)) {
            $sql .= " AND id = '$id_account'";
        }
        if (!empty($id_type)) {
            $sql .= " AND id_type = '$id_type'";
        }

        // echo $sql;
        // exit();

        $result = mysqli_query($conn, $sql);
        while ($row = $result->fetch_assoc()) {
            $employee_arr['total'] = $row['employee_total'];
        }

        $limit = 20;
        $page = 1;
        if (isset($_REQUEST['limit']) && $_REQUEST['limit'] != '') {
            $limit = $_REQUEST['limit'];
        }
        if (isset($_REQUEST['page']) && $_REQUEST['page'] != '') {
            $page = $_REQUEST['page'];
        }

        $employee_arr['total_page'] = strval(ceil($employee_arr['total'] / $limit));

        $employee_arr['limit'] = strval($limit);
        $start = ($page - 1) * $limit;

        // query
        $sql = "SELECT
                `tbl_account_account`.`id` as `id_account`,
                `tbl_account_account`.`id_type` as `id_type`,
                `tbl_account_account`.`account_username` as `account_username`,
                `tbl_account_account`.`account_password` as `account_password`,
                `tbl_account_account`.`account_fullname` as `account_fullname`,
                `tbl_account_account`.`account_email` as `account_email`,
                `tbl_account_account`.`account_phone` as `account_phone`,
                `tbl_account_account`.`account_status` as `account_status`,
                -- `tbl_account_account`.`account_code` as `account_code`,
                -- `tbl_account_account`.`account_token` as `account_token`, -- chưa bổ sung vào DB
                `tbl_account_account`.`force_sign_out` as `force_sign_out`,


                tbl_account_type.type_account as type_account,
                tbl_account_type.description as type_description
                 FROM tbl_account_account
                 LEFT JOIN tbl_account_type
                 ON tbl_account_account.id_type = tbl_account_type.id
                 WHERE 1=1
                 ";

        if (!empty($filter)) {
            $sql .= " AND (tbl_account_account.account_fullname LIKE '%" . $filter . "%' OR account_username LIKE '%" . $filter . "%' OR account_phone LIKE '%" . $filter . "%')";
        }
        if (!empty($id_account)) {
            $sql .= " AND tbl_account_account.id = '$id_account'";
        }
        if (!empty($id_type)) {
            $sql .= " AND tbl_account_account.id_type = '$id_type'";
        }

        $sql .= " ORDER BY tbl_account_account.id DESC  LIMIT $start,$limit";

        
        $result = mysqli_query($conn, $sql);

        // echo $sql;
        // exit();
        // Get row count
        $num = mysqli_num_rows($result);

        // Check if any categories

        $employee_arr['success'] = 'true';
        $employee_arr['page'] = $page;
        $employee_arr['data'] = array();

        if ($num > 0) {
            // Cat array
            while ($row = $result->fetch_assoc()) {
                $employee_item = array(
                    'id' => $row['id_account'],
                    'username' => $row['account_username'],
                    'id_type' => $row['id_type'],
                    'type_account' => $row['type_account'],
                   // 'account_code' => (!empty($row['account_code']))?$row['account_code']:"",
                    'email' => $row['account_email'],
                    'full_name' => $row['account_fullname'],
                    'phone_number' => $row['account_phone'],
                    'status_employee' => $row['account_status'],
                    'type_description' => $row['type_description'],
                    'role_permission' => getRolePermission($row['id_account'], $conn)
                );

                // Push to "data"
                array_push($employee_arr['data'], $employee_item);
            }
        }
        // Turn to JSON & output
        echo json_encode($employee_arr);

        break;

    case 'create_account':

        if (isset($_REQUEST['username']) && !(empty($_REQUEST['username']))) {
            if (is_username($_REQUEST['username'])) {
                $username = $_REQUEST['username'];
            } else {
                returnError("Tên đăng nhập không đúng định dạng");
            }
        } else {
            returnError("Điền tên đăng nhập");
        }

        //check username exists
        $sql_check_username_exists = "SELECT *
                FROM tbl_account_account
                WHERE account_username = '$username'";
        $result_check_username_exists = mysqli_query($conn, $sql_check_username_exists);
        $num_result_check_username_exists = mysqli_num_rows($result_check_username_exists);
        if ($num_result_check_username_exists > 0) {
            returnError("Tên đăng nhập đã tồn tại!");
        }

        if (isset($_REQUEST['password']) && !(empty($_REQUEST['password']))) {
            if (is_password($_REQUEST['password'])) {
                $password = md5($_REQUEST['password']);
            } else {
                returnError("Mật khẩu không đúng định dạng");
            }
        } else {
            returnError("Điền mật khẩu");
        }

        if (isset($_REQUEST['full_name'])) {
            if ($_REQUEST['full_name'] == '') {
                unset($_REQUEST['full_name']);
            }
        }

        if (!isset($_REQUEST['full_name'])) {
            returnError("Nhập họ và tên!");
        }

        $id_type = '';
        if (isset($_REQUEST['id_type'])) {
            if ($_REQUEST['id_type'] == '') {
                unset($_REQUEST['id_type']);
                returnError("Chọn loại tài khoản!");
            } else {
                $id_type = $_REQUEST['id_type'];
            }
        }

        $email = '';
        if (isset($_REQUEST['email']) &&  !empty($_REQUEST['email'])) {
            $email = $_REQUEST['email'];
        }

        $phone_number = '';
        if (isset($_REQUEST['phone_number']) &&  !empty($_REQUEST['phone_number'])) {
            $phone_number = $_REQUEST['phone_number'];
        } else {
            returnError("Nhập số điện thoại!");
        }

        $fullname = $_REQUEST['full_name'];

        $sql_create_user = "INSERT INTO tbl_account_account SET
              account_username = '" . $username . "'
              , account_password = '" . $password . "'
              , account_fullname = '" . $fullname . "'
              , account_phone = '" . $phone_number . "'
              , account_email = '" . $email . "'
              , id_type = '" . $id_type . "'
        ";

        if ($conn->query($sql_create_user)) {

            $id_created = mysqli_insert_id($conn);

            if (isset($_REQUEST['role_permission'])) {
                if ($_REQUEST['role_permission'] == '') {
                    unset($_REQUEST['role_permission']);
                } else {

                    if ($_REQUEST['role_permission'] != '-1') {
                        $rolePermission = explode(',', $_REQUEST['role_permission']);

                        foreach ($rolePermission as $itemRole) {
                            if (!empty($itemRole)) {
                                $sql_insert_role = "INSERT INTO tbl_account_authorize SET
                                    id_admin = '" . $id_created . "'
                                    , grant_permission = '" . $itemRole . "'
                                    
                                ";
                                mysqli_query($conn, $sql_insert_role);
                            }
                        }
                    }
                }
            }

            returnSuccess("Tạo tài khoản thành công!");
        } else {
            returnError("Tạo tài khoản không thành công!");
        }

        break;

    case 'update_account':

        $idUser = '';
        if (isset($_REQUEST['id_user'])) {
            if ($_REQUEST['id_user'] == '') {
                unset($_REQUEST['id_user']);
                returnError("id_user is missing!");
            } else {
                $idUser = $_REQUEST['id_user'];
            }
        } else {
            returnError("id_user is missing!");
        }

        $check = 0;

        // if (isset($_REQUEST['username']) && !empty($_REQUEST['username'])) {

        //     $username = $_REQUEST['username'];
        //     //check username exists
        //     $sql_check_username_exists = "SELECT *
        //         FROM tbl_account_account 
        //         WHERE account_username = '" . $username . "'";

        //     $result_check_username_exists = mysqli_query($conn, $sql_check_username_exists);
        //     $num_result_check_username_exists = mysqli_num_rows($result_check_username_exists);
        //     if ($num_result_check_username_exists > 0) {
        //         returnError("Tên đăng nhập đã tồn tại!");
        //     }

        //     $check++;
        //     $query = "UPDATE tbl_account_account SET ";
        //     $query .= " account_username  = '" . $username . "' ";
        //     $query .= " WHERE id = '" . $idUser . "'";
        //     // Create post
        //     if ($conn->query($query)) {
        //         $check--;
        //     }
        // }

        if (isset($_REQUEST['id_type']) && !empty($_REQUEST['id_type'])) {
            $id_type = $_REQUEST['id_type'];

            $check++;
            $query = "UPDATE tbl_account_account  SET ";
            $query .= " id_type  = '" . $id_type . "' ";
            $query .= " WHERE id = '" . $idUser . "'";
            // Create post
            if ($conn->query($query)) {
                $check--;
            }
        }
       // $customer_introduce = '';
        
        if (isset($_REQUEST['full_name']) && !empty($_REQUEST['full_name'])) {
            $check++;
            $query = "UPDATE tbl_account_account  SET ";
            $query .= " account_fullname  = '" . $_REQUEST['full_name'] . "' ";
            $query .= " WHERE id = '" . $idUser . "'";
            // Create post
            if ($conn->query($query)) {
                $check--;
            }
        }
        if (isset($_REQUEST['email']) && !empty($_REQUEST['email'])) {
            $check++;
            $query = "UPDATE tbl_account_account  SET ";
            $query .= " account_email  = '" . $_REQUEST['email'] . "' ";
            $query .= " WHERE id = '" . $idUser . "'";
            // Create post
            if ($conn->query($query)) {
                $check--;
            }
        }
        if (isset($_REQUEST['phone_number']) && !empty($_REQUEST['phone_number'])) {
            $check++;
            $query = "UPDATE tbl_account_account  SET ";
            $query .= " account_phone  = '" . $_REQUEST['phone_number'] . "' ";
            $query .= " WHERE id = '" . $idUser . "'";
            // Create post
            if ($conn->query($query)) {
                $check--;
            }
        }

        if (isset($_REQUEST['status']) && !empty($_REQUEST['status'])) {
            $check++;
            $query = "UPDATE tbl_account_account  SET ";
            $query .= "account_status  = '" . $_REQUEST['status'] . "' ";
            $query .= "WHERE id = '" . $idUser . "'";
            // Create post
            if ($conn->query($query)) {
                $check--;
            }
        }
        if (isset($_REQUEST['role_permission'])) {
            if ($_REQUEST['role_permission'] == '') {
                unset($_REQUEST['role_permission']);
            } else {

                $sql_check_user_role = "SELECT * FROM tbl_account_authorize WHERE id_admin = '" . $idUser . "'";

                $result_check_user_role = mysqli_query($conn, $sql_check_user_role);

                $num_result_check_role = mysqli_num_rows($result_check_user_role);

                if ($num_result_check_role > 0) {
                    $sql_delete_all_role = "DELETE FROM tbl_account_authorize WHERE id_admin = '" . $idUser . "'";
                    mysqli_query($conn, $sql_delete_all_role);
                }

                if ($_REQUEST['role_permission'] != '-1') {
                    $rolePermission = explode(',', $_REQUEST['role_permission']);

                    foreach ($rolePermission as $itemRole) {
                        if (!empty($itemRole)) {
                            $sql_insert_role = "INSERT INTO tbl_account_authorize SET id_admin = '" . $idUser . "', grant_permission = '" . $itemRole . "'";

                            mysqli_query($conn, $sql_insert_role);
                        }
                    }
                }

                
            }
        }

        if ($check == 0) {
            returnSuccess("Cập nhật thành công!");
        } else {
            returnError("Cập nhật không thành công");
        }

        break;

    case 'update_role_permission':
        $idUser = '';
        if (isset($_REQUEST['id_user'])) {
            if ($_REQUEST['id_user'] == '') {
                unset($_REQUEST['id_user']);
                returnError("id_user is missing!");
            } else {
                $idUser = $_REQUEST['id_user'];
            }
        } else {
            returnError("id_user is missing!");
        }

        if (isset($_REQUEST['role_permission'])) {
            if ($_REQUEST['role_permission'] == '') {
                unset($_REQUEST['role_permission']);
                returnError("role_permission is missing!");
            } else {
                $sql_check_user_role = "SELECT * FROM tbl_account_authorize 
                                            WHERE id_admin = '" . $idUser . "'
                                            ";

                $result_check_user_role = mysqli_query($conn, $sql_check_user_role);

                $num_result_check_role = mysqli_num_rows($result_check_user_role);

                if ($num_result_check_role > 0) {
                    $sql_delete_all_role = "DELETE FROM tbl_account_authorize WHERE id_admin = '" . $idUser . "'";
                    mysqli_query($conn, $sql_delete_all_role);
                }

                if ($_REQUEST['role_permission'] != '-1') {
                    $rolePermission = explode(',', $_REQUEST['role_permission']);

                    foreach ($rolePermission as $itemRole) {
                        if (!empty($itemRole)) {
                            $sql_insert_role = "INSERT INTO tbl_account_authorize SET 
                                                    id_admin = '" . $idUser . "', 
                                                    grant_permission = '" . $itemRole . "'";

                            mysqli_query($conn, $sql_insert_role);
                        }
                    }
                }
                

                returnSuccess("Cập nhật phân quyền thành công!", $token);
            }
        } else {
            returnError("role_permission is missing!");
        }

        break;

    case 'update_password':
        $idUser = '';
        if (isset($_REQUEST['id_user'])) {
            if ($_REQUEST['id_user'] == '') {
                unset($_REQUEST['id_user']);
                returnError("id_user is missing!");
            } else {
                $idUser = $_REQUEST['id_user'];
            }
        } else {
            returnError("id_user is missing!");
        }

        if (isset($_REQUEST['new_password'])) {
            if ($_REQUEST['new_password'] == '') {
                unset($_REQUEST['new_password']);
                returnError("new_password is missing!");
            } else {
                $new_password = $_REQUEST['new_password'];
            }
        } else {
            returnError("new_password is missing!");
        }
        if (isset($_REQUEST['old_password'])) {
            if ($_REQUEST['old_password'] == '') {
                unset($_REQUEST['old_password']);
                returnError("old_password is missing!");
            } else {
                $old_password = $_REQUEST['old_password'];
                $sql = "SELECT * FROM tbl_account_account WHERE id = ' . $idUser . '";
                $result = mysqli_query($conn, $sql);
                $num_result = mysqli_num_rows($result);
                if ($num_result > 0) {
                    while ($row = mysqli_fetch_array($result)) {
                        if ($row['account_password'] != md5($old_password)) {
                            returnError("Mật khẩu cũ không chính xác!");
                        }

                        $sql_update_password = "UPDATE tbl_account_account SET account_password = '" . md5($new_password) . "' WHERE id = '" . $idUser . "'";

                        if ($conn->query($sql_update_password)) {
                            returnSuccess("Cập nhật mật khẩu thành công!");
                        } else {
                            returnError("Cập nhật mật khẩu không thành công!");
                        }
                    }
                } else {
                    returnError("Không tìm thấy tài khoản!");
                }
            }
        } else {
            returnError("old_password is missing!");
        }

        break;

    case 'update_user_status':
        $idUser = '';
        if (isset($_REQUEST['id_user'])) {
            if ($_REQUEST['id_user'] == '') {
                unset($_REQUEST['id_user']);
                returnError("id_user is missing!");
            } else {
                $idUser = $_REQUEST['id_user'];
            }
        } else {
            returnError("id_user is missing!");
        }

        if (isset($_REQUEST['status'])) {
            if ($_REQUEST['status'] == '') {
                unset($_REQUEST['status']);
                returnError("status is missing!");
            } else {
                $user_status = $_REQUEST['status'];
            }
        } else {
            returnError("user_status is missing!");
        }

        $sql = "SELECT * FROM tbl_account_account WHERE id = '$idUser'";

        $result = mysqli_query($conn, $sql);
        $num_result = mysqli_num_rows($result);
        if ($num_result > 0) {
            while ($row = mysqli_fetch_array($result)) {
                $sql_update_status = "UPDATE tbl_account_account SET account_status = '" . $user_status . "' WHERE id = '" . $idUser . "'";

                if ($conn->query($sql_update_status)) {
                    returnSuccess("Cập nhật trạng thái thành công!");
                } else {
                    returnError("Cập nhật trạng thái không thành công!");
                }
            }
        } else {
            returnError("Không tìm thấy tài khoản!");
        }

        break;

    case 'resset_password_account':
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

        $sql_check_account_exists = "SELECT * FROM tbl_account_account WHERE id = '" . $id_account . "'";

        $result_check = mysqli_query($conn, $sql_check_account_exists);
        $num_result_check = mysqli_num_rows($result_check);

        if ($num_result_check > 0) {

            $query = "UPDATE tbl_account_account SET ";
            $query .= "account_password  = '" . md5(mysqli_real_escape_string($conn, $password_reset)) . "' ";
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

    case 'delete_account':
        if (isset($_REQUEST['id_user'])) {
            if ($_REQUEST['id_user'] == '') {
                unset($_REQUEST['id_user']);
                returnError("Nhập id_user");
            }
        }

        if (!isset($_REQUEST['id_user'])) {
            returnError("Nhập id_user");
        }

        $id_customer = $_REQUEST['id_user'];

        $sql_check_customer_exists = "SELECT * FROM tbl_account_account WHERE id = '" . $id_customer . "'";

        $result_check = mysqli_query($conn, $sql_check_customer_exists);
        $num_result_check = mysqli_num_rows($result_check);

        if ($num_result_check > 0) {

            // while($row_check = db_assoc($result_check)){
                
            //     $id_type = $row_check['id_type'];
            //     if($id_type != 1){
            //         $account_code = $row_check['account_code'];
            //         $sql_check_customer = "SELECT * FROM tbl_customer_customer WHERE BINARY customer_introduce = '$account_code'";
            //         $result_check_customer = db_qr($sql_check_customer);
            //         if(db_nums($result_check_customer) > 0){
            //             returnError("Không thể xóa. \n Bạn có thể ủy quyền cho một nhân viên khác.");
            //         }
            //     }

            // }
            $sql_check_account_role = "SELECT * FROM tbl_account_authorize WHERE id_admin = '" . $id_customer . "'";

            $result_check_role = mysqli_query($conn, $sql_check_account_role);

            $num_result_check_role = mysqli_num_rows($result_check_role);
            if ($num_result_check_role > 0) {
                $sql_delete_role = "DELETE FROM tbl_account_authorize
                            WHERE  id_admin = '" . $id_customer . "'";
                mysqli_query($conn, $sql_delete_role);
            }

            $sql_delete_customer = "
                            DELETE FROM tbl_account_account
                            WHERE  id = '" . $id_customer . "'
                          ";
            if ($conn->query($sql_delete_customer)) {
                returnSuccess("Xóa tài khoản thành công!");
            } else {
                returnError("Xóa tài khoản không thành công!");
            }
        } else {
            returnError("Không tìm thấy tài khoản!");
        }

        break;

    default:
        returnError("type_manager is not accept!");

        break;
}
