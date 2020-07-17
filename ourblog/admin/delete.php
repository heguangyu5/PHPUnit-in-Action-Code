<?php

include __DIR__ . '/check-login.php';
include __DIR__ . '/../autoload.php';

try {
    $post = new OurBlog_Post($_SESSION['id']);
    $post->delete($_GET);
    header('Location: index.php');
} catch (InvalidArgumentException $e) {
    die($e->getMessage());
} catch (Exception $e) {
    die('SERVER ERROR');
}
