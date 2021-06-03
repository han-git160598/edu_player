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
      
        if (isset($_REQUEST['product_name_en']) && ! empty($_REQUEST['product_name_en'])) {
            $product_name_en = $_REQUEST['product_name_en'];
        } else {
            returnError("Nhập product_name_en");
        }
       
        if (isset($_REQUEST['product_name_vn']) && ! empty($_REQUEST['product_name_vn'])) {
            $product_name_vn  = $_REQUEST['product_name_vn'];
        } else {
            returnError("Nhập product_name_vn ");
        }
        if (isset($_REQUEST['product_spelling']) && ! empty($_REQUEST['product_spelling'])) {
            $product_spelling  = $_REQUEST['product_spelling'];
        } else {
            returnError("Nhập product_spelling ");
        }
        if (isset($_REQUEST['product_ex_en']) && ! empty($_REQUEST['product_ex_en'])) {
            $product_ex_en  = $_REQUEST['product_ex_en'];
        } else {
            returnError("Nhập product_ex_en ");
        }
        if (isset($_REQUEST['product_ex_vn']) && ! empty($_REQUEST['product_ex_vn'])) {
            $product_ex_vn  = $_REQUEST['product_ex_vn'];
        } else {
            returnError("Nhập product_ex_vn ");
        }
        if (isset($_REQUEST['id_category']) && ! empty($_REQUEST['id_category'])) {
            $id_category  = $_REQUEST['id_category'];
        } else {
            returnError("Nhập id_category ");
        }
    
        $sql_product = "SELECT id FROM tbl_product_product   
        WHERE id = (SELECT MAX(id) FROM tbl_product_product)
        ";
// thêm file âm thanh english
        if (isset($_FILES['product_en_file']) && ! empty($_FILES['product_en_file'])) { 
            
            $result = mysqli_query($conn, $sql_product);
            $nums = mysqli_num_rows($result);
            if ($nums > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $id_product = $row['id'] + 1;
                }
            }else{
                $id_product= '1';
            }

            $product_en_file = 'product_en_file';
            $dir_save_product_music_foler = "music_file/product_category/";
            $dir_save_product_en_file = handing_file_mp31($product_en_file, $dir_save_product_music_foler,$id_product,$id_category);
        } else {
            returnError("Nhập product_en_file");
        }
// thêm file âm thanh vietnam
        if (isset($_FILES['product_vn_file']) && ! empty($_FILES['product_vn_file'])) { 
            
            $result = mysqli_query($conn, $sql_product);
            $nums = mysqli_num_rows($result);
            if ($nums > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $id_product = $row['id'] + 1;
                }
            }else{
                $id_product= '1';
            }

            $product_vn_file = 'product_vn_file';
            $dir_save_product_music_foler = "music_file/product_category/";
            $dir_save_product_vn_file = handing_file_mp31($product_vn_file, $dir_save_product_music_foler,$id_product,$id_category);
        } else {
            returnError("Nhập product_vn_file");
        }
// thêm âm nhạc ví dụ english
        if (isset($_FILES['product_ex_en_file']) && ! empty($_FILES['product_ex_en_file'])) { 
            
            $result = mysqli_query($conn, $sql_product);
            $nums = mysqli_num_rows($result);
            if ($nums > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $id_product = $row['id'] + 1;
                }
            }else{
                $id_product= '1';
            }

            $product_ex_en_file = 'product_ex_en_file';
            $dir_save_product_music_foler = "music_file/product_category/";
            $dir_save_product_ex_en_file = handing_file_mp31($product_ex_en_file, $dir_save_product_music_foler,$id_product,$id_category);
        } else {
            returnError("Nhập product_ex_en_file");
        }
// thêm âm nhạc ví dụ tieng viet
        if (isset($_FILES['product_ex_vn_file']) && ! empty($_FILES['product_ex_vn_file'])) { 
            
            $result = mysqli_query($conn, $sql_product);
            $nums = mysqli_num_rows($result);
            if ($nums > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $id_product = $row['id'] + 1;
                }
            }else{
                $id_product= '1';
            }

            $product_ex_vn_file = 'product_ex_vn_file';
            $dir_save_product_music_foler = "music_file/product_category/";
            $dir_save_product_ex_vn_file = handing_file_mp31($product_ex_vn_file,$dir_save_product_music_foler,$id_product,$id_category);
        } else {
            returnError("Nhập product_ex_vn_file");
        }

