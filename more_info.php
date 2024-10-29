<!DOCTYPE html>
<html lang="en" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفاصيل المعلومات عن الطالب</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script>
    function toggleFields() {
        const attendanceAbsent = document.getElementById('absent').checked;
        const attendanceAbsent_justified = document.getElementById('absent_justified').checked;
        const hifdhSection = document.getElementById('hifdhSection');
        const mourajaaSection = document.getElementById('mourajaaSection');
        const conductSection = document.getElementById('conductSection');

        if (attendanceAbsent || attendanceAbsent_justified) {
            hifdhSection.style.display = 'none';
            mourajaaSection.style.display = 'none';
            conductSection.style.display = 'none';
        } else {
            hifdhSection.style.display = 'block';
            mourajaaSection.style.display = 'block';
            conductSection.style.display = 'block';
        }
    }
    function toggleFields_hifdh() {
    // Get the radio buttons and the input field
    const goodHifdh = document.getElementById('good_hifdh');
    const badHifdh = document.getElementById('bad_hifdh');
    const wirdHifdhInput = document.querySelector('input[name="wird_hifdh"]');
    
    // Get the original value stored in the data attribute
    const originalValue = wirdHifdhInput.getAttribute('data-hifdh-value');

    // Clear or set the value based on the selected radio button
    if (goodHifdh.checked) {
        wirdHifdhInput.value = '';
    } else if (badHifdh.checked) {
        wirdHifdhInput.value = originalValue;
    } else {
        wirdHifdhInput.value = originalValue + ' + ';
    }
    }


    function toggleFields_mourajaa(){
    const goodMourajaa = document.getElementById('good_mourajaa');
    const badMourajaa = document.getElementById('bad_mourajaa');
    const wirdMourajaaInput = document.querySelector('input[name="wird_mourajaa"]');
    
    // Get the original value stored in the data attribute
    const originalValue = wirdMourajaaInput.getAttribute('data-mourajaa-value');

    if (goodMourajaa.checked) {
        wirdMourajaaInput.value = '';
    } else if (badMourajaa.checked) {
        wirdMourajaaInput.value = originalValue;
    } else {
        wirdMourajaaInput.value = originalValue + ' + ';
    }
    }
    

    </script>

