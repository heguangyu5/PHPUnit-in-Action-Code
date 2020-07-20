<?php

include __DIR__ . '/check-login.php';
include __DIR__ . '/../autoload.php';

$error = '';
if ($_POST) {
    try {
        $post = new OurBlog_Post($_SESSION['id']);
        $post->add($_POST);
        header('Location: index.php');
        exit;
    } catch (InvalidArgumentException $e) {
        $error = $e->getMessage();
    } catch (Exception $e) {
        $error = 'SERVER ERROR';
    }
}

$title = 'Write Post';
include __DIR__ . '/header.php';

?>

<h2>Write Post</h2>
<form method="POST">
    <?php
        if ($error) {
            echo '<p class="error">', htmlspecialchars($error),'</p>';
        }
    ?>
    
    <label>Category:</label><br>
    <select name="categoryId">
        <option value="">--Select--</option>
        <?php
            foreach ($categoryRows as $row) {
                echo '<option value="', $row['id'], '">', $row['name'], '</option>';
            }
        ?>
    </select>
    <br><br>
    
    Title:<br>
    <input type="text" name="title" style="width:80%">
    <br><br>
    Content:<br>
    <textarea name="content" rows="20" cols="100"></textarea>
    <br><br>
    Tags (multiple tags separated by ,):<br>
    <input type="text" name="tags" style="width: 80%">
    <br><br>
    
    <input type="submit" value="Submit">
</form>
