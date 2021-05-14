<?php
// include_once 'basic_auth.php';
include_once "../lib/database.php";
include_once "../lib/connect.php";
include_once "../lib/reuse_function.php";
include_once "../lib/validation.php";

// include_once "../vendor/autoload.php";

// include_once "../lib/jwt/php-jwt-master/src/JWT.php";

// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header("Access-Control-Allow-Methods: GET");
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

// check if data recived is from raw - if so, assign it to $_REQUEST
if (!isset($_REQUEST['detect'])) {
    // get raw json data
    $_REQUEST = json_decode(file_get_contents('php://input'), true);
    if (!isset($_REQUEST['detect'])) {
        echo json_encode(array(
            'message' => 'detect parameter not found !'
        ));
        exit();
    }
}
// handle detect value
$detect = $_REQUEST['detect'];

switch ($detect) {

        /*admin board*/
    case 'product_product':{
        include_once 'admin_board/product_product.php';
        break;
    }
    case 'category_product':{
        include_once 'admin_board/category_product.php';
        break;
    }
    case 'notify_manager': {
            include_once 'admin_board/notify_manager.php';
            break;
        }

    case 'account_manager': {
            include_once 'admin_board/account_manager.php';
            break;
        }
    case 'account_type_manager': {
            include_once 'admin_board/account_type_manager.php';
            break;
        }
    case 'app_deploy_manager': {
            include_once 'admin_board/app_deploy_manager.php';
            break;
        }
    case 'customer_manager': {
            include_once 'admin_board/customer_manager.php';
            break;
        }
    
    case 'force_signout': {
            include_once 'admin_board/force_signout.php';
            break;
        }
        /*employee board*/
   

        /*customer board*/
    case 'check_product_favourite':{
        include_once 'customer_board/check_product_favourite.php';
        break; 
    }
    case 'favourite_music':{
        include_once 'customer_board/favourite_music.php';
        break; 
    }
    
    case 'check_customer_disable': {
            include_once 'customer_board/check_customer_disable.php';
            break;
         }
  
    
         
    case 'forgot_password': {
            include_once 'customer_board/forgot_password.php';
            break;
        }
    
  
    case 'check_phone_isset': {
            include_once 'customer_board/check_phone_isset.php';
            break;
        }
    
    case 'register': {
            include_once 'customer_board/register.php';
            break;
        }
     
        /*viewlist board*/
    
    case 'list_favourite':{
            include_once 'viewlist_board/list_favourite.php';
            break;
    }
    case 'list_category_by_topic':{
        include_once 'viewlist_board/list_category_by_topic.php';
            break;
    }
    case 'list_topic':{
        include_once 'viewlist_board/list_topic.php';
            break;
    }
    case 'list_product':{
        include_once 'viewlist_board/list_product.php';
            break;
    }
    case 'list_category':{
        include_once 'viewlist_board/list_category.php';
            break;
        }
    case 'check_customer_error': {
            include_once 'viewlist_board/check_customer_error.php';
            break;
        }

    case 'get_notify': {
            include_once 'viewlist_board/get_notify.php';
            break;
        }
    case 'check_sign_out': {
            include_once 'viewlist_board/check_sign_out.php';
            break;
        }
   
    case 'login': {
            include_once 'viewlist_board/login.php';
            break;
        }
    case 'change_pass': {
            include_once 'viewlist_board/change_pass.php';
            break;
        }

    case 'list_customer': {
            include_once 'viewlist_board/list_customer.php';
            break;
        }
    case 'list_customer_detail': {
            include_once 'viewlist_board/list_customer_detail.php';
            break;
        }


    default: {
            echo json_encode(array(
                'success' => 'false',
                'massage' => 'detect has been failed'
            ));
        }
}
