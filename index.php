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

    <div class="main">
      <div class="content">
        <p class="slogan">Sharing <span>ideas</span> <br> Sparking <span>creativity</span> <br> Inspiring
          <span>minds</span>
        </p>
        <a href="create_post.html"><button>Get started</button></a>
      </div>
    </div>
    <div class="recent">
      <h2>Discover recent posts</h2>

      <div class="container">

        <?php

        // $db = new PDO("sqlite:blog.db");
        
        // $stmt = $db->prepare("DELETE FROM posts WHERE id >= 4");
        // $stmt->execute();
        
        // $db = null;
        
        include 'funcs.php';

        $db = new PDO("sqlite:blog.db");



        // $comments = [
        //   ['post_id' => 2, 'author_name' => 'Sarah', 'content' => 'Great perspective, thanks for sharing!'],
        //   ['post_id' => 2, 'author_name' => 'Chris', 'content' => 'I appreciate your honesty and transparency.'],
        //   ['post_id' => 3, 'author_name' => 'David', 'content' => 'Very well written, I enjoyed reading this.'],
        //   ['post_id' => 4, 'author_name' => 'Olivia', 'content' => 'This post resonated with me deeply, thank you.'],
        //   ['post_id' => 4, 'author_name' => 'Emily', 'content' => 'I couldn\'t agree more with your points.']
        // ];

        // foreach ($comments as $comment) {
        //   $stmt = $db->prepare('INSERT INTO comments (post_id, author_name, content) VALUES (:post_id, :author_name, :content)');
        //   $stmt->bindParam(':post_id', $comment['post_id']);
        //   $stmt->bindParam(':author_name', $comment['author_name']);
        //   $stmt->bindParam(':content', $comment['content']);
        //   $stmt->execute();
        // }




        $stmt = $db->prepare('SELECT * FROM posts');
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $last_three_posts = array_slice($rows, -3);

        foreach ($last_three_posts as $row) {
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
    </div>

  </body>

  </html>

</body>

</html>