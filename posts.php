<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog</title>
    <link rel="stylesheet" href="static/css/styles.css">
    <link rel="stylesheet" href="static/css/index.css">
</head>

<body>
    <!DOCTYPE html>
    <html>

    <head>
        <title>My Blog System</title>
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

        <h2 style="text-align:center; padding-top: 10px;">All posts</h2>

        <div>
            <label for="category">Filter by category:</label>
            <select id="category" name="category">
                <option value="">All</option>
                <?php
                $db = new PDO("sqlite:blog.db");
                $stmt = $db->prepare('SELECT DISTINCT category FROM posts');
                $stmt->execute();
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($rows as $row) {
                    $category = $row['category'];
                    echo "<option value='$category'>$category</option>";
                }

                $db = null;
                ?>
            </select>
            <a id="categoryAnchor" href="posts.php?category="><button type="submit">Filter</button></a>
        </div>


        <div class="container">

            <?php

            // $db = new PDO("sqlite:blog.db");
            
            // $stmt = $db->prepare("DELETE FROM posts WHERE id >= 4");
            // $stmt->execute();
            
            // $db = null;
            
            include 'funcs.php';
            $db = new PDO("sqlite:blog.db");

            $category = isset($_GET['category']) ? $_GET['category'] . "%" : '';
            if (!empty($category)) {
                $stmt = $db->prepare('SELECT * FROM posts WHERE category LIKE :category');
                $stmt->bindParam(':category', $category);
            } else {
                $stmt = $db->prepare('SELECT * FROM posts');
            }

            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($rows as $row) {
                echo '<a href="/blog/post.php?id=' . $row['id'] . '" class="card-link">';
                echo '<div class="card">';
                echo '<div class="card__header">';
                echo '<img src="' . $row['photo'] . '" alt="card__image" class="card__image" width="600">';
                echo '</div>';
                echo '<div class="card__body">';
                echo '<span class="tag tag-red">' . $row['category'] . '</span>';
                echo '<h4>' . $row['title'] . '</h4>';
                $content = $row['content'];

                if (strlen($content) > 40) {
                    $content = substr($content, 0, 99) . '...';
                }

                echo '<p>' . $content . '</p>';
                // echo '<p>' . $row['content'] . '</p>';
                echo '</div>';
                echo '<div class="card__footer">';
                echo '<div class="user">';
                echo '<img src="' . $row['author_photo'] . '" alt="user__image" class="user__image">';
                echo '<div class="user__info">';
                echo '<h5>' . $row['author_name'] . '</h5>';
                echo "<small>" . human_time_diff(strtotime($row['created_at']), time()) . "</small>";


                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '</a>';
            }

            $db = null;
            ?>
        </div>
        <script>
            let anchor = document.querySelector("#categoryAnchor");
            anchor.addEventListener('click', e => {
                let category = document.querySelector("#category");
                anchor.setAttribute('href', anchor.getAttribute('href') + category.value);
            });
        </script>
    </body>

    </html>

</body>

</html>