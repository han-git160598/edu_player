<?php
if (isset($_REQUEST['type_manager'])) {
    if ($_REQUEST['type_manager'] == '') {
        unset($_REQUEST['type_manager']);
    }
}

if (! isset($_REQUEST['type_manager'])) {
    returnError("type_manager is missing!");
}   

$typeManager = $_REQUEST['type_manager'];

switch ($typeManager) {
    case 'list_product':
        include_once './viewlist_board/list_product.php';
        break;
    
    case 'create_product':
        
        $title = '';
        if (isset($_REQUEST['product_name']) && ! empty($_REQUEST['product_name'])) {
            $product_name = $_REQUEST['product_name'];
        } else {
            returnError("Nhập product_name");
        }
        $id_category='';
        if (isset($_REQUEST['id_category']) && ! empty($_REQUEST['id_category'])) {
            $id_category  = $_REQUEST['id_category'];
        } else {
            returnError("Nhập id_category ");
        }
        $product_duration='';
        if (isset($_REQUEST['product_duration']) && ! empty($_REQUEST['product_duration'])) {
            $product_duration  = $_REQUEST['product_duration'];
        } else {
            returnError("Nhập product_duration ");
        }

        $sql_category = "SELECT category_en_title, category_parent FROM tbl_product_category
                         WHERE id = '$id_category'
        ";

        $result = db_qr($sql_category);
        $nums = db_nums($result);
        if($nums > 0)
        {
            while($row=db_assoc($result))
            {
                $category_en_title = $row['category_en_title'];
                $category_parent = $row['category_parent'];
            }
        }

        $sql_category_topic = "SELECT category_en_title FROM tbl_product_category
                         WHERE id = '$category_parent'
        ";
        $result_topic = db_qr($sql_category_topic);
        $nums_topic = db_nums($result_topic);
        if($nums_topic > 0)
        {
            while($row_topic=db_assoc($result_topic))
            {
                $category_en_title_topic = $row_topic['category_en_title'];
            }
        }


        $dir_save_product_music_file2='';
        if (isset($_FILES['product_music_file']) && ! empty($_FILES['product_music_file'])) { // up product_img
            
            $product_music_file = 'product_music_file';
            $dir_save_product_music_foler = "music_file/" . $category_en_title_topic . "/" . $category_en_title ."/";
            $dir_save_product_music_file2 = handing_file_img($product_music_file, $dir_save_product_music_foler);
        
        } else {
            returnError("Nhập product_music_file ");
        }


        $dir_save_product_img2 = '';
        if (isset($_FILES['product_img']) && ! empty($_FILES['product_img'])) {
            $customer_product_img = 'product_img';
            $dir_save_product_img = "images/product_product/"; // sửa đường dẫn
            $dir_save_product_img2 = handing_file_img($customer_product_img, $dir_save_product_img);
        } else {
            returnError("Nhập product_img ");
        }
        
        
        $sql_create_product = "
                                INSERT INTO tbl_product_product SET
                                  product_name = '$product_name'
                                , id_category = '$id_category'
                                , product_duration = '$product_duration'
                                , product_img = '$dir_save_product_img2'
                                , product_music_file = '$dir_save_product_music_file2'";

        if ($conn->query($sql_create_product)) {
            returnSuccess("Tạo sản phẩm thành công!");
        } else {
            returnError("Tạo sản phẩm không thành công!");
        }
        
        break;
    
    case 'update_product':

        
        $category_en_title='';
        if (isset($_REQUEST['category_en_title']) && ! empty($_REQUEST['category_en_title'])) {
            $category_en_title  = $_REQUEST['category_en_title'];
        } else {
            returnError("Nhập category_en_title");
        }

        $category_parent='';
        if (isset($_REQUEST['category_parent']) && ! empty($_REQUEST['category_parent'])) {
            $category_parent  = $_REQUEST['category_parent'];
        } else {
            returnError("Nhập category_parent");
        }
        
        $id_product = '';
        if (isset($_REQUEST['id_product']) && ! empty($_REQUEST['id_product'])) {
            $id_product = $_REQUEST['id_product'];
        } else {
            returnError("Nhập id_product!");
        }
        
        $check = 0;
        
        if (isset($_REQUEST['product_name']) && ! empty($_REQUEST['product_name'])) {
            $check ++;
            $query = "UPDATE tbl_product_product SET ";
            $query .= " product_name  = '" . $_REQUEST['product_name'] . "' ";
            $query .= " WHERE id = '" . $id_product . "'";
            // Create post
            if ($conn->query($query)) {
                $check --;
            } else {
                returnError("Cập nhật sản phẩm không thành công!");
            }
        }
    
        $img_photo_product = '';
        if (isset($_FILES['img_product'])) {
            $check++;
            $sql = "SELECT * FROM `tbl_product_product` WHERE `id` = '{$id_product}'";
            $result = mysqli_query($conn, $sql);
            $nums = mysqli_num_rows($result);
            if ($nums > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $product_img = $row['product_img'];
                    if (file_exists("../" . $product_img)) {
                        @unlink("../" . $product_img);
                    }
                }
            }
            $product_img = 'img_product';
            $dir_save_product_img = "images/product_category/";
            $dir_save_product_img2 = handing_file_img($category_img, $dir_save_product_img);
            $sql = "UPDATE `tbl_product_product`
                SET `product_img` = '{$dir_save_product_img2}' 
                WHERE `id` = '{$id_product}'";
            if ($conn->query($sql)) {
                $check --;
            } else {
                returnError("Cập nhật img không thành công!");
            }
        }


