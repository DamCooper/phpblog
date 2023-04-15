<?php
if (isset($_GET['id'])) {
    $db = new PDO("sqlite:blog.db");
    $id = $_GET['id'];
    $post_id = $_GET['post_id'];

    $stmt = $db->prepare('DELETE FROM comments WHERE id = ?');
    $stmt->execute([$id]);

    header('Location: /blog/post.php?id=' . $post_id);

    $db = null;
    exit;
}
?>