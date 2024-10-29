<?php
include('connect.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start(); // Start the session to access session variables

if (isset($_POST["add_talib"])) {
    // Sanitize user input
    $first_name = mysqli_real_escape_string($conn, $_POST["first_name"]);
    $last_name = mysqli_real_escape_string($conn, $_POST["last_name"]);
    $first_name_fr = mysqli_real_escape_string($conn, $_POST["first_name_fr"]);
    $last_name_fr = mysqli_real_escape_string($conn, $_POST["last_name_fr"]);

    // Check if the user ID and chikh_name are set in the session
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['chikh_name'])) {
        die("User is not logged in or chikh name not found.");
    }

    // Get the user ID and chikh_name from the session
    $user_id = $_SESSION['user_id'];
    $chikh_name = $_SESSION['chikh_name'];

    // Prepare insert query
    $sqlInsert = "INSERT INTO chikh_talaba3 (first_name, last_name,  user_id, chikh_name, first_name_fr, last_name_fr) 
                  VALUES (?, ?, ?, ?, ?, ?)";
    
    // Prepare and execute the statement
    $stmt = mysqli_prepare($conn, $sqlInsert);
    mysqli_stmt_bind_param($stmt, "ssssss", $first_name, $last_name, $user_id, $chikh_name, $first_name_fr, $last_name_fr);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION["add_talib"] = "تمت اضافة الطالب بنجاح";
        header("Location: table.php");
        exit();
    } else {
        echo "Error inserting record: " . mysqli_error($conn);
    }
}

if (isset($_POST["delete_all"])) {
    // Get the logged-in user's ID from the session
    if (!isset($_SESSION["user_id"])) {
        die("User is not logged in.");
    }

    $user_id = $_SESSION["user_id"];

    // Prepare delete query
    $sqlDelete = "DELETE FROM chikh_talaba3 WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $sqlDelete);
    mysqli_stmt_bind_param($stmt, "i", $user_id);

    if (mysqli_stmt_execute($stmt)) {
        // Reset the auto-increment value
        //$sqlResetAutoIncrement = "ALTER TABLE chikh_talaba3 AUTO_INCREMENT = 1";
        //mysqli_query($conn, $sqlResetAutoIncrement);

        $_SESSION["delete_all"] = "All talib records deleted successfully"; 
        header("Location: table.php");
        exit();
    } else {
        echo "Error deleting records: " . mysqli_error($conn);
    }
}

