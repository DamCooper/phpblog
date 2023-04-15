<!DOCTYPE html>
<html>

<head>
    <title>Blog Post</title>
    <link rel="stylesheet" href="static/css/post.css">
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
    $stmt = $db->prepare("SELECT * FROM posts WHERE id = :post_id");
    $stmt->bindParam(':post_id', $post_id);
    $stmt->execute();
    $row = $stmt->fetch();
    echo '<div class="title-container">';
    echo '<h2>' . $row['title'] . '</h2>';
    echo '<div class="modify">';
    echo '<a href="/blog/edit_post.php?id=' . $post_id . '">' . '<i class="fa-solid fa-pen"></i>Edit post</a>';
    echo '<a href="/blog/delete_post.php?id=' . $post_id . '">' . '<i class="fa-solid fa-trash"></i>Delete post</a>';
    echo '</div>';
    echo '</div>';

    echo '<img src="' . $row['photo'] . '" alt="post image">';
    echo '<p>' . $row['content'] . '</p>';
    echo '<div class="info">';
    echo '<p>Category: ' . $row['category'] . '</p>';
    echo '<p>Author: ' . $row['author_name'] . '</p>';
    echo '<p>Published: ' . human_time_diff(strtotime($row['created_at']), time()) . '</p>';
    echo '</div>';

    ?>
    <form class="addCommentForm" enctype="multipart/form-data" action="add_comment.php" method="POST">
        <h3 style="padding-bottom:20px; text-align: center;">Add Comment</h3>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
        <br>
        <label for="photo" class="post-label">Photo:</label>
        <input type="file" id="photo" name="author_photo" class="post-input">
        <br>
        <label for="content">Comment:</label>
        <textarea id="content" name="content" required></textarea>
        <br>
        <?php 
        echo '<input type="hidden" name="post_id" value="'. $post_id .'">';
        ?>
        <button type="submit" value="Submit">Submit</button>
    </form>

    <?php
    $comments_stmt = $db->prepare("SELECT * FROM comments WHERE post_id = :post_id ORDER BY created_at DESC");
    $comments_stmt->bindParam(':post_id', $post_id);
    $comments_stmt->execute();
    $comments = $comments_stmt->fetchAll();
    echo '<div class="comments">';

    echo '<h3>Comments</h3>';

    if (count($comments) == 0) {
        echo '<p>No comments yet.</p>';
    } else {
        $i = 0;
        foreach ($comments as $comment) {
            echo '<div class="comment">';
            echo '<div class="img">';
            echo '<img src="' . $comment['author_photo'] . '">';
            echo '</div>';

            echo '<form id="editCommentForm' . $i . '" method="post" action="edit_comment.php">';
            echo '<div class="modify">';
            echo '<input name="comment_id" type="hidden" value="' . $comment['id'] . '">';
            echo '<input name="post_id" type="hidden" value="' . $post_id . '">';
            // echo '<input type="submit">';
            echo '<a class="edit" onclick="editComment(' . $i . ')">' . '<i class="fa-solid fa-pen"></i>Edit</a>';
            echo '<a class="trash" href="/blog/delete_comment.php?id=' . $comment['id'] . '&post_id='. $post_id .'">' . '<i class="fa-solid fa-trash"></i>Delete</a>';
            echo '</div>';


            echo '<p><strong>' . $comment['author_name'] . '</strong> said:</p>';
            echo '<textarea name="new_content">' . $comment['content'] . '</textarea>';
            echo '</form>';
            echo "<small>" . human_time_diff(strtotime($comment['created_at']), time()) . "</small>";
            echo '</div>';
            $i = $i + 1;
        }
    }
    echo '</div>';




    $db = null;
    ?>


    <script>
        const comments = document.querySelectorAll("textarea");
        const edits = document.querySelectorAll(`a[class="edit"`);


        for (let i = 0; i < comments.length; i++) {
            let comm = comments[i];
            let edit = edits[i];



            edit.addEventListener('mousedown', e => {

                if (edit.textContent == 'Save') {
                    let form = document.querySelector('#editCommentForm' + i)
                    // console.log(form);
                    form.submit();
                }
            });

            comm.addEventListener('focus', e => {
                edit.innerHTML = '<i class="fa-solid fa-floppy-disk"></i>Save';
            });
            comm.addEventListener('focusout', e => {
                edit.innerHTML = '<i class="fa-solid fa-pen"></i>Edit';
            });
        }
        function editComment(id) {
            let comment = comments[parseInt(id)];
            comment.focus();
        }
    </script>
</body>

</html>