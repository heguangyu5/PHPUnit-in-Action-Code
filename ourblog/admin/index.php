<?php

include __DIR__ . '/check-login.php';
include __DIR__ . '/../autoload.php';

$title = 'Admin';
include __DIR__ . '/header.php';

if (isset($_GET['categoryId'])) {
    $categoryId = filter_var($_GET['categoryId'], FILTER_VALIDATE_INT, array(
        'options' => array('min_range' => 1)
    ));
} else {
    $categoryId = 0;
}

$sql = "SELECT id, title FROM post WHERE user_id = " . $_SESSION['id'];
if ($categoryId) {
    $sql .= " AND category_id = $categoryId";
}

$postRows = OurBlog_Db::getInstance()->fetchAll($sql);
?>

<table>
    <thead>
        <tr>
            <th width="50">ID</th>
            <th>Title</th>
            <th width="150">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($postRows as $row): ?>
        <tr>
            <td align="center"><?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['title']); ?></td>
            <td align="center">
                <a href="edit.php?id=<?php echo $row['id']; ?>">Edit</a>
                <a href="delete.php?id=<?php echo $row['id']; ?>">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include __DIR__ . '/footer.php'; ?>
