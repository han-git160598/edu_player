<?php

function handing_file_mp31($myfile, $dir_save,$id_product,$id_category)
{    // myfile = file nhập vào, $max_size = kích thước lớn nhất của file, 
    // $allow_file_type = các đuôi file cho phép, $dir_save = thư mục lưu trữ
     $last_name = file_name($myfile);
    if ($_FILES[$myfile]['error'] == 0) {
        $target_dir = $dir_save;
        $target_dir_4_upload = '../' . $dir_save;
        $target_file = $target_dir . basename($_FILES[$myfile]['name']);
        $target_save_file = $target_dir_4_upload . basename($_FILES[$myfile]['name']);

        $allow_file_type = array('mp3','mpeg');
        $max_file_size = 8242880;
        $img_file_type = pathinfo($target_file, PATHINFO_EXTENSION);

        // kiem tra co phai file anh
        $check = getimagesize($_FILES[$myfile]['tmp_name']);
        //echo $check;exit();
        if (1==1) {
            $img_info = pathinfo($_FILES[$myfile]['name']);
            $img_info['filename'] = $id_category.'_Category_'.$id_product.$last_name;
            $name_copy = $img_info['filename'];
            $target_file = $target_dir . $name_copy . "." . $img_info['extension'];
            $target_save_file = $target_dir_4_upload . $name_copy . "." . $img_info['extension'];
          
            if ($_FILES[$myfile]['size'] > $max_file_size) {
                return_error("file size is greater than" . $max_file_size);
            }

            if (!in_array(strtolower($img_file_type), $allow_file_type)) {
                return_error("file type is not allow,". implode(",",$allow_file_type));
            }
            
            move_uploaded_file($_FILES[$myfile]['tmp_name'], $target_save_file);
            // return_success($target_file);
            return $target_file;
        }else {
            return_error("Không phải mp3");
        }
      
    } else {
        return_error("Lỗi dữ liệu");
    }
}
function file_name($name)
{
   switch($name)
   { 
       case 'product_en_file':{
            $new_name = '_Product_EN';
            break;
       }
       case 'product_vn_file':{
            $new_name = '_Product_VN';
            break;
       }
       case 'product_ex_en_file':{
            $new_name = '_Product_Ex_EN';
            break;
       }
       case 'product_ex_vn_file':{
            $new_name = '_Product_Ex_VN';
            break;
       }
   }

    return $new_name;
}
// xu ly hinh anh

function handing_files_img($myfile, $dir_save)
{   // myfile = file nhập vào, $max_size = kích thước lớn nhất của file, 
    // $allow_file_type = các đuôi file cho phép, $dir_save = thư mục lưu trữ
    $total = count($_FILES[$myfile]['name']);
    for ($i = 0; $i < $total; $i++) {
        if ($_FILES[$myfile]['error'][$i] == 0) {
            $target_dir = $dir_save;
            $target_dir_4_upload = '../' . $dir_save;
            $target_file = $target_dir . basename($_FILES[$myfile]['name'][$i]);
            $target_save_file = $target_dir_4_upload . basename($_FILES[$myfile]['name'][$i]);

            $allow_file_type = array('jpg', 'jpeg', 'png');
            $max_file_size = 5242880;
            $img_file_type = pathinfo($target_file, PATHINFO_EXTENSION);

            // kiem tra co phai file anh
            $check = getimagesize($_FILES[$myfile]['tmp_name'][$i]);
            if ($check !== false) {
                $img_info = pathinfo($_FILES[$myfile]['name'][$i]);
                if (file_exists($target_save_file)) {
                    $k = 0;
                    $name_copy = $img_info['filename'] . "_Copy_" . $k;
                    $target_file = $target_dir . $name_copy . "." . $img_info['extension'];
                    $target_save_file = $target_dir_4_upload . $name_copy . "." . $img_info['extension'];
                    while (file_exists($target_save_file)) {
                        $k++;
                        $name_copy = $img_info['filename'] . "_Copy_" . $k;
                        $target_file = $target_dir . $name_copy . "." . $img_info['extension'];
                        $target_save_file = $target_dir_4_upload . $name_copy . "." . $img_info['extension'];
                    }
                }

                if ($_FILES[$myfile]['size'][$i] > $max_file_size) {
                    return_error("file size is greater than {$max_file_size}");
                }

                if (!in_array(strtolower($img_file_type), $allow_file_type)) {
                    return_error("file type is not allow,". implode(",",$allow_file_type));
                }

                move_uploaded_file($_FILES[$myfile]['tmp_name'][$i], $target_save_file);

                $file[] = $target_file;
            } else {
                return_error("Không phải ảnh");
            }
        } else {

            return_error("Lỗi dữ liệu");
        }
    }
    if (isset($file) && !empty($file)) {
        return $file;
    }
}



