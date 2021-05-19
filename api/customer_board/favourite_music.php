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

    case 'list_favourite':
        $id_prodcut = '';
        if (isset($_REQUEST['id_customer'])) {
            if ($_REQUEST['id_customer'] == '') {
                unset($_REQUEST['id_customer']);
            } else {
                $id_customer = $_REQUEST['id_customer'];
            }
        }else {
            returnError("Nhập id_customer");
        }

        $product_arr = array();
        // get total customer
        $sql = "SELECT count( tbl_product_favourite.id_product) as product_total  
                FROM  tbl_product_favourite 
                WHERE tbl_product_favourite.id_customer = '{$id_customer}' ";

        $result = mysqli_query($conn, $sql);

        while ($row = $result->fetch_assoc()) {
            $product_arr['total'] = $row['product_total'];
        }

        $limit = 100;
        $page = 1;
        if (isset($_REQUEST['limit']) && $_REQUEST['limit'] != '') {
            $limit = $_REQUEST['limit'];
        }
        if (isset($_REQUEST['page']) && $_REQUEST['page'] != '') {
            $page = $_REQUEST['page'];
        }

        $product_arr['total_page'] = strval(ceil($product_arr['total'] / $limit));

        $product_arr['limit'] = strval($limit);
        $start = ($page - 1) * $limit;

        // query
        $sql = "SELECT
                `tbl_customer_customer`.`id` as `id_customer`,
                `tbl_product_favourite`.`id` as `id_favourite`,
                `tbl_product_product`.`id` as `id_product`,
                `tbl_product_product`.`product_music_file` as `product_music_file`,    
                `tbl_product_product`.`product_name` as `product_name`,
                `tbl_product_product`.`product_img` as `product_img`
                 FROM tbl_product_favourite
                 LEFT JOIN tbl_product_product ON tbl_product_product.id = tbl_product_favourite.id_product
                 LEFT JOIN tbl_customer_customer ON tbl_customer_customer.id = tbl_product_favourite.id_customer
                 WHERE tbl_product_favourite.id_customer = '{$id_customer}'
                 ";

        $sql .= " ORDER BY tbl_product_product.id DESC  LIMIT $start,$limit";

        
        $result = mysqli_query($conn, $sql);

  
        // Get row count
        $num = mysqli_num_rows($result);

        // Check if any categories

        $product_arr['success'] = 'true';
        $product_arr['page'] = $page;
        $product_arr['data'] = array();

        if ($num > 0) {
            // Cat array
            while ($row = $result->fetch_assoc()) {
                $favourite_item = array(
                    'id' => $row['id_customer'],
                    'product_music_file' => $row['product_music_file'],
                    'product_name' => $row['product_name'],
                    'product_img' => $row['product_img'],
                );

                // Push to "data"
                array_push($product_arr['data'], $favourite_item);
            }
        }
        // Turn to JSON & output
        echo json_encode($product_arr);

        break;

    case 'create_favourite':

        
     
        if (isset($_REQUEST['id_product']) && $_REQUEST['id_product'] != '') {
            $id_product = $_REQUEST['id_product'];
        }else {
            returnError("Nhập id_product");;
        }
        if (isset($_REQUEST['id_customer']) && $_REQUEST['id_customer'] != '') {
            $id_customer = $_REQUEST['id_customer'];
        }else {
            returnError("Nhập id_customer");;
        }

        //check username exists
        $sql_check_favourite_exists = "SELECT *
                FROM tbl_product_favourite
                WHERE tbl_product_favourite.id_customer  = '$id_customer'
                AND tbl_product_favourite.id_product = '$id_product'";
                

        $result_check_favourite_exists = mysqli_query($conn, $sql_check_favourite_exists);
        $num_result_check_favourite_exists = mysqli_num_rows($result_check_favourite_exists);
        if ($num_result_check_favourite_exists > 0) {
            returnError("product đã tồn tại trong favourite !");
        }


        $sql_create_favourite = "INSERT INTO tbl_product_favourite SET
              id_customer = '" . $id_customer . "'
              , id_product = '" . $id_product . "'
        ";
  

        if ($conn->query($sql_create_favourite)) {
            returnSuccess("Lưu vào mục yêu thích thành công!");
        } else {
            returnError("Lưu vào mục yêu thích không thành công!");
        }

        break;

    case 'delete_favourite':

        if (isset($_REQUEST['id_customer']) && $_REQUEST['id_customer'] != '') {
            $id_customer = $_REQUEST['id_customer'];
        }else {
            returnError("Nhập id_customer");;
        }
        if (isset($_REQUEST['id_product']) && $_REQUEST['id_product'] != '') {
            $id_product = $_REQUEST['id_product'];
        }else {
            returnError("Nhập id_product");;
        }

    
        $sql_delete_favourite = "
                DELETE FROM tbl_product_favourite
                WHERE id_product =  '" . $id_product . "'
                AND id_customer =  '" . $id_customer . "'
        ";
        if ($conn->query($sql_delete_favourite)) {
            returnSuccess("Xóa bài hát yêu thích thành công!");
        } else {
            returnError("Xóa bài hát yêu thích không thành công!");
        }
    
           
    

        break;

    default:
        returnError("type_manager is not accept!");

        break;
}
