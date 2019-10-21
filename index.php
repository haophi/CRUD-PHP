<?php
    require_once 'connect.php';
    // MULTY DELETE
    session_start();
    $mesageDelete = "";
    $total = "";
    if (isset($_POST['token'])) {
        if (isset($_SESSION['token']) && $_SESSION['token'] == $_POST['token']) {
            unset($_SESSION['token']);
            header('location:'.$_SERVER['PHP_SELF']);
            exit();
        } else {
            $_SESSION['token'] = $_POST['token'];
        }
        $checkbox = isset($_POST['checkbox']) ? $_POST['checkbox']: "";
        if (!empty($checkbox)) {
            $total = $database->delete($checkbox);
            $mesageDelete = '<div class="success">Có '.$total.' dòng được xoá!</div>';
        } else {
            $mesageDelete = '<div class="notice">Vui lòng chọn vào những dòng muốn xoá!</div>';
        }
    }


    // Sắp xếp
    $columns = array('id', 'firstname', 'lastname', 'username', 'age', 'email');
    $column = isset($_GET['column']) && in_array($_GET['column'], $columns) ? $_GET['column'] : $columns[0];
    $sort_order = isset($_GET['order']) && strtolower($_GET['order']) == 'desc' ? 'DESC' : 'ASC';
    $up_or_down = str_replace(array('ASC','DESC'), array('sort-by-up','sort-by-down'), $sort_order); 
    $asc_or_desc = $sort_order == 'ASC' ? 'desc' : 'asc';


    // PHÂN TRANG
    require_once 'class/Pagination.class.php';
    $totalItems = $database->totalItem("SELECT COUNT(`id`) AS totalItems FROM `users`");
    $totalItemsPerPage = 5;
    $pageRange = 3;
    $currentPage = (isset($_GET['page'])) ? $_GET['page'] : 1;
    $pagination = new Pagination($totalItems, $totalItemsPerPage, $pageRange, $currentPage, $column, strtolower($sort_order));
    $paginationHTML = $pagination->showPagination();
    $position = ($currentPage - 1)*$totalItemsPerPage;


    // List
    $query[] = "SELECT `id`, `firstname`, `lastname`, `age`, `email`, `username`";
    $query[] = "FROM `users`";
    $query[] = "ORDER BY `$column` $sort_order";
    $query[] = "LIMIT $position,$totalItemsPerPage";

    $query = implode(" ", $query);
    $list = $database -> listRecord($query);
    if (!empty($list)) {
        $xhtml = "";
        $i = 0;
        foreach($list as $item) {
            $row = ($i %2 == 0) ? 'odd' : 'even';
            $id = $item['id'];
            $xhtml .= '<div class="row '. $row .'">
                        <p class="no"><input type="checkbox" name="checkbox[]" value="'. $id .'" /></p>
                        <p class="id">'. $id .'</p>
                        <p class="name">'. $item['firstname'] .'</p>
                        <p class="size">'. $item['lastname'] .'</p>
                        <p class="size">'. $item['username'] .'</p>
                        <p class="size">'. $item['age'] .'</p>
                        <p class="size">'. $item['email'] .'</p>
                        <p class="action">
                            <a href="form.php?action=edit&id='. $id .'">Edit</a> |
                            <a href="delete.php?id='. $id .'">Delete</a>
                        </p>
                    </div>';
            $i++;
        }
    }
?>

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
    <div id="wrapper">
        <div class="title">Manage User</div>
        <div class="list">
            <div id="area-button">
                <a href="form.php?action=add">Add User</a>
                <a id="multy-delete" style="height=20px;" href="#">Delete User</a>
            </div>
            <?php echo $mesageDelete ?>
            <form action="index.php" method="POST" name="main-form" id="main-form">
                <div class="row" style="text-align: center; font-weight:bold">
                    <p class="no"><input type="checkbox" name="check-all" id="check-all" /></p>
                    <p class="id">ID<a href="index.php?column=id&order=<?php echo $asc_or_desc; ?>&page=<?php echo $currentPage ?>" class="sort-by <?php echo  $column == 'id' ? $up_or_down : 'sort-by-up sort-by-down' ?>" ></a></p>
                    <p class="name">First Name<a href="index.php?column=firstname&order=<?php echo $asc_or_desc; ?>&page=<?php echo $currentPage ?>" class="sort-by <?php echo  $column == 'firstname' ? $up_or_down : 'sort-by-up sort-by-down' ?>" ></a></p>
                    <p class="size">Last Name<a href="index.php?column=lastname&order=<?php echo $asc_or_desc; ?>&page=<?php echo $currentPage ?>" class="sort-by <?php echo  $column == 'lastname' ? $up_or_down : 'sort-by-up sort-by-down' ?>" ></a></p>
                    <p class="size">User Name<a href="index.php?column=username&order=<?php echo $asc_or_desc; ?>&page=<?php echo $currentPage ?>" class="sort-by <?php echo  $column == 'username' ? $up_or_down : 'sort-by-up sort-by-down' ?>" ></a></p>
                    <p class="size">Age<a href="index.php?column=age&order=<?php echo $asc_or_desc; ?>&page=<?php echo $currentPage ?>" class="sort-by <?php echo  $column == 'age' ? $up_or_down : 'sort-by-up sort-by-down' ?>" ></a></p>
                    <p class="size">Email<a href="index.php?column=email&order=<?php echo $asc_or_desc; ?>&page=<?php echo $currentPage ?>" class="sort-by <?php echo  $column == 'email' ? $up_or_down : 'sort-by-up sort-by-down' ?>" ></a></p>
                    <p class="action">Action</p>
                </div>
                <?php echo $xhtml; ?>

                <!-- For multy delete  -->
                <input type="hidden" value="<?php echo time()?>" name="token" /> 
            </form>
        </div>
       
        <div id="pagination">
            <?php echo $paginationHTML ?>
        </div>
    </div>
</body>

</html>