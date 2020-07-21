<?php

include __DIR__ . '/autoload.php';

try {
    if (!isset($_GET['id'])) {
        throw new InvalidArgumentException('missing required key id');
    }
    $id = OurBlog_Post::DBAIPK($_GET['id']);
    if (!$id) {
        throw new InvalidArgumentException('invalid id');
    }
    $sql = "SELECT  p.title,
                    p.is_external,
                    p.content,
                    p.create_date,
                    u.username
            FROM    post p
                    INNER JOIN user u ON p.user_id = u.id
            WHERE
                    p.id = $id";
    $post = OurBlog_Db::getInstance()->fetchRow($sql);
    if (!$post) {
        throw new InvalidArgumentException('id not exists');
    }
    if ($post['is_external']) {
        header('Location: ' . $post['content']);
        exit;
    }
} catch (InvalidArgumentException $e) {
    header('Location: index.php');
    exit;
}

$title = htmlspecialchars($post['title']);
include __DIR__ . '/header.php';

?>

<h3><?php echo $title; ?></h3>
<p class="post-meta">
    <?php echo htmlspecialchars($post['username']); ?> created at <?php echo $post['create_date']; ?>
</p>
<div class="post-content"><?php echo htmlspecialchars($post['content']); ?></div>

<?php include __DIR__ . '/footer.php'; ?>
