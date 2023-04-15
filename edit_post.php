<!DOCTYPE html>
<html>

<head>
    <title>Blog Post</title>
    <link rel="stylesheet" href="static/css/editpost.css">
    <link rel="stylesheet" href="static/css/styles.css">
    <script src="https://kit.fontawesome.com/a9515ed9ed.js" crossorigin="anonymous"></script>
</head>

<body>

    <nav>
        <div class="logo"><a href="/blog">Blog</a></div>
        <ul>
            <li><a href="#">About</a></li>
            <li><a href="posts.php">Posts</a></li>
            <li><a href="#">Contact</a></li>
        </ul>
    </nav>
    <?php
    include 'funcs.php';

    $db = new PDO("sqlite:blog.db");
    $post_id = $_GET['id'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $title = $_POST['title'];
        $category = $_POST['category'];
        $author_name = $_POST['author_name'];
        $content = $_POST['content'];

        $stmt = $db->prepare('SELECT photo FROM posts WHERE id = :post_id');
        $stmt->bindParam(':post_id', $post_id);
        $stmt->execute();
        $row = $stmt->fetch();
        $old_photo_path = $row['photo'];

        if (!empty($_FILES['photo']['name'])) {
            $photo_name = $_FILES['photo']['name'];
            $photo_path = "static/img/" . basename($photo_name);
            move_uploaded_file($_FILES['photo']['tmp_name'], $photo_path);
        } else {
            $photo_path = $old_photo_path;
        }

        $stmt = $db->prepare('UPDATE posts SET title = :title, category = :category, author_name = :author_name, photo = :photo_path, content = :content WHERE id = :post_id');
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':author_name', $author_name);
        $stmt->bindParam(':photo_path', $photo_path);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':post_id', $post_id);
        $stmt->execute();

        header('Location: /blog/post.php?id=' . $post_id);
    }



    $post_id = $_GET['id'];
    $stmt = $db->prepare('SELECT * FROM posts WHERE id = :id');
    $stmt->bindParam(':id', $post_id);
    $stmt->execute();
    $post = $stmt->fetch();

    echo '<form method="post" enctype="multipart/form-data" >';
    echo '<h2>Edit post</h2>';

    echo '<input type="text" id="title" name="title" value="' . $post['title'] . '">';

    echo '<img src="' . $post['photo'] . '" alt="post image">';
    echo '<input type="file" id="photo" name="photo" class="post-input">';

    echo '<input type="text" id="category" name="category" value="' . $post['category'] . '">';

    echo '<input type="text" id="author_name" name="author_name" value="' . $post['author_name'] . '">';

    echo '<textarea id="content" name="content">' . $post['content'] . '</textarea>';

    echo '<button type="submit">Save changes</button>';
    echo '</form>';

    $db = null;


    ?>
</body>

</html>