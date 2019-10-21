<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <title>PHP CRUD</title>
    <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
    <script src="js/my-js.js"></script>
</head>
<body>
    <?php
        require_once 'connect.php';
        $id = $_GET['id'];
        $query = "SELECT * FROM `users` WHERE `id` = '".$id."'";
        $item = $database -> singleRecord($query);
        if(!empty($item)){
            $xhtml = '<div class="row">
                    <p>ID: </p>
                    <span>'.$item['id'].'</span>
                    </div>
                    <div class="row">
                        <p>First name: </p>
                        <span>'.$item['firstname'].'</span>
                    </div>
                    <div class="row">
                        <p>Last name: </p>
                        <span>'.$item['lastname'].'</span>
                    </div>
                    <div class="row">
                        <p>User name: </p>
                        <span>'.$item['username'].'</span>
                    </div>
                    <div class="row">
                        <p>Email: </p>
                        <span>'.$item['email'].'</span>
                    </div>
                    <div class="row">
                        <p>Age: </p>
                        <span>'.$item['age'].'</span>
                    </div>
                    <div class="row">
                        <input type="hidden" name="id" value="'.$id.'">
                        <input type="submit" value="Delete" name="submit">
                        <input type="button" value="Cancel" name="cancel" id="cancel-button">
                    </div>';
        }else{
            header('location: error.php');
            exit();
        }
        if(isset($_POST['submit'])){
            $query = "DELETE FROM `users` WHERE `id` = '". $_POST['id'] ."'";
            $database->_query($query);
            $xhtml = '<div class= "success">Đã xoá thành công ! Click vào <a href="index.php">đây</a> để về trang quản lý';
        }
    ?>
	<div id="wrapper">
    	<div class="title">Manage User</div>
        <div id="form">   
			<form action="#" method="post" name="add-form">
            <?php 
               echo $xhtml;
            ?>
			</form>  
        </div>
        
    </div>
</body>
</html>
