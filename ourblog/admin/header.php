<?php
    if (!isset($title)) {
        exit;
    }
?>

<html>
<head>
<meta charset="utf-8">
<title><?php echo $title; ?> - OurBlog</title>
<link rel="stylesheet" href="../style.css">
</head>
<body>

<div class="container">
    <div class="nav-user">
        <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
        <a href="add.php">Write Post</a>
        <a href="logout.php">Logout</a>
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
