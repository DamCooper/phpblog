<?php
if (isset($_GET['id'])) {
    $db = new PDO("sqlite:blog.db");
    $id = $_GET['id'];

    $stmt = $db->prepare('DELETE FROM posts WHERE id = ?');
    $stmt->execute([$id]);

    header('Location: /blog');
    $db = null;
    exit;
}
?>