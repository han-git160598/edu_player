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
    case 'list_category':
        include_once './viewlist_board/list_category.php';
        break;
    
    case 'create_category':

        
        $title = '';
        if (isset($_REQUEST['category_vn_title']) && ! empty($_REQUEST['category_vn_title'])) {
            $title_vn = $_REQUEST['category_vn_title'];
        } else {
            returnError("Nhập tên danh mục (tiếng việt)!");
        }
        
        $content = '';
        if (isset($_REQUEST['category_en_title']) && ! empty($_REQUEST['category_en_title'])) {
            $title_en = $_REQUEST['category_en_title'];
        } else {
            returnError("Nhập tên danh mục (tiếng anh)!");
        }

        
        $img_photo_category= '';
        if (isset($_FILES['image_category'])) {
            $img_photo_category = saveImage($_FILES['image_category'], 'images/product_category/');
            if ($img_photo_category == "error_size_img") {
                returnError("image_category > 5MB !");
            }
            
            if ($img_photo_category == "error_type_img") {
                returnError("image_category error type");
            }
        }else{
            returnError("Nhập image_category");
        }

        if (isset($_REQUEST['category_parent']) &&  $_REQUEST['category_parent'] !='') {
            $category_parent = $_REQUEST['category_parent'];
            
        } else {
            returnError("Nhập category_parent");
        }
        

        $sql_create_category = "
            INSERT INTO tbl_product_category SET
            category_vn_title            = '" . $title_vn . "',
            category_en_title            = '" . $title_en . "',
            category_parent            = '" . $category_parent . "',
            category_img     = '" . $img_photo_category . "'
        ";

        if ($conn->query($sql_create_category)) {

            $path = "../music_file/product_category/" . $title_en . "";
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
                returnSuccess("Tạo thư mục thành công");
            }else{
                returnError("lỗi rồi nè");
        }
            
            returnSuccess("Tạo danh mục thành công!");
        } else {
            returnError("Tạo danh mục không thành công!");
        }
        
        break;
    
    case 'update_category':
        
        $id_category = '';
        if (isset($_REQUEST['id_category']) && ! empty($_REQUEST['id_category'])) {
            $id_category = $_REQUEST['id_category'];
        } else {
            returnError("Nhập id_category!");
        }
        
        $check = 0;
        
        if (isset($_REQUEST['category_vn_title']) && ! empty($_REQUEST['category_vn_title'])) {
            $check ++;
            $query = "UPDATE tbl_product_category SET ";
            $query .= " category_vn_title  = '" . $_REQUEST['category_vn_title'] . "' ";
            $query .= " WHERE id = '" . $id_category . "'";
            // Create post
            if ($conn->query($query)) {
                $check --;
            } else {
                returnError("Cập nhật danh mục (tiếng việt) không thành công!");
            }
        }
        if (isset($_REQUEST['category_en_title']) && ! empty($_REQUEST['category_en_title'])) {
            $check ++;
            $query = "UPDATE tbl_product_category SET ";
            $query .= " category_en_title  = '" . $_REQUEST['category_en_title'] . "' ";
            $query .= " WHERE id = '" . $id_category . "'";
            // Create post
            if ($conn->query($query)) {
                $check --;
            } else {
                returnError("Cập nhật danh mục (tiếng anh) không thành công!");
            }
        }

        
        $img_photo_category = '';
        if (isset($_FILES['image_category'])) {
            $check++;
            $sql = "SELECT * FROM `tbl_product_category` WHERE `id` = '{$id_category}'";
            $result = mysqli_query($conn, $sql);
            $nums = mysqli_num_rows($result);
            if ($nums > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $category_img = $row['category_img'];
                    if (file_exists("../" . $category_img)) {
                        @unlink("../" . $category_img);
                    }
                }
            }
            $category_img = 'image_category';
            $dir_save_category_img = "images/product_category/";
            $dir_save_category_img2 = handing_file_img($category_img, $dir_save_category_img);
            $sql = "UPDATE `tbl_product_category`
                SET `category_img` = '{$dir_save_category_img2}' 
                WHERE `id` = '{$id_category}'";
            if ($conn->query($sql)) {
                $check --;
            } else {
                returnError("Cập nhật img không thành công!");
            }
        }
        
        if ($check > 0) {
            returnError("Cập nhật danh mục không thành công!");
        } else {
            returnSuccess("Cập nhật danh mục thành công!");
        }
        
        break;
    
    case 'delete_category':
        
        $id_category = '';
        if (isset($_REQUEST['id_category']) && ! empty($_REQUEST['id_category'])) {
            $id_category = $_REQUEST['id_category'];
        } else {
            returnError("Nhập id_category!");
        }

        $sql_check_product = "SELECT id FROM tbl_product_product 
                                WHERE id_category = '" . $id_category . "'";
        $result_product = db_qr($sql_check_product);
        if(db_nums($result_product)>0)
        {
            returnError("Vui lòng xóa sản phẩm hết mới xóa danh mục!");
        }

        $sql_check_category = "SELECT id FROM tbl_product_category 
                                WHERE category_parent = '" . $id_category . "'";
        $result_category = db_qr($sql_check_category);
        if(db_nums($result_category)>0)
        {
            returnError("Vui lòng xóa hết danh mục con rồi xóa danh mục cha!");
        }

         

        $sql_check_category_exists = "SELECT category_img FROM tbl_product_category WHERE id = '" . $id_category . "'";

       

        $result_check = mysqli_query($conn, $sql_check_category_exists);
        $num_result_check = mysqli_num_rows($result_check);
        
        if ($num_result_check > 0) {
            
            while ($rowItem = $result_check->fetch_assoc()) {
                $image_category = $rowItem['category_img'];
                
                if (file_exists('../' . $image_category)) {
                    @unlink('../' . $image_category);
                }
            }


            
            $sql_delete_category = "
                            DELETE FROM tbl_product_category
                            WHERE  id = '" . $id_category . "'
                          ";
            if ($conn->query($sql_delete_category)) {
                returnSuccess("Xóa danh mục thành công!");
            } else {
                returnError("Xóa danh mục không thành công!");
            }
        } else {
            returnError("Không tìm thấy danh mục !");
        }
        
        break;
    
    default:
        returnError("type_manager is not accept!");
        break;
}