function handing_file_img($myfile, $dir_save)
{    // myfile = file nhập vào, $max_size = kích thước lớn nhất của file, 
    // $allow_file_type = các đuôi file cho phép, $dir_save = thư mục lưu trữ
    if ($_FILES[$myfile]['error'] == 0) {
        
        $_FILES[$myfile]['name'] = convert_vi_to_en($_FILES[$myfile]['name']);

        $target_dir = $dir_save;
        $target_dir_4_upload = '../' . $dir_save;
        $target_file = $target_dir . basename($_FILES[$myfile]['name']);
        $target_save_file = $target_dir_4_upload . basename($_FILES[$myfile]['name']);

        $allow_file_type = array('jpg', 'jpeg', 'png');
        $max_file_size = 5242880;
        $img_file_type = pathinfo($target_file, PATHINFO_EXTENSION);

        // kiem tra co phai file anh
        $check = getimagesize($_FILES[$myfile]['tmp_name']);
        if ($check !== false) {
            $img_info = pathinfo($_FILES[$myfile]['name']);
            if (file_exists($target_save_file)) {
                $k = 0;
                $name_copy = $img_info['filename'] . "_Copy_" . $k;
                $target_file = $target_dir . $name_copy . "." . $img_info['extension'];
                $target_save_file = $target_dir_4_upload . $name_copy . "." . $img_info['extension'];
                while (file_exists($target_save_file)) {
                    $k++;
                    $name_copy = $img_info['filename'] . "_Copy_" . $k;
                    $target_file = $target_dir . $name_copy . "." . $img_info['extension'];
                    $target_save_file = $target_dir_4_upload . $name_copy . "." . $img_info['extension'];
                }
            }

            if ($_FILES[$myfile]['size'] > $max_file_size) {
                return_error("file size is greater than {$max_file_size}");
            }

            if (!in_array(strtolower($img_file_type), $allow_file_type)) {
                return_error("file type is not allow, {$allow_file_type}");
            }

            move_uploaded_file($_FILES[$myfile]['tmp_name'], $target_save_file);
            // return_success($target_file);
            return $target_file;
        } else {
            return_error("Không phải ảnh");
        }
    } else {
        return_error("Lỗi dữ liệu");
    }
}

function returnEmptyData($msg, $data = array())
{
    echo json_encode(array(
        'success' => 'true',
        'message' => $msg,
        'data' => $data,
    ));
    exit();
}

function db_assoc($result)
{
    return mysqli_fetch_assoc($result);
}
function db_nums($result)
{
    $nums = mysqli_num_rows($result);
    return $nums;
}

function db_qr($sql)
{
    global $conn;
    $result = mysqli_query($conn, $sql);
    if (!empty($result)) {
        return $result;
    }
    return false;
}

function errorCode($error_code, $msg = "")
{
    echo json_encode(array(
        'success' => 'false',
        'error_code' => $error_code,
        'message' => $msg,
    ));
    exit();
}
function errorToken($error_code, $msg = "", $data = array())
{
    echo json_encode(array(
        'success' => ($error_code == '4001') ? 'true' : 'false',
        'message' => $msg,
        'error_code' => $error_code,
        'data' => $data
    ));
    exit();
}

function return_error($message)
{
    echo json_encode(array(
        'success' => 'false',
        'message' => $message,
    ));
    exit();
}

function reJson($array)
{
    echo json_encode($array);
    exit();
}
function returnError($string)
{
    echo json_encode(
        array('success' => 'false', 'message' => $string)
    );
    exit();
}
function returnSuccess($string)
{
    echo json_encode(
        array('success' => 'true', 'message' => $string)
    );
    exit();
}

