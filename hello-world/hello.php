<html>
<head>
<meta charset="utf-8">
</head>
<body>

<?php

if ($_GET) {
    include __DIR__ . '/func.php';
    echo sayHello($_GET);
}

?>

<form>
    Input your name: <input type="text" name="username">
    <input type="submit" value="Submit">
</form>

</body>
</html>
