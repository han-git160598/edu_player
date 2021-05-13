<?php
    $id_category = ''
    $id_product = ''
    if(isset($_REQUEST['id_category']) && !(empty($_REQUEST['id_category'])){
        echo 'cua dinh';
        exit;
    }
    else {
        echo 'cua khanh';
        exit;
    }
