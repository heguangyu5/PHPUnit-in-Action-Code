<?php
    session_start();
    if (isset($_SESSION['id'])) {
        header('Location: index.php');
        exit;
    }

    $error = '';
    if ($_POST) {
        include __DIR__ . '/../autoload.php';
        try {
            $userInfo = OurBlog_User::auth($_POST);
            if ($userInfo) {
                session_regenerate_id(true);
                $_SESSION['id']       = $userInfo['id'];
                $_SESSION['username'] = $userInfo['username'];
                header('Location: index.php');
                exit;
            }
            $error = 'Email or Password wrong!';
        } catch (InvalidArgumentException $e) {
            $error = $e->getMessage();
        } catch (Exception $e) {
            $error = 'SERVER ERROR';
        }
    }
?>

<html>
<head>
<meta charset="utf-8">
<title>OurBlog - Sign In</title>
<link rel="stylesheet" href="../style.css">
</head>
<body>

<div class="container">
    <h2>OurBlog Sign In</h2>
    <?php
        if (isset($_GET['reg']) && $_GET['reg'] == 'success') {
            echo '<p class="success">reg success!</p>';
        }
    ?>
    <form method="POST">
        <?php
            if ($error) {
                echo '<p class="error">', htmlspecialchars($error),'</p>';
            }
        ?>
        <label>Email:</label><input type="email" name="email" required><br><br>
        <label>Password:</label><input type="password" name="password" required><br><br>
        <label></label><input type="submit" value="Sign In">
    </form>
</div>

</body>
</html>
