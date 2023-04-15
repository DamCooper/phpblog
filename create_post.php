<?php
$db = new PDO("sqlite:blog.db");

$title = $_POST['title'];
$content = $_POST['content'];
$category = $_POST['category'];
$author_name = $_POST['author-name'];
$random_id = rand(1, 100);
$author_photo = "https://i.pravatar.cc/40?img={$random_id}";


$photo_name = $_FILES['photo']['name'];
$photo_path = "static/img/" . basename($photo_name);
if (move_uploaded_file($_FILES['photo']['tmp_name'], $photo_path)) {
    echo "The file ". htmlspecialchars(basename($_FILES["photo"]["name"])). " has been uploaded.";
} else {
    echo "Sorry, there was an error uploading your file.";
}


$sql = "INSERT INTO posts (title, content, photo, category, author_name, author_photo) 
        VALUES (:title, :content, :photo, :category, :author_name, :author_photo)";
$stmt = $db->prepare($sql);


$stmt->bindParam(':title', $title);
$stmt->bindParam(':content', $content);
$stmt->bindParam(':photo', $photo_path);
$stmt->bindParam(':category', $category);
$stmt->bindParam(':author_name', $author_name);
$stmt->bindParam(':author_photo', $author_photo);

if ($stmt->execute()) {
    echo 'Post created successfully!';
} else {
    echo 'Error creating post: ' . $stmt->errorInfo()[2];
}
$db = null;

?>