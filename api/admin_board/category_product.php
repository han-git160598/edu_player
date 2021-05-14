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
            if(is_username($title_en))
            {
                $title_en = $_REQUEST['category_en_title'];
            }else{
                returnError("Nhập tên danh mục không đúng định dạng (không được nhập dấu và khoảng trắng)!");
            }
        } else {
            returnError("Nhập tên danh mục (tiếng anh)!");
        }
        $category_parent='0';
        if (isset($_REQUEST['category_parent']) &&  $_REQUEST['category_parent'] !='') {
            $category_parent = $_REQUEST['category_parent'];
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

       
///////////////////////
    $sql_title_category = "SELECT * FROM tbl_product_category 
    WHERE category_en_title = '" . $title_en . "'  ";
      $result_title = mysqli_query($conn, $sql_title_category);
      $nums_titel = mysqli_num_rows($result_title);
      if ($nums_titel > 0) {
        returnError("Tên category_en_title đã tồn tại");
      }
     // lây floder topic
    if($category_parent !=0)
    {
        $sql_category_parent = "SELECT * FROM tbl_product_category 
        WHERE id = '" . $category_parent . "'  ";
    
        $result_check = mysqli_query($conn, $sql_category_parent);
        $num_result_check = mysqli_num_rows($result_check);
    
        if ($num_result_check > 0) {
            while ($rowItem = mysqli_fetch_assoc($result_check)) {
                $floder_category = $rowItem['category_en_title'];
            }
        }
    }
    



////////////////////////



        $sql_create_category = "
            INSERT INTO tbl_product_category SET
            category_vn_title            = '" . $title_vn . "',
            category_en_title            = '" . $title_en . "',
            category_parent            = '" . $category_parent . "',
            category_img     = '" . $img_photo_category . "'
        ";

        
        if($category_parent == 0)
        {
            if ($conn->query($sql_create_category)) {

                $path = "../music_file/" . $title_en . "";
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                    returnSuccess("Tạo danh mục thành công!");
                }else{
                    returnError("Tên thư mục đã tồn tại");
                }
            } else {
                returnError("Tạo danh mục không thành công!");
            }
        }else{
            if ($conn->query($sql_create_category)) {
                $path = "../music_file/" . $floder_category . "/" . $title_en . "";
               
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                    returnSuccess("Tạo thư mục thành công");
                }else{
                    returnError("Tên thư mục đã tồn tại");
                }
                
                returnSuccess("Tạo danh mục thành công!");
            } else {
                returnError("Tạo danh mục không thành công!");
            }

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
        
        if (isset($_REQUEST['category_hot']) && ! empty($_REQUEST['category_hot'])) {
            $check ++;
            $query = "UPDATE tbl_product_category SET ";
            $query .= " category_hot  = '" . $_REQUEST['category_hot'] . "' ";
            $query .= " WHERE id = '" . $id_category . "'";
            // Create post
            if ($conn->query($query)) {
                $check --;
            } else {
                returnError("Cập nhật danh mục nổi bật");
            }
        }

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
            // định dạng tên english, cũng là tên floder
            $title_en = $_REQUEST['category_en_title'];
            if(is_username($title_en))
            {
                $title_en = $_REQUEST['category_en_title'];
            }else{
                returnError("Nhập tên danh mục không đúng định dạng (không được nhập dấu và khoảng trắng)!");
            }


            // check tên en
            $title_en = $_REQUEST['category_en_title'];

            $sql_title_category = "SELECT * FROM tbl_product_category 
            WHERE id != '$id_category' AND category_en_title = '" . $title_en . "'  ";
            $result_title = mysqli_query($conn, $sql_title_category);
            $nums_titel = mysqli_num_rows($result_title);
            if ($nums_titel > 0) {
                returnError("Tên category_en_title đã tồn tại");
            }

            // lấy tên floder cũ và mới xác định topic hay sub_category

            $sql_floder_topic = "SELECT * FROM tbl_product_category 
            WHERE id = '" . $id_category . "'  ";
        
            $result_check_floder = mysqli_query($conn, $sql_floder_topic);
            $rowItem_floder = $result_check_floder->fetch_assoc();
            $floder_category_old = $rowItem_floder['category_en_title'];
            $category_parent_floder = $rowItem_floder['category_parent'];
    

            $check ++;
            $query = "UPDATE tbl_product_category SET ";
            $query .= " category_en_title  = '" . $_REQUEST['category_en_title'] . "' ";
            $query .= " WHERE id = '" . $id_category . "'";
            
            if ($conn->query($query)) {
                if($category_parent_floder == 0)
                {
                    // floder 
                    $old_name = "../music_file/" . $floder_category_old . "";
                    $new_name = "../music_file/" . $title_en . "";
                    if(file_exists($new_name))
                    { 
                       // echo "Error While Renaming $old_name" ;
                    }
                    else
                    {
                    if(rename( $old_name, $new_name))
                        { 
                            //lấy id_category topic

                            $sql_category ="SELECT * FROM tbl_product_category
                                            WHERE category_parent = '$id_category' 
                            ";

                            $result_category = mysqli_query($conn, $sql_category);
                            $num_result_category = mysqli_num_rows($result_category);
                            
                            if ($num_result_category > 0) {
                                while ($rowItem_category = mysqli_fetch_assoc($result_category)) 
                                {
                                    $sql_product_by_category = "SELECT * FROM tbl_product_product
                                                                WHERE id_category ='".$rowItem_category['id']."'";

                                     $result_product_by_category = mysqli_query($conn, $sql_product_by_category);
                                     $num_product_by_category = mysqli_num_rows($result_product_by_category);
                                     if ($num_product_by_category > 0) {
                                        while ($rowItem_product_by_category = mysqli_fetch_assoc($result_product_by_category)) 
                                        {
                                           $product_music_file =$rowItem_product_by_category['product_music_file'];
                                           $id_product = $rowItem_product_by_category['id'];
                                           $array = explode("/",$product_music_file);
                                            $array[1] = $title_en;
                                            $new_link =  implode("/",$array);

                                            $sql_update_link = "UPDATE tbl_product_product SET ";
                                            $sql_update_link .=" product_music_file  = '" . $new_link . "' ";
                                            $sql_update_link .= " WHERE id = '" . $id_product . "'";
                                           
                                            if ($conn->query($sql_update_link)) {
                                                $check--;
                                            }else{
                                                returnError("thay đổi danh mục không thành công");
                                            }
                                        }

                                     }
                                }
                            }
 
                        }
                        else
                        {
                            returnError("Cập nhật tên title_en thành công nhưng thất bại ở thay đổi tên thư mục  ") ;
                        }
                    }
                // floder 
                }else{

                    $sql_floder_topic_1 = "SELECT * FROM tbl_product_category 
                    WHERE id = '" . $category_parent_floder . "'  ";
                
                    $result_check_floder_topic = mysqli_query($conn, $sql_floder_topic_1);
                    $rowItem_floder_topic = $result_check_floder_topic->fetch_assoc();
                    $floder_topic_old_1 = $rowItem_floder_topic['category_en_title'];
                   // $category_parent_floder_1 = $rowItem_floder_1['category_parent'];

                    // floder 
                    $old_name = "../music_file/" . $floder_topic_old_1 . "/" . $floder_category_old . "";
                    $new_name =  "../music_file/" . $floder_topic_old_1 . "/" . $title_en . "";
                    if(file_exists($new_name))
                    { 
                     //   echo "Error While Renaming $old_name" ;
                    }
                    else
                    {
                    if(rename( $old_name, $new_name))
                        { 

                            //lấy id_category topic

                            $sql_category = "SELECT * FROM tbl_product_category
                                            WHERE id = '$id_category' 
                            ";

                            $result_category = mysqli_query($conn, $sql_category);
                            $num_result_category = mysqli_num_rows($result_category);
                            
                            if ($num_result_category > 0) {
                                while ($rowItem_category = mysqli_fetch_assoc($result_category)) 
                                {
                                    $sql_product_by_category = "SELECT * FROM tbl_product_product
                                                                WHERE id_category ='".$rowItem_category['id']."'";

                                     $result_product_by_category = mysqli_query($conn, $sql_product_by_category);
                                     $num_product_by_category = mysqli_num_rows($result_product_by_category);
                                     if ($num_product_by_category > 0) {
                                        while ($rowItem_product_by_category = mysqli_fetch_assoc($result_product_by_category)) 
                                        {
                                           $product_music_file =$rowItem_product_by_category['product_music_file'];
                                           $id_product = $rowItem_product_by_category['id'];
                                           $array = explode("/",$product_music_file);
                                            $array[2] = $title_en;
                                            $new_link =  implode("/",$array);

                                            $sql_update_link = "UPDATE tbl_product_product SET ";
                                            $sql_update_link .=" product_music_file  = '" . $new_link . "' ";
                                            $sql_update_link .= " WHERE id = '" . $id_product . "'";
                                           
                                            if ($conn->query($sql_update_link)) {
                                                $check--;
                                            }else{
                                                returnError("thay đổi danh mục không thành công");
                                            }
                                        }

                                     }
                                }
                            }

                        }
                        else
                        {
                            returnError("Cập nhật tên title_en thành công nhưng thất bại ở thay đổi tên thư mục  ") ;
                        }
                    }
                // floder 


                }

               

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

        // luận lý ràng buộc
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

         

        $sql_check_category_exists = "SELECT * FROM tbl_product_category WHERE id = '" . $id_category . "'";

        $result_check = mysqli_query($conn, $sql_check_category_exists);
        $num_result_check = mysqli_num_rows($result_check);
        
        if ($num_result_check > 0) {
            
            while ($rowItem = $result_check->fetch_assoc()) {
                $image_category = $rowItem['category_img'];
                $category_parent = $rowItem['category_parent'];
                $category_en_title = $rowItem['category_en_title'];
                
                
                if (file_exists('../' . $image_category)) {
                    @unlink('../' . $image_category);
                }
            }
            $sql_delete_category = "
                            DELETE FROM tbl_product_category
                            WHERE  id = '" . $id_category . "'
                          ";
            if ($conn->query($sql_delete_category)) {

                if($category_parent== 0)
                {
                    $dirname = "../music_file/" . $category_en_title . " ";
                    if(rmdir($dirname))
                    {
                        returnSuccess("Xóa danh mục thành công!");
                    }
                    else
                    {
                        returnError("$dirname xóa thất bại, nhưng xóa DB thành công"); 
                    }
                }else{
                    $sql_topic = "SELECT * FROM tbl_product_category 
                                  WHERE id = " . $category_parent . ";
                    ";
                    $result_topic = mysqli_query($conn, $sql_topic);
                    $rowItem_floder_topic = $result_topic->fetch_assoc();
                    $floder_topic = $rowItem_floder_topic['category_en_title'];
                   
                    $dirname = "../music_file/" . $floder_topic . "/" . $category_en_title . "";
                    if(rmdir($dirname))
                    {
                        returnSuccess("Xóa danh mục thành công!");
                    }
                    else
                    {
                        returnError("$dirname xóa thất bại, nhưng xóa DB thành công"); 
                    }
                }

                    
              
                
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