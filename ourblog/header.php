<?php
    if (!isset($title)) {
        exit;
    }
    
    session_start();
?>

<html>
<head>
<meta charset="utf-8">
<title><?php echo $title; ?> - OurBlog</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <div class="nav-user">
        <?php if (isset($_SESSION['id'])): ?>
        <a href="admin/index.php"><?php echo htmlspecialchars($_SESSION['username']); ?></a>
        <?php else: ?>
        <a href="reg.php">Sign Up</a>
        <a href="admin/login.php">Sign In</a>
        <?php endif; ?>
    </div>
    <div class="nav-links">
        <a href="index.php">Home</a>
        <?php
            $categoryRows = OurBlog_Db::getInstance()->fetchAll('SELECT id, name FROM category');
            foreach ($categoryRows as $row) {
                echo '<a href="index.php?categoryId=', $row['id'], '">', $row['name'], '</a>';
            }
        ?>
    </div>
