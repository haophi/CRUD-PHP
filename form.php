<?php 
    require_once 'class/Validate.class.php';
    require_once 'connect.php';

    session_start();

    $error = "";
    $success = "";
    $outputValidate = array();
    
    $id = isset($_GET['id']) ? $_GET['id'] : "";
    //echo $id;
    $action = $_GET['action'];

    $flagRedirect = false;

    $titlePage = "";
    if($action == 'edit'){
        $id = mysqli_real_escape_string($database->getConnect(), $id);
        $query = "SELECT `firstname`, `lastname`, `username`, `age`, `email` FROM `users` WHERE `id` = '".$id."'";
        $outputValidate = $database->singleRecord($query);
        $linkForm = 'form.php?action=edit&id='.$id;
        $titlePage = "EDIT USER";
        if(empty($outputValidate)) $flagRedirect = true;
    }else if($action == 'add'){
        $titlePage = "ADD USER";
        $linkForm = 'form.php?action=add&id=' . $id;
    }else{
       $flagRedirect = true;
    }
    // Chuyển sang trang error
    if($flagRedirect == true){
        header('location: error.php');
        exit();
    }
   
    if(!empty($_POST)){
        if(isset($_SESSION['token']) && $_SESSION['token'] == $_POST['token']){//refesh page ko cho thêm mới
            unset($_SESSION['token']);
            header('location:'.$linkForm);
            exit();
        }else{
            $_SESSION['token'] = $_POST['token'];
        }

        //Loại bỏ trường hợp submit và token
        $source = array(
            'firstname' => $_POST['firstname'],
            'lastname' => $_POST['lastname'],
            'username' => $_POST['username'],
            'age' => $_POST['age'],
            'email' => $_POST['email']
        );
        $validate = new Validate($source);
        $validate->addRule('firstname', 'string', 2, 50)
                 ->addRule('lastname', 'string', 2, 50)
                 ->addRule('username', 'string', 2, 50)
                 ->addRule('age', 'int', 1, 150)
                 ->addRule('email', 'email');

        $validate -> run();
        $outputValidate = $validate->getResult();

        if(!$validate->isValid()){
            $error = $validate->showErrors();
        }else{
            if($action == 'edit'){
                $where = array(array('id', $id));
                $database->update($outputValidate, $where);
                $success = '<div class= "success">Đã sửa thành công</div>';
            }else if ($action == 'add'){
                $database->insert($outputValidate);
                $success = '<div class= "success">Đã thêm thành công</div>';
                $outputValidate = array();
            }
        }
    }
 ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <title><?php echo $titlePage?></title>
    <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
    <script src="js/my-js.js"></script>
</head>
<body>
	<div id="wrapper">
    	<div class="title"><?php echo $titlePage ?></div>
        <div id="form">  
            <?php echo $error . $success ?> 
			<form action="<?php echo $linkForm ?>" method="POST" name="add-form">
				<div class="row">
					<p>First name</p>
                    <input type="text" name="firstname" value="<?php if(isset($outputValidate['firstname'])) echo $outputValidate['firstname'];?>">
				</div>
                
                <div class="row">
					<p>Last name</p>
                    <input type="text" name="lastname" value="<?php if(isset($outputValidate['lastname'])) echo $outputValidate['lastname'];?>">
				</div>
				
				<div class="row">
					<p>User Name</p>
					<input type="text" name="username" value="<?php if(isset($outputValidate['username'])) echo $outputValidate['username'];?>">
                </div>
                
                <div class="row">
					<p>Email</p>
					<input type="text" name="email" value="<?php if(isset($outputValidate['email'])) echo $outputValidate['email'];?>">
                </div>
                
                <div class="row">
					<p>Age</p>
					<input type="text" name="age" value="<?php if(isset($outputValidate['age'])) echo $outputValidate['age'];?>">
                </div>
				
				<div class="row">
					<input type="submit" value="Save" name="submit">
                    <input type="button" value="Cancel" name="cancel" id="cancel-button">
                    <input type="hidden" value="<?php echo time();?>" name="token" /> 
				</div>
			</form>    
        </div>
    </div>
</body>
</html>