// thêm hình ảnh
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
                                  id_category = '$id_category'
                                , product_name_vn = '$product_name_vn'
                                , product_name_en = '$product_name_en'
                                , product_spelling = '$product_spelling'
                                , product_img = '$dir_save_product_img2'
                                , product_en_file = '$dir_save_product_en_file'
                                , product_vn_file = '$dir_save_product_vn_file'
                                , product_ex_en_file = '$dir_save_product_ex_en_file'
                                , product_ex_vn_file = '$dir_save_product_ex_vn_file'
                                , product_ex_en = '$product_ex_en'
                                , product_ex_vn = '$product_ex_vn'
                                ";

        if ($conn->query($sql_create_product)) {
            returnSuccess("Tạo sản phẩm thành công!");
        } else {
            returnError("Tạo sản phẩm không thành công!");
        }
        
        break;
    
    case 'update_product':

        $id_product = '';
        if (isset($_REQUEST['id_product']) && ! empty($_REQUEST['id_product'])) {
            $id_product = $_REQUEST['id_product'];
        } else {
            returnError("Nhập id_product!");
        }
        
        $check = 0;
        
        if (isset($_REQUEST['product_name_vn']) && ! empty($_REQUEST['product_name_vn'])) {
            $check ++;
            $query = "UPDATE tbl_product_product SET ";
            $query .= " product_name_vn  = '" . $_REQUEST['product_name_vn'] . "' ";
            $query .= " WHERE id = '" . $id_product . "'";
            // Create post
            if ($conn->query($query)) {
                $check --;
            } else {
                returnError("Cập nhật sản phẩm không thành công!");
            }
        }

        if (isset($_REQUEST['product_name_en']) && ! empty($_REQUEST['product_name_en'])) {
            $check ++;
            $query = "UPDATE tbl_product_product SET ";
            $query .= " product_name_en  = '" . $_REQUEST['product_name_en'] . "' ";
            $query .= " WHERE id = '" . $id_product . "'";
            // Create post
            if ($conn->query($query)) {
                $check --;
            } else {
                returnError("Cập nhật sản phẩm không thành công!");
            }
        }

        if (isset($_REQUEST['product_ex_en']) && ! empty($_REQUEST['product_ex_en'])) {
            $check ++;
            $query = "UPDATE tbl_product_product SET ";
            $query .= " product_ex_en  = '" . $_REQUEST['product_ex_en'] . "' ";
            $query .= " WHERE id = '" . $id_product . "'";
            // Create post
            if ($conn->query($query)) {
                $check --;
            } else {
                returnError("Cập nhật sản phẩm không thành công!");
            }
        }

        if (isset($_REQUEST['product_ex_vn']) && ! empty($_REQUEST['product_ex_vn'])) {
            $check ++;
            $query = "UPDATE tbl_product_product SET ";
            $query .= " product_ex_vn  = '" . $_REQUEST['product_ex_vn'] . "' ";
            $query .= " WHERE id = '" . $id_product . "'";
            // Create post
            if ($conn->query($query)) {
                $check --;
            } else {
                returnError("Cập nhật sản phẩm không thành công!");
            }
        }

        if (isset($_REQUEST['product_spelling']) && ! empty($_REQUEST['product_spelling'])) {
            $check ++;
            $query = "UPDATE tbl_product_product SET ";
            $query .= " product_spelling  = '" . $_REQUEST['product_spelling'] . "' ";
            $query .= " WHERE id = '" . $id_product . "'";
            // Create post
            if ($conn->query($query)) {
                $check --;
            } else {
                returnError("Cập nhật sản phẩm không thành công!");
            }
        }
    
        if (isset($_FILES['product_img'] )) {
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
            $product_img = 'product_img';
            $dir_save_product_img = "images/product_category/";
            $dir_save_product_img2 = handing_file_img($product_img, $dir_save_product_img);
            $sql = "UPDATE `tbl_product_product`
                SET `product_img` = '{$dir_save_product_img2}' 
                WHERE `id` = '{$id_product}'";
            if ($conn->query($sql)) {
                $check --;
            } else {
                returnError("Cập nhật img không thành công!");
            }
        }
// Truy vấn lấy ra old_link
        $sql_link_product = "SELECT * FROM tbl_product_product
                             WHERE id = '$id_product'
        ";
            $result = mysqli_query($conn, $sql_link_product);
            $nums = mysqli_num_rows($result);
            if ($nums > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $product_en_file = $row['product_en_file'];
                    $product_vn_file = $row['product_vn_file'];
                    $product_ex_en_file = $row['product_ex_en_file'];
                    $product_ex_vn_file = $row['product_ex_vn_file'];
                }
            }else{
                returnError("Không tìm thấy id");
            }
            $array = explode("/",$product_en_file);
            $name_file_mp3 = explode("_",$array[2]);
            $id_category =$name_file_mp3[0];
            $id_product_mp3 = $name_file_mp3[2];

//update file mp3 english
        if (isset($_FILES['product_en_file'])) {
            $check++;
            if (file_exists("../" . $product_en_file)) {
                @unlink("../" . $product_en_file);
            }
// lay lai ten cu de luu file theo STT  
            $product_music_file = 'product_en_file';
            $dir_save_product_music_foler = "music_file/product_category/";
            $dir_save_product_music_file2 = handing_file_mp31($product_music_file, $dir_save_product_music_foler,$id_product_mp3,$id_category);

            $sql = "UPDATE `tbl_product_product`
                SET `product_en_file` = '{$dir_save_product_music_file2}' 
                WHERE `id` = '{$id_product}'";
            
            if ($conn->query($sql)) {
                $check --;
            } else {
                returnError("Cập nhật file mp3 không thành công!");
            }
        }


