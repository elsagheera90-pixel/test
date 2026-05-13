<?php
// إخفاء الأخطاء التقنية عشان الشكل يبقى نضيف
error_reporting(0);

// استدعاء الملف اللي بره الـ public
include('/home/nile/ali/config.php');

// الربط بالقاعدة
$conn = mysqli_connect($host, $db_user, $db_pass, $db_name);

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $u = mysqli_real_escape_string($conn, $_POST['user']);
    $p = $_POST['pass'];

    // استعلام عن اليوزر
    $sql = "SELECT * FROM users WHERE username='$u'";
    $result = mysqli_query($conn, $sql);

    if ($row = mysqli_fetch_assoc($result)) {
        // الحركة اللي اتفقنا عليها: مقارنة مباشرة بدون تشفير
        if ($p == $row['password']) {
            $message = "<h1 style='color:green;'>Success!</h1>";
        } else {
            $message = "<h1 style='color:red;'>Wrong Password!</h1>";
        }
    } else {
        $message = "<h1 style='color:red;'>User Not Found!</h1>";
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>Login System</title>
</head>
<body style="text-align:center; margin-top:100px; font-family: Arial, sans-serif;">

    <div style="margin-bottom: 20px;">
        <?php echo $message; ?>
    </div>

    <form method="POST" style="display: inline-block; border: 1px solid #ccc; padding: 20px; border-radius: 10px;">
        <h2>Login Test</h2>
        <input type="text" name="user" placeholder="Username" required style="margin-bottom:10px; padding:8px;"><br>
        <input type="password" name="pass" placeholder="Password" required style="margin-bottom:10px; padding:8px;"><br>
        <button type="submit" style="padding: 10px 20px; background-color: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer;">
            Login
        </button>
    </form>

</body>
</html>





<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('/home/nile/ali_adel/ali_config.php');

$conn = mysqli_connect($host, $db_user, $db_pass, $db_name);

if (!$conn) {
    die("<h2 style='color:red; text-align:center;'>Connection Failed: " . mysqli_connect_error() . "</h2>");
}

// السطرين دول خليهم في الكود أول ما تفتح الموقع، وبعد ما تظهر Success امسحهم
$new_hash = password_hash('123', PASSWORD_DEFAULT);
$update_result = mysqli_query($conn, "UPDATE users SET password='$new_hash' WHERE username='admin'");
if (!$update_result) {
    echo "<p style='color:orange; text-align:center;'>Password update error: " . mysqli_error($conn) . "</p>";
}

$message = "";
$message_color = "black";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $u = trim($_POST['user']);
    $p = trim($_POST['pass']);

    if (empty($u) || empty($p)) {
        $message = "Error: Username and password are required.";
        $message_color = "red";
    } else {
        $sql = "SELECT * FROM users WHERE username='" . mysqli_real_escape_string($conn, $u) . "'";
        $result = mysqli_query($conn, $sql);

        if ($result === false) {
            $message = "Query Error: " . mysqli_error($conn);
            $message_color = "red";
        } elseif ($row = mysqli_fetch_assoc($result)) {
            if (password_verify($p, $row['password'])) {
                $message = "Success! Secure Login";
                $message_color = "green";
            } else {
                $message = "Error: Invalid Password!";
                $message_color = "red";
            }
        } else {
            $message = "Error: User not found!";
            $message_color = "red";
        }
    }
}
?>

<form method="POST" style="margin-top:50px; text-align:center;">
    <h2>Secure Login System</h2>

    <?php if (!empty($message)): ?>
        <h3 style="color:<?= $message_color ?>;"><?= htmlspecialchars($message) ?></h3>
    <?php endif; ?>

    <input type="text" name="user" placeholder="Username" required><br><br>
    <input type="password" name="pass" placeholder="Password" required><br><br>
    <button type="submit">Login</button>
</form>
