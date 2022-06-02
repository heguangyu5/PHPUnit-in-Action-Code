<?php

include __DIR__ . '/check-login.php';
include __DIR__ . '/../autoload.php';

$error = '';
if ($_FILES) {
    try {
        $upload = new OurBlog_Upload($_SERVER['DOCUMENT_ROOT'] . '/upload', $_SESSION['id']);
        $filename = $upload->upload();
        echo "Upload success! file saved as $filename.";
        exit;
    } catch (InvalidArgumentException $e) {
        die($e->getMessage());
    } catch (Exception $e) {
        die('SERVER ERROR');
    }
}

?>

<h2>Upload File</h2>
<form enctype="multipart/form-data" method="POST">
    <input name="file" type="file">
    <input type="submit" value="Upload">
</form>