//update file mp3 vietnam
        if (isset($_FILES['product_vn_file'])) {
            $check++;
            if (file_exists("../" . $product_vn_file)) {
                @unlink("../" . $product_vn_file);
            }
// lay lai ten cu de luu file theo STT
            $product_music_file = 'product_vn_file';
            $dir_save_product_music_foler = "music_file/product_category/";
            $dir_save_product_music_file2 = handing_file_mp31($product_music_file, $dir_save_product_music_foler,$id_product_mp3,$id_category);

            $sql = "UPDATE `tbl_product_product`
                SET `product_vn_file` = '{$dir_save_product_music_file2}' 
                WHERE `id` = '{$id_product}'";
            
            if ($conn->query($sql)) {
                $check --;
            } else {
                returnError("Cập nhật file mp3 không thành công!");
            }
        }

//update file mp3 vi du vietnam
        if (isset($_FILES['product_ex_vn_file'])) {
            $check++;
            if (file_exists("../" . $product_ex_vn_file)) {
                @unlink("../" . $product_ex_vn_file);
            }
// lay lai ten cu de luu file theo STT
            $product_music_file = 'product_ex_vn_file';
            $dir_save_product_music_foler = "music_file/product_category/";
            $dir_save_product_music_file2 = handing_file_mp31($product_music_file, $dir_save_product_music_foler,$id_product_mp3,$id_category);

            $sql = "UPDATE `tbl_product_product`
                SET `product_ex_vn_file` = '{$dir_save_product_music_file2}' 
                WHERE `id` = '{$id_product}'";
            
            if ($conn->query($sql)) {
                $check --;
            } else {
                returnError("Cập nhật file mp3 không thành công!");
            }
        }

//update file mp3 vi du english
        if (isset($_FILES['product_ex_en_file'])) {
            $check++;
            if (file_exists("../" . $product_ex_en_file)) {
                @unlink("../" . $product_ex_en_file);
            }
// lay lai ten cu de luu file theo STT
            $product_music_file = 'product_ex_en_file';
            $dir_save_product_music_foler = "music_file/product_category/";
            $dir_save_product_music_file2 = handing_file_mp31($product_music_file, $dir_save_product_music_foler,$id_product_mp3,$id_category);

            $sql = "UPDATE `tbl_product_product`
                SET `product_ex_en_file` = '{$dir_save_product_music_file2}' 
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
        
        $arr_id_product = array();
        if (isset($_REQUEST['id_product']) && ! empty($_REQUEST['id_product'])) {
            $id_product = $_REQUEST['id_product'];

        } else {
            returnError("Nhập id_product!");
        }
        $check =0;
        $arr_id_product = explode(",",$id_product);
        foreach ($arr_id_product as $value)
        { 
          
            $check ++;
            $sql_check_notification_exists = "SELECT * FROM tbl_product_product WHERE id = '" . $value . "'";
            $result_check = mysqli_query($conn, $sql_check_notification_exists);
            $num_result_check = mysqli_num_rows($result_check);
            
            if ($num_result_check > 0) {
                
                while ($rowItem = $result_check->fetch_assoc()) {
                    $product_img = $rowItem['product_img'];
                    if (file_exists('../' . $product_img)) {
                        @unlink('../' . $product_img);
                    }
                    $product_en_file = $rowItem['product_en_file'];
                    if (file_exists('../' . $product_en_file)) {
                        @unlink('../' . $product_en_file);
                    }
                    $product_vn_file = $rowItem['product_vn_file'];
                    if (file_exists('../' . $product_vn_file)) {
                        @unlink('../' . $product_vn_file);
                    }
                    $product_ex_en_file = $rowItem['product_ex_en_file'];
                    if (file_exists('../' . $product_ex_en_file)) {
                        @unlink('../' . $product_ex_en_file);
                    }
                    $product_ex_vn_file = $rowItem['product_ex_vn_file'];
                    if (file_exists('../' . $product_ex_vn_file)) {
                        @unlink('../' . $product_ex_vn_file);
                    }
                }
                $sql_delete_product = "
                                DELETE FROM tbl_product_product
                                WHERE  id = '" . $value . "'
                              ";
                if ($conn->query($sql_delete_product)) {
                   $check --;
                }
            } 
            else {
                returnError("Không tìm thấy sản phẩm !");
            }
        }
      
     
        if($check == 0 )
        {
            returnSuccess("Xóa sản phẩm thành công!");
        }else {
            returnError("Xóa sản phẩm không thành công!");
        }

        
        
        break;
    
    default:
        returnError("type_manager is not accept!");
        break;
}