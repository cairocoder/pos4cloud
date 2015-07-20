<!-- // validate file type -->
<?php
require_once("_inc/dbClass.php");

$row_arr = array();
$col_arr = array();

if(!empty($_FILES['items']['name'])){
        //CHECK FILE PROPERTIES
        $fileinfo = pathinfo($_FILES["items"]["name"]);
        $filetype = $_FILES["items"]["type"];
        $remark = NULL;
        //Validate File Type
        if(strtolower(trim($fileinfo["extension"])) != "csv"){
            echo "<script>window.location.href='itemsUpload.php?err=2';</script>";
            exit;
        }else{
            $file_path = $_FILES["items"]["tmp_name"];
        }
        //Validate File Type Ends
        if(($handle = fopen($file_path, 'r')) !== false){
            $header = fgetcsv($handle);
            //get sheet header length to check number of columns in sheet
            $headerLength = count($header);
            while(($data = fgetcsv($handle)) !== false){
                //$data[0] map to the first column of our csv
                $values = '';
                for($i = 0; $i < count($header); $i++){
                    //convert data into two dimention array
                    array_push($row_arr, $data[$i]);
                }
                //push element to columns array
                array_push($col_arr, $row_arr);
                $row_arr = array();
            }
            //array to hold columns array to check template structure
            $hdr = array('item id','item name','item description','season','gender',
                         'international code','color1','color2','color3','color4',
                         'color5','departments','vendors','attributes','msrp','rtp',
                         'item cost','sub departments');
            //check  sheet header length
            if(count($hdr) != $headerLength){
                echo '<script>window.location.href="itemsUpload.php?err=3";</script>';
            }else{
                //check if arrangment of columns and their names is right
                $checkHeader = $header;
                for($u=0; $u < $headerLength; $u++){
                    $checkHeader[$u] = strtolower($checkHeader[$u]);
                    if($checkHeader[$u] != $hdr[$u]){
                        echo '<script>window.location.href="itemsUpload.php?err=4";</script>';
                    }
                }
            }

            $limit = NULL;
            $err   = 0;
            $rearrange_col = $col_arr;
            $tmp   = array();
            $outer = array();
            $inner = array();
            $cond  = count($rearrange_col[0]);
            for($i = 0; $i < $cond; $i++){
                for($x = 0; $x < count($rearrange_col); $x++){
                    array_push($inner, $rearrange_col[$x][0]);
                    array_shift($rearrange_col[$x]);
                }
                array_push($outer, $inner);
                $inner = array();
            }

            for($i = 0; $i < 1; $i++){
                if(count(array_unique($outer[$i])) < count($outer[$i])){
                    $limit = $header[$i];
                    $err   = 5;
                }  
            }

            //check if there is empty cell in sheet
            if($limit == NULL){
                for($i = 0; $i < count($col_arr); $i++){
                    for($x = 0; $x < count($col_arr[$i]); $x++){
                        if($col_arr[$i][$x] == '' || $col_arr[$i][$x] == NULL || empty($col_arr[$i][$x])){
                            $limit = $header[$x];
                            $err   = 6;
                        }
                    }
                }
            }

            //check if the item id in the database 
            if($limit == NULL){
                for($i = 0; $i < count($col_arr); $i++){
                    $query = $db->query("SELECT item_name FROM items WHERE item_id = '".$col_arr[$i][0]."'");
                    $getItems = $db->fetch_array($query);
                    if(!empty($getItems)){
                        $limit  = $header[0];
                        $err    = 7;
                        $errVal = $col_arr[$i][0];
                        break;
                    }
                }
            }

            //check if used data are valid
            if($limit == NULL){
                for($i = 0; $i < count($col_arr); $i++){
                    for($x = 2; $x < 14; $x++){
                        if($x == 2){
                            $table = 'items_desc';
                            $colmn = 'desc_id';
                            $colmn2= 'desc';
                        }elseif($x == 3){
                            $table = 'items_season';
                            $colmn = 'season_id';
                            $colmn2= 'desc';
                        }elseif($x == 4){
                            $table = 'items_gender';
                            $colmn = 'gender_id';
                            $colmn2= 'desc';
                        }elseif($x == 5){
                            $table = 'items_intl_code';
                            $colmn = 'intl_code_id';
                            $colmn2= 'desc';
                        }elseif($x == 6 || $x == 7 || $x == 8 || $x == 9 || $x == 10){
                            $table = 'items_color';
                            $colmn = 'color_id';
                            $colmn2= 'desc';
                        }elseif($x == 11){
                            $table = 'items_dept';
                            $colmn = 'dept_id';
                            $colmn2= 'desc';
                        }elseif($x == 12){
                            $table = 'items_vendor';
                            $colmn = 'vend_id';
                            $colmn2= 'long_desc';
                        }elseif($x == 13){
                            $table = 'items_attr';
                            $colmn = 'attr_id';
                            $colmn2= 'desc';
                        }
                        $col_arr[$i][$x] = mysql_escape_string($col_arr[$i][$x]);
                        $query = $db->query("SELECT `".$colmn."` FROM ".$table." WHERE `".$colmn2."` = '".$col_arr[$i][$x]."'");
                        $getDesc = $db->fetch_array($query);
                        if(!empty($getDesc)){
                            $col_arr[$i][$x] = $getDesc[$colmn];
                        }else{
                            $limit = $header[$x];
                            $err   = 8;
                            $errVal = $col_arr[$i][$x];
                            // echo "SELECT `".$colmn."` FROM ".$table." WHERE `".$colmn2."` = '".$col_arr[$i][$x]."'";
                            // die();
                            break;
                        }
                    }
                    if($limit != NULL){
                        break;
                    }
                }
            }

            if($limit == NULL){
                for($i = 0; $i < count($col_arr); $i++){
                    $col_arr[$i][17] = mysql_escape_string($col_arr[$i][17]);
                    $query = $db->query("SELECT `sub_dept_id` FROM items_sub_dept WHERE `desc` = '".$col_arr[$i][17]."'");
                    $getSub = $db->fetch_array($query);
                    if(!empty($getSub)){
                        $col_arr[$i][17] = $getSub['sub_dept_id'];
                    }else{
                        $limit  = $header[17];
                        $err    = 8;
                        $errVal = $col_arr[$i][17];
                        break;
                    }
                }
            }

            if($limit == NULL){
                for($i = 0; $i < count($col_arr); $i++){
                    array_push($col_arr[$i], $col_arr[$i][0]);
                }
            }

            if($limit == NULL){
                for($i = 0; $i < count($col_arr); $i++){
                    for($x = 0; $x < count($col_arr[$i]); $x++){
                        $col_arr[$i][$x] = "'".$col_arr[$i][$x]."'";
                    }
                }
            }
            if($limit == NULL){
                //prepare insert query
                $q    = "INSERT INTO items (`item_id`,`item_name`,`desc_id`,`season_id`,`gender_id`,
                        `intl_code_id`,`color1_id`,`color2_id`,`color3_id`,`color4_id`,
                        `color5_id`,`dept_id`,`vend_id`,`attr_id`,`msrp`,`rtp`,
                        `item_cost`,`sub_dept_id`,`upc`) values ";
                for($i = 0; $i < count($col_arr); $i++){
                    $dif = count($col_arr) - $i; 
                    $values = implode(",", $col_arr[$i]);
                    $q .= "(".$values.")";
                    if($dif > 1){
                        $q .= ",";
                    }
                }

                $add = $db->query($q);
                if(!$add){
                    echo '<script>window.location.href="itemsUpload.php?err=9&lmt='.$limit.'";</script>';
                }else{
                    echo '<script>window.location.href="itemsUpload.php?err=10&lmt='.$limit.'";</script>';
                }
            }else{
                echo '<script>window.location.href="itemsUpload.php?err='.$err.'&lmt='.$limit.'&errVal='.$errVal.'";</script>';
            }
        }
        fclose($handle);
}else{
    echo '<script>window.location.href="itemsUpload.php?err=1";</script>';
}