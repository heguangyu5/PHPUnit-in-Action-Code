<?php

include __DIR__ . '/check-login.php';
include __DIR__ . '/../autoload.php';

$error = '';
if ($_POST) {
    try {
        $post = new OurBlog_Post($_SESSION['id']);
        $post->edit($_POST);
        header('Location: index.php');
        exit;
    } catch (InvalidArgumentException $e) {
        $error = $e->getMessage();
    } catch (Exception $e) {
        $error = 'SERVER ERROR';
    }
}

try {
    if (!isset($_GET['id'])) {
        throw new InvalidArgumentException('missing required key id');
    }
    $id = OurBlog_Post::DBAIPK($_GET['id']);
    if (!$id) {
        throw new InvalidArgumentException('invalid id');
    }
    $postRow = OurBlog_Db::getInstance()->fetchRow("SELECT category_id, title, is_external, content FROM post WHERE id = $id AND user_id = {$_SESSION['id']}");
    if (!$postRow) {
        throw new InvalidArgumentException('id not exists or not your post');
    }
} catch (InvalidArgumentException $e) {
    header('Location: index.php');
    exit;
}

$title = 'Edit Post';
include __DIR__ . '/header.php';

?>

<h2>Edit Post</h2>
<form method="POST">
    <?php
        if ($error) {
            echo '<p class="error">', htmlspecialchars($error),'</p>';
        }
    ?>
    
    <input type="hidden" name="id" value="<?php echo $id; ?>">
    <label>Category:</label><br>
    <select name="categoryId">
        <option value="">--Select--</option>
        <?php
            foreach ($categoryRows as $row) {
                echo '<option value="', $row['id'], '"', ($row['id'] == $postRow['category_id'] ? ' selected' : ''), '>', $row['name'], '</option>';
            }
        ?>
    </select>
    <br><br>
    
    Title:<br>
    <input type="text" name="title" style="width:80%" value="<?php echo htmlspecialchars($postRow['title']); ?>">
    <br><br>
    External Post? <input type="checkbox" name="external" value="1"<?php echo $postRow['is_external'] ? ' checked' : ''; ?>>
<br><br>
    Content:<br>
    <textarea name="content" rows="20" cols="100"><?php echo htmlspecialchars($postRow['content']); ?></textarea>
    <br><br>
    <?php
        $sql = "SELECT  t.name
                FROM    post_tag pt
                        INNER JOIN tag t ON pt.tag_id = t.id
                WHERE
                        pt.post_id = $id
                        ORDER BY pt.id";
        $tags = OurBlog_Db::getInstance()->fetchCol($sql);
        $tags = implode(',', $tags);
    ?>
    Tags (multiple tags separated by ,):<br>
    <input type="text" name="tags" style="width: 80%" value="<?php echo htmlspecialchars($tags); ?>">
    <br><br>
    
    <input type="submit" value="Save">
</form>