</head>
<body>

    <?php
        include('connect.php'); // Ensure your database connection is included
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        if (isset($_GET["id"])) {
            include("connect.php");
            $id = $_GET["id"];
            // Fetch the row data from the database using the ID
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


            
    
       
                <!-- Consolidated Form -->
                <form method="POST" action="process.php" class="mb-3">
                    
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>"> <!-- Hidden input to pass the Talib's ID -->
                    
                    <div class="d-flex flex-column flex-md-row align-items-center">
                        <span class="badge bg-primary mb-2 mb-md-0 me-2" style="font-size: 1.2em; padding: 0.5em 1em;">هل الطالب حاضر ؟</span>

                        <!-- Present -->
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="attendance" id="present" value="present" required <?php if ($row['last_selected_attendance'] == 'present') echo 'checked'; ?> onclick="toggleFields()">
                            <label class="form-check-label" for="present">حاضر</label>
                        </div>

                        <!-- Absent Justified -->
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="attendance" id="absent_justified" value="absent_justified" <?php if ($row['last_selected_attendance'] == 'absent_justified') echo 'checked'; ?> onclick="toggleFields()">
                            <label class="form-check-label" for="absent_justified">غياب مبرر</label>
                        </div>

                        <!-- Absent -->
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="attendance" id="absent" value="absent" <?php if ($row['last_selected_attendance'] == 'absent') echo 'checked'; ?> onclick="toggleFields()">
                            <label class="form-check-label" for="absent">غياب غير مبرر</label>
                            
                        </div>
                        
                       
                    </div>
                   

                    <br>

                    <!-- Hifdh Form -->
                    <div id="hifdhSection">
                        <div class="d-flex flex-column flex-md-row align-items-center">
                            <span class="badge bg-primary mb-2 mb-md-0 me-2" style="font-size: 1.2em; padding: 0.5em 1em;">حفظ الطالب</span>
                            <input type="text" class="form-control mx-2 mb-2 mb-md-0" name="wird_hifdh" placeholder="أدخل حِفظ الطالب" style="width: 250px;" value="<?php echo $row['wird_hifdh']; ?>" data-hifdh-value="<?php echo $row['wird_hifdh']; ?>">

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="quality_hifdh" id="good_hifdh" value="good_hifdh" <?php if ($row['last_selected_hifdh'] == 'good_hifdh') echo 'checked'; ?> onclick="toggleFields_hifdh()">
                                <label class="form-check-label" for="good_hifdh">حفظ جيد</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="quality_hifdh" id="bad_hifdh" value="bad_hifdh" <?php if ($row['last_selected_hifdh'] == 'bad_hifdh') echo 'checked'; ?> onclick="toggleFields_hifdh()">
                                <label class="form-check-label" for="bad_hifdh">حفظ ضعيف</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="quality_hifdh" id="normal_hifdh" value="normal_hifdh" <?php if ($row['last_selected_hifdh'] == 'normal_hifdh') echo 'checked'; ?> onclick="toggleFields_hifdh()">
                                <label class="form-check-label" for="normal_hifdh">حفظ مقبول بتحفظ</label>
                            </div>
                        </div>
                    </div>
                    <br>

                    <!-- Mourajaa Form -->
                    <div id="mourajaaSection">
                        <div class="d-flex flex-column flex-md-row align-items-center">
                            <span class="badge bg-primary mb-2 mb-md-0 me-2" style="font-size: 1.2em; padding: 0.5em 1em;">هل الطالب مُراجِع؟</span>
                            <input type="text" class="form-control mx-2 mb-2 mb-md-0" name="wird_mourajaa" placeholder="أدخل مُراجعة الطالب" style="width: 250px;" value="<?php echo $row['wird_mourajaa']; ?>" data-mourajaa-value="<?php echo $row['wird_mourajaa']; ?>">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="quality_mourajaa" id="good_mourajaa" value="good_mourajaa" <?php if ($row['last_selected_mourajaa'] == 'good_mourajaa') echo 'checked'; ?> onclick="toggleFields_mourajaa()">
                                <label class="form-check-label" for="good_mourajaa">مراجعة جيدة</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="quality_mourajaa" id="bad_mourajaa" value="bad_mourajaa" <?php if ($row['last_selected_mourajaa'] == 'bad_mourajaa') echo 'checked'; ?> onclick="toggleFields_mourajaa()">
                                <label class="form-check-label" for="bad_mourajaa">مراجعة ضعيفة</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="quality_mourajaa" id="normal_mourajaa" value="normal_mourajaa" <?php if ($row['last_selected_mourajaa'] == 'normal_mourajaa') echo 'checked'; ?> onclick="toggleFields_mourajaa()">
                                <label class="form-check-label" for="normal_mourajaa">مراجعة مقبولة بتحفظ</label>
                            </div>
                        </div>
                    </div>
                    <br>

                    <!-- Conduct Form -->
                    <div id = "conductSection">
                        <div class="d-flex flex-column flex-md-row align-items-center">
                            <span class="badge bg-primary mb-2 mb-md-0 me-2" style="font-size: 1.2em; padding: 0.5em 1em;">هل سلوك الطالب جيد؟</span>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="quality_conduct" id="good_conduct" value="good_conduct" <?php if ($row['last_selected_conduct'] == 'good_conduct') echo 'checked'; ?>>
                                <label class="form-check-label" for="good_conduct">سلوك مقبول</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="quality_conduct" id="bad_conduct" value="bad_conduct" <?php if ($row['last_selected_conduct'] == 'bad_conduct') echo 'checked'; ?>>
                                <label class="form-check-label" for="bad_conduct">سلوك غير مقبول</label>
                            </div>
                        </div>
                    </div>
                    
                    <br>
                    
                    <?php if ($row['last_selected_attendance'] === 'absent'): ?>
                        <div class="d-flex flex-column flex-md-row align-items-center">
                            <span class="badge bg-primary mb-2 mb-md-0 me-2" style="font-size: 1.2em; padding: 0.5em 1em;">هل تم تبرير الغياب الماضي ؟</span>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="absence_justification" id="no_justify_absence" value="no">
                                    <label class="form-check-label" for="no_justify_absence"> لا</label>
                                </div>    

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="absence_justification" id="justify_absence"  value="yes">
                                    <label class="form-check-label" for="justify_absence"> نعم</label>
                                </div>
                                
                        </div>
                    <?php endif; ?>
                    

                    <div class="mb-4 mt-4 d-flex justify-content-center">
                        <button type="submit" name="submit_options" class="btn btn-success mx-2 px-4 py-2">إرسال الخيارات</button>
                    </div>

</form>

        <?php
            } else {
                echo "<p>لا توجد بيانات للطالب.</p>";
            }
        }
        ?>
    </div>
        
    
</body>
</html>
