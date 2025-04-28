<?php
include(__DIR__ . "/auth.php");

if (!$authenticated) {
    header("Location: ./login.php");
} else {
    if (!$admin) {
        header("Location: ./unauth.php");
    }
}

include("../utils/dbConnection.php");

$name = $infoSinger = $imgFile = "";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql2 = "SELECT * FROM singers WHERE id= '$id' ";
    $res2 = mysqli_query($conn, $sql2);
    $data = mysqli_fetch_array($res2);
    $name = $data["name"];
    $imgFile = "../" . $data["image"];
    $infoSinger = $data["info"];
}

$errors = array('singername' => '', 'info' => '', 'img' => '');
$singername = $img = $info = '';

// INSERT SINGER INTO DATABASE
function saveFile($fileInfo)
{
    $filename = $fileInfo['name'];
    $tmpPath = $fileInfo['tmp_name'];
    $destinationPath = __DIR__ . '/../images/singers/' . $filename;

    if (move_uploaded_file($tmpPath, $destinationPath)) {
        return 'images/singers/' . $filename; // Lưu đường dẫn vào DB
    } else {
        return false; // Trả về false nếu không upload được
    }
}

if (isset($_POST['submit'])) {
    if (empty($_FILES["img"]["name"])) {
        if (!isset($_GET['id'])) {
            $errors['img'] = "Image field cannot be empty";
        }
    } else {
        // Kiểm tra định dạng tệp
        $allowedTypes = ['image/jpeg', 'image/png']; // Các loại tệp hợp lệ
        if (in_array($_FILES["img"]["type"], $allowedTypes)) {
            $img = $_FILES['img'];
        } else {
            $errors['img'] = "Wrong file format. Expect JPG or PNG file.";
        }
    }

    if (empty($_POST['singername'])) {
        $errors['singername'] = "Singer's name cannot be empty.";
    } else {
        $singername = $_POST['singername'];
    }

    if (empty($_POST['info'])) {
        $errors['info'] = "Info cannot be empty.";
    } else {
        $info = $_POST['info'];
    }

    if (array_filter($errors)) {
        echo 'Form not valid';
    } else {
        if ($img != "") {
            $images = saveFile($img);
            if (!$images) {
                $errors['img'] = "File upload failed. Please try again.";
            }
        } else {
            $images = isset($data["image"]) ? $data["image"] : '';
        }

        if (isset($_GET['id'])) {
            $updateSinger = "UPDATE singers SET name = '$singername', info = '$info', image = '$images' WHERE id =$id";
            $res3 = mysqli_query($conn, $updateSinger);
            header("Location: editSinger.php");
        } else {
            $insertSinger = "INSERT INTO singers(name, info, image) VALUES ('$singername', '$info', '$images')";
            if (!mysqli_query($conn, $insertSinger)) {
                echo "Error: " . "<br>" . mysqli_error($conn);
            } else {
                header("Location: editSinger.php");
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Singer</title>
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <form method="POST" enctype="multipart/form-data">
        <?php foreach ($errors as $error) : ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endforeach; ?>
        <h2>Singers</h2>

        <label>Name</label>
        <input type="text" name="singername" placeholder="Singer name" value="<?php echo $name; ?>"><br>
        <label>More</label>
<textarea 
    name="info" 
    rows="8" 
    cols="50" 
    style="margin: 0px; width: 100%; height: auto; border: 2px solid #ccc; border-radius: 5px;" 
    placeholder="Singer Info"><?php echo htmlspecialchars($infoSinger); ?></textarea><br>


        <?php if ($imgFile != "") : ?>
            <label>Current Image</label>
            <img style="width: 50px; height: 50px;" src="<?php echo $imgFile; ?>" alt="">
            <br>
        <?php endif; ?>
        <label>File Images</label>
        <input type="file" name="img" accept="image/*"> <br>

        <a href="editSinger.php" class="ca">BACK</a>

        <button type="submit" name="submit">SUBMIT</button>
    </form>
</body>

</html>
