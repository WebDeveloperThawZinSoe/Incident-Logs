<?php
    session_start();
    include "database.php";

    /* Success message */
function success_message($data,$location){
    $_SESSION["success"] = $data;
    header("location:$location");
}

/* Error message */
function error_message($data,$location){
    $_SESSION["error"] = $data;
    header("location:$location");
    
}
/* Image Filter */
function image_filter($image,$location){
    $name = $image["name"];
    $size = $image["size"];
    $error = $image["error"];
    $tmp_name = $image["tmp_name"];
    $type = $image["type"];
    $image_upload_location = "uploads/";
    global $unique_file_name ;
    $unique_file_name = rand(0,100) . "_" . $name;

    if($error == 0){
        if($size < 2000000){
            if($type == "image/png" || $type=="image/jpg" || $type =="image/jpeg" || $type == "image/gif"){
                move_uploaded_file($tmp_name , $image_upload_location . $unique_file_name);
                return $unique_file_name;
            }else{
                error_message("We only accept jpg png and gif",$location);
                die();
            }
        }else{
            error_message("File is too big",$location);
            die();
        }
    }else{
        error_message("File has error" , $location);
        die();
    }

}


    /* Create Category */
    if(isset($_POST["category_create"])){
       $category = htmlspecialchars($_POST["category"]);
       $sql = "INSERT INTO category(category,status) VALUES ('$category',1)";
       $result = mysqli_query($connect,$sql);
       if($result){
        success_message("Create Category Success",$_SERVER['HTTP_REFERER']);
       }else{
        error_message("Create Category Fail",$_SERVER['HTTP_REFERER']);
       }
    }


    /* Category Delete */
    if(isset($_POST["category_delete"])){
        $id = htmlspecialchars($_POST["id"]);
        $sql = "UPDATE category SET status='0' WHERE id=$id";
        $result = mysqli_query($connect,$sql);
        if($result){
         success_message("Delete Category Success",$_SERVER['HTTP_REFERER']);
        }else{
         error_message("Delete Category Fail",$_SERVER['HTTP_REFERER']);
        }
    }

    /* Sub Category Create */
    if(isset($_POST["sub_category_create"])){
       $category = htmlspecialchars($_POST["category"]);
       $subcategory = htmlspecialchars($_POST["subcategory"]);
       $sql = "INSERT INTO sub_category(cat_id,subcategory,status) VALUES ('$category','$subcategory','1')";
       $result = mysqli_query($connect,$sql);
       if($result){
        success_message("Create SubCategory Success",$_SERVER['HTTP_REFERER']);
       }else{
        error_message("Create SubCategory Fail",$_SERVER['HTTP_REFERER']);
       }
    }

    /* Sub Category Delete*/
    if(isset($_POST["sub_category_delete"])){
        $id = $_POST["id"];
        $sql = "UPDATE sub_category SET status='0' WHERE id=$id";
        $result = mysqli_query($connect,$sql);
        if($result){
         success_message("Delete SubCategory Success",$_SERVER['HTTP_REFERER']);
        }else{
         error_message("Delete SubCategory Fail",$_SERVER['HTTP_REFERER']);
        }
    }

    /* incident_create */
    if(isset($_POST['incident_create'])){
        $cat_id = htmlspecialchars($_POST['category']);
        $sub_id = htmlspecialchars($_POST['subcategory']);
        $incident = htmlspecialchars($_POST['incident']);
        $date = date("Y-m-d");
        $sql = "INSERT INTO incident(title,cat_id,sub_cat_id,create_at) VALUES ('$incident','$cat_id','$sub_id','$date')";
        $result = mysqli_query($connect,$sql);
        if($result){
            success_message("Create Incident Success",$_SERVER['HTTP_REFERER']);
           }else{
            error_message("Create Incident  Fail",$_SERVER['HTTP_REFERER']);
           }
    }

    /* incident_delete */
    if(isset($_POST["incident_delete"])){
        $id = htmlspecialchars($_POST["id"]);
        $sql = "DELETE FROM incident WHERE id='$id'";
        $result = mysqli_query($connect,$sql);
        if($result){
            success_message("Delete Incident Success",$_SERVER['HTTP_REFERER']);
           }else{
            error_message("Delete Incident  Fail",$_SERVER['HTTP_REFERER']);
           }
    }

    /* solution_create */
    if(isset($_POST['solution_create'])){

       
        $incident = htmlspecialchars($_POST['incident']);
        $solution = htmlspecialchars($_POST['solution']);
        $date = date("Y-m-d");
        $sql = "INSERT INTO solution(incident_id,answer,member_id,create_date) VALUES ($incident,'$solution',1,'$data')";
        $result = mysqli_query($connect, $sql);
        if($result){
            success_message("Create Solution Success",$_SERVER['HTTP_REFERER']);
           }else{
            error_message("Create Solution  Fail",$_SERVER['HTTP_REFERER']);
           }
    }


    /* create_log */
    if(isset($_POST['create_log'])){
       $cat_id = htmlspecialchars($_POST["cat_id"]);
       $sub_id = htmlspecialchars($_POST["sub_id"]);
       $inc_id = htmlspecialchars($_POST["inc_id"]);
       $answer_id = htmlspecialchars($_POST["answer_id"]);
       $location  = htmlspecialchars($_POST["location"]);
       $remark = htmlspecialchars($_POST["remark"]);
       $date = date("Y-m-d");
       $user = $_SESSION["username"];
       $sql = "INSERT INTO logs (cat_id,sub_cat_id,incident_id,solution_id, name, location,remark,create_at) VALUES ($cat_id,$sub_id,$inc_id,$answer_id,'$user','$location','$remark','$date')";
       $result = mysqli_query($connect,$sql);
       if($result){
        success_message("Create Logs Success","view_logs.php");
       }else{
        error_message("Create Logs  Fail","view_logs.php");
       }
    }


    /* create_location */
    if(isset($_POST["create_location"])){
       $location = htmlspecialchars($_POST["location"]);
       $sql = "INSERT INTO location(name) VALUES ('$location')";
       $result = mysqli_query($connect,$sql);
       if($result){
        success_message("Create Location Success",$_SERVER['HTTP_REFERER']);
       }else{
        error_message("Create Location  Fail",$_SERVER['HTTP_REFERER']);
       }
    }


    /* member_create */
    if(isset($_POST["member_create"])){
       $username = htmlspecialchars($_POST["username"]);
       $password = htmlspecialchars($_POST["password"]);
       $role = htmlspecialchars($_POST["role"]);
       $date = date("Y-m-d");
       $sql = "SELECT * FROM member WHERE name='$username'";
       $result = mysqli_query($connect, $sql);
       $row = mysqli_num_rows($result);
       if($row > 0){
        error_message("Member Already Exists ",$_SERVER['HTTP_REFERER']);
        die();
       }
        /* No Encrypt Here Encrypt Your Self */
       $sql2 = "INSERT INTO member(name,password,role,status,profile,position, department,phone,create_at) VALUES ('$username','$password','$role',1,'-','-','-','-','$data')";
       $result2 = mysqli_query($connect,$sql2);
       if($result){
        success_message("Member Create Success",$_SERVER['HTTP_REFERER']);
       }else{
        error_message("Member Create  Fail",$_SERVER['HTTP_REFERER']);
       }
    }

?>