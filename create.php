<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add a new talib</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <div class="container">
    <header class="d-flex justify-content-between my-4">
            <div>
            <a href="table.php" class="btn btn-primary">عودة</a>
            </div>
            <h1>اضف طالب جديد</h1>
        </header>
    <form action="process.php" method="post">
    
        <div class="input-group mb-3">
                <input type="text" class="form-control" name="first_name" required >
                <span class="input-group-text" id="first_name_span">الاسم </span>
                            
        </div>
        <div class="input-group mb-3">
                <input type="text" class="form-control" name="last_name"  required >
                <span class="input-group-text" id="last_name_span">اللقب</span>
                    
        </div>
        <div class="input-group mb-3">
                <input type="text" class="form-control" name="first_name_fr"   required >
                <span class="input-group-text" id="first_name_fr_span">الاسم بالاتينية</span>
                    
        </div>
        <div class="input-group mb-3">
                <input type="text" class="form-control" name="last_name_fr"  required >
                <span class="input-group-text" id="first_name_fr_span">اللقب بالاتينية</span>
                    
        </div>


       
        <div class="form-element my-4">
                <input type="submit" name="add_talib" value="اضف الطالب" class="btn btn-primary">
        </div>
    </form>
    </div>
</body>
</html>