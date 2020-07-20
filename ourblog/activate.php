<?php

include __DIR__ . '/autoload.php';
try {
    OurBlog_User::activate($_GET);
    header('Location: admin/login.php?activate=success');
    exit;
} catch (InvalidArgumentException $e) {
    die($e->getMessage());
} catch (Exception $e) {
    die('SERVER ERROR');
}
