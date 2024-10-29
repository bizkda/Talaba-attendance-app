<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Talaba list</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        /* Custom styling for RTL layout */
        body {
            text-align: right; /* Align text to the right */
        }
        .table thead th {
            text-align: right; /* Align table headers to the right */
        }
        .table tbody td {
            text-align: right; /* Align table cells to the right */
        }
    </style>
</head>
<body>
    <div class="container">
        <header class="d-flex justify-content-between my-4">
            
            <div>
            <a href="logout.php" class="btn btn-danger">خروج</a>
             <!-- Delete All button triggers form submission -->
                <!--<form method="POST" action="process.php" style="display:inline;">
                    <input type="submit" name="delete_all" value="حذف الكل" class="btn btn-danger">
                </form> -->
            <a href="create.php" class="btn btn-primary">اضافة طالب</a>
            </div>
            
        </header>
        <h1>قائمة الطلبة</h1>
        <?php
            session_start();
            if(isset($_SESSION["add_talib"])){
                ?>
                <div class="alert alert-success">
                    <?php
                    echo $_SESSION["add_talib"];
                    unset($_SESSION["add_talib"])
                    ?>
                </div>
            <?php
            }
        ?>
        <?php
            session_start();
            if(isset($_SESSION["edit_talib"])){
                ?>
                <div class="alert alert-success">
                    <?php
                    echo $_SESSION["edit_talib"];
                    unset($_SESSION["edit_talib"])
                    ?>
                </div>
            <?php
            }
        ?>
        <?php
            session_start();
            if(isset($_SESSION["delete"])){
                ?>
                <div class="alert alert-success">
                    <?php
                    echo $_SESSION["delete"];
                    unset($_SESSION["delete"])
                    ?>
                </div>
            <?php
            }
        ?>
        <table class = "table table bordered">
            <thead>
                <tr>
                    
                    <th>المهام</th>
                    
                    <th>اللقب</th>
                    <th>الاسم</th>
                    <th>#</th>
                </tr>
            </thead>
            <tbody>
            <?php
                    include('connect.php');
                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }

                    if (isset($_SESSION["user_id"])) {
                        $user_id = $_SESSION["user_id"];
                    } else {
                        // Redirect to login page if user is not logged in
                       
                        header("Location: login.php");
                        exit();
                    }
                    $user_id = intval($user_id); // Ensures that $user_id is an integer

                    if ($user_id === 1) {
                        $sqlSelect = "SELECT * FROM chikh_talaba3";
                        $result = mysqli_query($conn, $sqlSelect);
                        // Fetch all the data into an array
                        if($result){
                            $dataArray = [];
                            while ($data = mysqli_fetch_array($result)) {
                            $dataArray[] = $data;
                        }
                        
                            // Display the reordered data
                            foreach ($dataArray as $data) {
                                ?>
                                <tr>
                                    <td>
                                        <a href="talib_archive.php?id=<?php echo $data['id']; ?>" class="btn btn-warning">السجل</a>
                                        <a href="more_info.php?id=<?php echo $data['id']; ?>" class="btn btn-info">التقييم</a>
                                        
                                    </td>
                                    <td><?php echo $data['last_name'] ; ?></td>
                                    <td><?php echo $data['first_name'] ; ?></td>
                                    <td><?php echo '#'; ?></td>
                                </tr> 
                                <?php
                            }
                        }
                        
                    }else {
                        // Check if today is Friday
                        $isFriday = (date('N') == 6);

                        // Base query to get all records for the user
                        $sqlSelect = "SELECT * FROM chikh_talaba3 WHERE user_id = '$user_id' ORDER BY id ASC";
                        $result = mysqli_query($conn, $sqlSelect);
                        if($result){
                            // Fetch all the data into an array
                        $dataArray = [];
                        while ($data = mysqli_fetch_array($result)) {
                            $dataArray[] = $data;
                        }

                        // If today is Friday, reorder the array
                        if ($isFriday) {
                            // Shift the array to make the second element the first, the third the second, etc.
                            $firstElement = array_shift($dataArray); // Remove the first element
                            $dataArray[] = $firstElement; // Add it to the end of the array
                        }
                        
                        // Display the reordered data
                        foreach ($dataArray as $data) {
                            ?>
                            <tr>
                                <td>
                                    <a href="talib_archive.php?id=<?php echo $data['id']; ?>" class="btn btn-warning">السجل</a>
                                    <a href="more_info.php?id=<?php echo $data['id']; ?>" class="btn btn-info">التقييم</a>
                                    
                                </td>
                                <td><?php echo $data['last_name'] ; ?></td>
                                <td><?php echo $data['first_name'] ; ?></td>
                                <td><?php echo '#'; ?></td>
                            </tr>
                        
                        
                        
                        
                <?php
                        }
                    }
                }
                
                ?>

                   

        
            </tbody>
        </table>
    </div>
</body>