// Process the form submission for attendance
if (isset($_POST["submit_options"])) {
    // Check and sanitize ID
    if (isset($_POST["id"])) {
        $id = mysqli_real_escape_string($conn, $_POST["id"]);
    } else {
        die("Error: ID not provided.");
    }

    // Sanitize other inputs
        $absence = mysqli_real_escape_string($conn, $_POST["attendance"] ?? '');
        $wird_hifdh = mysqli_real_escape_string($conn, $_POST["wird_hifdh"] ?? '');
        $quality_hifdh = mysqli_real_escape_string($conn, $_POST["quality_hifdh"] ?? '');
        $wird_mourajaa = mysqli_real_escape_string($conn, $_POST["wird_mourajaa"] ?? '');
        $quality_mourajaa = mysqli_real_escape_string($conn, $_POST["quality_mourajaa"] ?? '');
        $quality_conduct = mysqli_real_escape_string($conn, $_POST["quality_conduct"] ?? '');
        $absence_justification = mysqli_real_escape_string($conn, $_POST["absence_justification"] ?? '');

    
// Fetch the current absence count and other values from the database
$result = mysqli_query($conn, "SELECT absence, hifdh, mourajaa, conduct, good_hifdh_streak , good_mourajaa_streak , good_conduct_streak , last_selected_hifdh , last_selected_mourajaa , last_selected_conduct , last_selected_attendance FROM chikh_talaba3 WHERE id = '$id'");
$row = mysqli_fetch_assoc($result);

$number_absence = $row['absence'] ?? 0; // Default to 0 if NULL
$hifdh = $row['hifdh'] ?? 0; // Default to 0 if NULL
$mourajaa = $row['mourajaa'] ?? 0; // Default to 0 if NULL
$conduct = $row['conduct'] ?? 0; // Default to 0 if NULL
$good_hifdh_streak = $row['good_hifdh_streak'] ?? 0; // Default to 0 if NULL
$good_mourajaa_streak = $row['good_mourajaa_streak'] ?? 0; // Default to 0 if NULL
$good_conduct_streak = $row['good_conduct_streak'] ?? 0; // Default to 0 if NULL

if($absence_justification === "yes"){
    $number_absence--; // Increment if the student is absent

}

if ($absence === 'absent') {
    $number_absence++; // Increment if the student is absent

    // Generate the current date
    $currentDate = date('Y-m-d');

    // Update the absence_date and number_absence fields
    $sqlAbsence = "UPDATE chikh_talaba3 SET absence_date = CONCAT(IFNULL(absence_date, ''), '\n', ?), absence = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sqlAbsence);
    mysqli_stmt_bind_param($stmt, "sii", $currentDate, $number_absence, $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
} elseif ($absence === 'absent_justified') {
    // Generate the current date
    $currentDate = date('Y-m-d');

    // Update the absence_date and number_absence fields
    $sqlAbsence = "UPDATE chikh_talaba3 SET absence_date = CONCAT(IFNULL(absence_date, ''), '\n', ?), absence = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sqlAbsence);
    mysqli_stmt_bind_param($stmt, "sii", $currentDate, $number_absence, $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
} else {
    // Update hifdh based on quality_hifdh
    if ($quality_hifdh === 'bad_hifdh') {
        $hifdh++; // Increment for 'bad hifdh'
        $good_hifdh_streak = 0; // Reset streak for bad hifdh

        // Generate the current date
        $currentDate = date('Y-m-d');

        // Update the absence_date and number_absence fields
        $sqlNoHifdh = "UPDATE chikh_talaba3 SET no_hifdh_date = CONCAT(IFNULL(no_hifdh_date, ''), '\n', ?) WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sqlNoHifdh);
        mysqli_stmt_bind_param($stmt, "si", $currentDate, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

    } elseif ($quality_hifdh === 'good_hifdh') {
        $good_hifdh_streak++; // Increment streak for 'good hifdh'

        // Only decrement hifdh if the student has done good hifdh for 3 successive times
        if ($good_hifdh_streak >= 3 && $hifdh > 0) {
            $hifdh--; // Decrement hifdh after 3 good performances
            $good_hifdh_streak = 0; // Reset the streak
        }
    }

    // Update mourajaa based on quality_mourajaa
    if ($quality_mourajaa === 'bad_mourajaa') {
        $mourajaa++; // Increment for 'bad mourajaa'
        $good_mourajaa_streak = 0;

        // Generate the current date
        $currentDate = date('Y-m-d');

        // Update the absence_date and number_absence fields
        $sqlNoMourajaa = "UPDATE chikh_talaba3 SET no_mourajaa_date = CONCAT(IFNULL(no_mourajaa_date, ''), '\n', ?) WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sqlNoMourajaa);
        mysqli_stmt_bind_param($stmt, "si", $currentDate,  $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

    } elseif ($quality_mourajaa === 'good_mourajaa') {
        $good_mourajaa_streak++;

        if($good_mourajaa_streak >= 3 && $mourajaa > 0){
            $mourajaa--;
            $good_mourajaa_streak = 0;
        }
    }

    // Update conduct based on quality_conduct
    if ($quality_conduct === 'bad_conduct') {
        $conduct++; // Increment for 'bad conduct'
    } elseif ($quality_conduct === 'good_conduct') {

        $good_conduct_streak++;

        if($good_conduct_streak >= 3 && $conduct > 0 ){
            $conduct--;
            $good_conduct_streak = 0;
        }
    }
    

}

    
    // Prepare update query
    $sqlUpdate = "UPDATE chikh_talaba3 SET 
                  wird_hifdh = ?, 
                  wird_mourajaa = ?, 
                  absence = ?, 
                  conduct = ?, 
                  hifdh = ?, 
                  mourajaa = ?, 
                  good_hifdh_streak = ?,
                  good_mourajaa_streak = ?,
                  good_conduct_streak = ?,
                  last_selected_hifdh = ?,
                  last_selected_mourajaa = ?,
                  last_selected_conduct = ?,
                  last_selected_attendance = ?
                  WHERE id = ?";

    // Prepare and execute the statement
    $stmt = mysqli_prepare($conn, $sqlUpdate);
    mysqli_stmt_bind_param($stmt, "ssiiiiiiissssi", $wird_hifdh, $wird_mourajaa, $number_absence, $conduct, $hifdh, $mourajaa, $good_hifdh_streak , $good_mourajaa_streak , $good_conduct_streak , $quality_hifdh , $quality_mourajaa , $quality_conduct , $absence , $id);
    
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: table.php");
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
}

?>