//// truy van duong dan luu file mp3

        $sql_category_topic = "SELECT category_en_title FROM tbl_product_category
                         WHERE id = '$category_parent'
        ";
        $result_topic = db_qr($sql_category_topic);
        $nums_topic = db_nums($result_topic);
        if($nums_topic > 0)
        {
            while($row_topic=db_assoc($result_topic))
            {
                $category_en_title_topic = $row_topic['category_en_title'];
            }
        }

        $product_music_file = '';
        if (isset($_FILES['product_music_file'])) {
            $check++;
            $sql = "SELECT * FROM `tbl_product_product` WHERE `id` = '{$id_product}'";
            $result = mysqli_query($conn, $sql);
            $nums = mysqli_num_rows($result);
            if ($nums > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $product_music_file = $row['product_music_file'];
                    if (file_exists("../" . $product_music_file)) {
                        @unlink("../" . $product_music_file);
                    }
                }
            }

            $product_music_file = 'product_music_file';

            $dir_save_product_music_foler = "music_file/" . $category_en_title_topic . "/" . $category_en_title ."/";

            $dir_save_product_music_file2 = handing_file_img($product_music_file, $dir_save_product_music_foler);

            $sql = "UPDATE `tbl_product_product`
                SET `product_music_file` = '{$dir_save_product_music_file2}' 
                WHERE `id` = '{$id_product}'";
            if ($conn->query($sql)) {
                $check --;
            } else {
                returnError("Cập nhật file mp3 không thành công!");
            }
        }
        
        if ($check > 0) {
            returnError("Cập nhật sản phẩm không thành công!");
        } else {
            returnSuccess("Cập nhật sản phẩm thành công!");
        }
        
        break;
    
    case 'delete_product':
        
        $id_product = '';
        if (isset($_REQUEST['id_product']) && ! empty($_REQUEST['id_product'])) {
            $id_product = $_REQUEST['id_product'];
        } else {
            returnError("Nhập id_product!");
        }
        
        $sql_check_notification_exists = "SELECT * FROM tbl_product_product WHERE id = '" . $id_product . "'";
        
        $result_check = mysqli_query($conn, $sql_check_notification_exists);
        $num_result_check = mysqli_num_rows($result_check);
        
        if ($num_result_check > 0) {
            
            while ($rowItem = $result_check->fetch_assoc()) {
                $product_img = $rowItem['product_img'];
                if (file_exists('../' . $product_img)) {
                    @unlink('../' . $product_img);
                }
                $product_music_file = $rowItem['product_music_file'];
                if (file_exists('../' . $product_music_file)) {
                    @unlink('../' . $product_music_file);
                }
            }
            
            $sql_delete_product = "
                            DELETE FROM tbl_product_product
                            WHERE  id = '" . $id_product . "'
                          ";
            if ($conn->query($sql_delete_product)) {
                returnSuccess("Xóa sản phẩm thành công!");
            } else {
                returnError("Xóa sản phẩm không thành công!");
            }
        } else {
            returnError("Không tìm thấy sản phẩm !");
        }
        
        break;
    
    default:
        returnError("type_manager is not accept!");
        break;
}