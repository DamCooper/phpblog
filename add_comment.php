<?php
$db = new PDO('sqlite:blog.db');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $post_id = $_POST['post_id'];
    $author_name = $_POST['name'];
    $content = $_POST['content'];

    $photo_name = $_FILES['author_photo']['name'];
    $photo_path = "static/img/" . basename($photo_name);
    move_uploaded_file($_FILES['author_photo']['tmp_name'], $photo_path);

    $stmt = $db->prepare('INSERT INTO comments (post_id, author_name, content, author_photo) VALUES (:post_id, :author_name, :content, :photo_path)');
    $stmt->bindParam(':post_id', $post_id);
    $stmt->bindParam(':author_name', $author_name);
    $stmt->bindParam(':content', $content);
    $stmt->bindParam(':photo_path', $photo_path);
    $stmt->execute();

    header('Location: /blog/post.php?id=' . $post_id);
}
?>