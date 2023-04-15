<?php
$db = new PDO('sqlite:blog.db');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $comment_id = $_POST['comment_id'];
    $new_content = $_POST['new_content'];
    $post_id = $_POST['post_id'];

    $stmt = $db->prepare('UPDATE comments SET content = :new_content WHERE id = :comment_id');
    $stmt->bindParam(':new_content', $new_content);
    $stmt->bindParam(':comment_id', $comment_id);
    $stmt->execute();
    header('Location: /blog/post.php?id=' . $post_id);
    exit();
}

$comment_id = $_GET['id'];
$stmt = $db->prepare('SELECT * FROM comments WHERE id = :comment_id');
$stmt->bindParam(':comment_id', $comment_id);
$stmt->execute();
$comment = $stmt->fetch();
?>