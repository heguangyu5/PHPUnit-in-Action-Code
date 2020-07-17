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
</div>

</body>
</html>
