<!DOCTYPE html>
<html lang="en" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Talib Archive</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap.min.css">
    <style>
        /* Custom styling for text fields */
        .absence-field {
            width: 100%;
            text-align: right;
            overflow: hidden;
            resize: none;
        }
    </style>
    <script>
        // Auto resize function for textarea elements
        function autoResize(textarea) {
            textarea.style.height = 'auto'; // Reset the height
            textarea.style.height = (textarea.scrollHeight) + 'px'; // Adjust to content height
        }

        // Automatically resize all absence-field textareas on page load and input
        document.addEventListener('DOMContentLoaded', function() {
            const textareas = document.querySelectorAll('.absence-field');
            textareas.forEach(textarea => {
                autoResize(textarea); // Resize on load
                textarea.addEventListener('input', () => autoResize(textarea)); // Resize on input
            });
        });
    </script>
</head>
<body>
    <div class="container">
    <?php
        include('connect.php');
        ini_set('display_errors', 1);
        error_reporting(E_ALL);

        if (isset($_GET["id"])) {
            $id = $_GET["id"];
            $result = mysqli_query($conn, "SELECT * FROM chikh_talaba3 WHERE id = '$id'");
            $row = mysqli_fetch_assoc($result);

            if ($row) {
        ?>
        <header class="d-flex justify-content-between align-items-center p-3 my-4 bg-light border rounded shadow-sm">
                
                <h2 class="m-0 text-primary">
                    <?php 
                        // Displaying the student's full name with Arabic text for "Student"
                        echo 'الطالب' . ': ' . htmlspecialchars($row['first_name']) . ' ' . htmlspecialchars($row['last_name']); 
                    ?> 
                </h2>
                <div>
                    <a href="table.php" class="btn btn-warning">عودة</a>
                </div>
        </header>

        
        <div class="mb-4">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    
             <!-- Update fields without extra whitespace -->
            <div class="d-flex flex-column">
                <span class="badge bg-primary mb-2" style="font-size: 1.2em; padding: 0.5em 1em;">تواريخ الغياب</span>
                <textarea class="form-control absence-field" readonly placeholder="لاتوجد غيابات"><?php echo !empty($row['absence_date']) ? htmlspecialchars($row['absence_date']) : ''; ?></textarea>
            </div>
            <br>
            <div class="d-flex flex-column">
                <span class="badge bg-primary mb-2" style="font-size: 1.2em; padding: 0.5em 1em;">التقارير المرفوعة</span>
                <textarea class="form-control absence-field" rows="4" readonly placeholder="لا توجد تقارير"><?php echo !empty($row['taqarir']) ? htmlspecialchars($row['taqarir']) : ''; ?></textarea>
            </div>
            <br>
            <div class="d-flex flex-column">
                <span class="badge bg-primary mb-2" style="font-size: 1.2em; padding: 0.5em 1em;">تواريخ عدم الحفظ</span>
                <textarea class="form-control absence-field" readonly placeholder="تلميذ رائع"><?php echo !empty($row['no_hifdh_date']) ? htmlspecialchars($row['no_hifdh_date']) : ''; ?></textarea>
            </div>
            <br>
            <div class="d-flex flex-column">
                <span class="badge bg-primary mb-2" style="font-size: 1.2em; padding: 0.5em 1em;">تواريخ عدم المراجعة</span>
                <textarea class="form-control absence-field" readonly placeholder="تلميذ رائع"><?php echo !empty($row['no_mourajaa_date']) ? htmlspecialchars($row['no_mourajaa_date']) : ''; ?></textarea>
            </div>
        </div>
        <?php
            } else {
                echo "<p>لا توجد بيانات للطالب.</p>";
            }
        }
        ?>
    </div>
</body>
</html>