function getRolePermission($idUser = '')
{
    global $conn;
    $sql = "SELECT * FROM tbl_account_permission";

    if (!empty($idUser)) {
        $sql = " SELECT 
            tbl_account_permission.id,
            tbl_account_permission.permission,
            tbl_account_permission.description

            FROM tbl_account_permission
            LEFT JOIN tbl_account_authorize
            ON tbl_account_permission.id = tbl_account_authorize.grant_permission

            WHERE tbl_account_authorize.id_admin = '" . $idUser . "'
			
			ORDER BY tbl_account_authorize.grant_permission ASC
        ";
    }

    $result = mysqli_query($conn, $sql);
    // mysqli_close($conn);
    // Get row count
    $num = mysqli_num_rows($result);
    $arr_result = array();
    // Check if any item
    if ($num > 0) {

        while ($row = $result->fetch_assoc()) {

            $role_item = array(
                'id' => $row['id'],
                'permission' => $row['permission'],
                'description' => $row['description']
            );
            // Push to "data"
            array_push($arr_result, $role_item);
        }
    }

    return $arr_result;
}

function saveImage($file, $target_save = '')
{
    $link_image = '';
    if (isset($file) && is_uploaded_file($file['tmp_name'])) {
        // check file size (1048576: 1MB) 5242880

        if ($file['size'] >= 5242880) {
            //  returnError("only accept file size < 5MB!");

            return "error_size_img";
        }
        // check file type
        $allowedTypes = array(
            IMAGETYPE_PNG,
            IMAGETYPE_JPEG,
            IMAGETYPE_GIF
        );
        $detectedType = exif_imagetype($file['tmp_name']);
        $error = !in_array($detectedType, $allowedTypes);

        if ($error) {
            //returnError("only accept PNG, JPEG, GIF !");
            return "error_type_img";
        }

        $target_dir = $target_save;
        $target_dir_4_upload = '../' . $target_save;
        $final_name = basename($file["name"]);

        $path = $file['name'];
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $final_name = generateRandomString(60) . '.' . $ext;

        // end handle way to rename

        while (file_exists($target_dir_4_upload . $final_name)) {
            // doi ten file
            $final_name = generateRandomString(60) . '.' . $ext;
        }

        // upload file toi folder icon
        $target_file_upload = $target_dir_4_upload . $final_name;
        $target_file = $target_dir . $final_name;

        move_uploaded_file($file["tmp_name"], $target_file_upload);

        $link_image = $target_file;
    }

    return $link_image;
}
function generateRandomString($length)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function pushNotification($title, $message, $action, $to, $type_send = 'topic', $server_key = 'AAAAfbkjjRI:APA91bGqe1uj3LWTadQknDpceDD3o7rJMn46UyEAV9lUvIq08E1m2ZFnEmPC61pJ9YREY2xZ-MSE1WJ_3fDBady57c4oyjNCcvcRqnFgw1HmoOY6btUpGvGz1Cdsr85CBltY5snBY3Hj')
{
    $message_data = array(
        'title' => $title,
        'body' => $message,
        "click_action" => $action,
        "badge" => "1"
    );
    $headers = array(
        'Authorization: key=' . $server_key,
        'Content-Type: application/json'
    );

    $data = array();

    if (!empty($type_send) && $type_send == 'single') {
        require_once 'notification.php';
        $notification = new Notification();

        $notification->setTitle($title);
        $notification->setMessage($message);
        $notification->setAction($action);

        $requestData = $notification->getNotificatin();

        $data['to'] = $to;
        $data['data'] = $requestData;
    } else {
        $data['to'] = "/topics/" . $to;
        $data['notification'] = $message_data;
    }

    $data = json_encode($data);

    // print_r($data);
    // exit

    $url = 'https://fcm.googleapis.com/fcm/send';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $result = curl_exec($ch);
    curl_close($ch);
}


function convert_vi_to_en($str)
{
    $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", "a", $str);
    $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", "e", $str);
    $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", "i", $str);
    $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", "o", $str);
    $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", "u", $str);
    $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", "y", $str);
    $str = preg_replace("/(đ)/", "d", $str);
    $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", "A", $str);
    $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", "E", $str);
    $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", "I", $str);
    $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", "O", $str);
    $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", "U", $str);
    $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", "Y", $str);
    $str = preg_replace("/(Đ)/", "D", $str);
    $str = preg_replace("/(\“|\”|\‘|\’|\,|\!|\&|\;|\@|\#|\%|\~|\`|\=|\_|\'|\]|\[|\}|\{|\)|\(|\+|\^)/", '-', $str);
    $str = preg_replace("/( )/", '_', $str);
    return $str;
}