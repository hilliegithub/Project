<?php
session_start();
require_once('connect.php');
$processingError = false;

try {
    // Checks if this is a valid page being requested else redirect to home page.
    if ($_GET && !empty($_GET['id'])) {
        $postid = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        // Minimun valid integer number is 0
        if (filter_var($postid, FILTER_VALIDATE_INT, ["option" => ["min_range" => 1]]) !== false) {
            $query = "SELECT * FROM BikePost WHERE id = :id LIMIT 1";
            $stmt = $db->prepare($query);

            $stmt->bindValue('id', $postid, PDO::PARAM_INT);
            $result = $stmt->execute();
            if (!$result) {
                throw new Exception('Error processing request. Please try again later');
            }

            if ($stmt->rowCount() != 1) {
                throw new Exception('Error processing request. Please try again later');
            }
            $post = $stmt->fetch();

            $getCommentsQuery = "SELECT * FROM comments WHERE BikePostID = :postid ORDER BY date_created DESC";
            $dbObject = $db->prepare($getCommentsQuery);
            $dbObject->bindValue('postid', $postid);
            $dbObject->execute();
            $comments = $dbObject->fetchAll();
            // print_r($post);
        } else {
            throw new Exception('Invalid Post');
        }
    } else {
        header("Location: index.php");
        exit;
    }
} catch (Exception $e) {
    $processingError = true;
    $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $post['make'] ?>
        <?= $post['model'] ?>
    </title>
</head>

<body>
    <main>
        <?php include("navigation.php") ?>
        <div class="post">
            <h1>
                <?= $post['make'] ?>
                <?= $post['model'] ?>
            </h1>
            <?php if ($post['image_url']): ?>
            <img src=<?= $post['image_url'] ?> alt="<?= $post['make'] ?>" width="300px">
            <?php endif ?>
            <div>Make:
                <?= $post['make'] ?>
            </div>
            <div>Model:
                <?= $post['model'] ?>
            </div>
            <div>Year:
                <?= $post['year'] ?>
            </div>
            <div>Engine:
                <?= $post['engine'] ?>
            </div>
            <div>
                <a href="editBikePost.php?id=<?= $post['id'] ?>">
                    Edit This Post
                </a>
            </div>
        </div>
        <div id="comment-section">
            <div class="create-comment">
                <form action="createComment.php" method="post">
                    <textarea id="comment" name="comment" rows="6" cols="40" placeholder="What are your thoughts?"
                        required></textarea>
                    <?php if (!isset($_SESSION['user_id'])): ?>

                    <input type="text" name="commenter" id="commenter" placeholder="Tell us your name" required>
                    <input type="text" name="userid" hidden value="none">

                    <?php else: ?>

                    <input type="text" name="commenter" id="commenter" hidden value="<?= $_SESSION['user'] ?>">
                    <input type="text" name="userid" hidden value="<?= $_SESSION['user_id'] ?>">

                    <?php endif ?>

                    <input type="text" name="postid" hidden value="<?= $post['id'] ?>">
                    <input type="submit" name="submit">
                </form>
            </div>
            <div class="postedComments">
                <?php foreach ($comments as $comment): ?>
                <?php if (($_SESSION['isAdmin'] === 1) && ($comment['hidden'] === 1)): ?>
                <!-- if you are an admin and the comment is deemed hidden still display -->
                <div class="posted-comment" style="border: 1px solid black; margin-bottom: 10px;">
                    <p>
                        <?= $comment['content'] ?>
                    </p>
                    <p>
                        <span>posted by: </span>
                        <?= $comment['commenter'] ?>
                        <?php if (isset($_SESSION['user_id']) && ($_SESSION['isAdmin'] === 1)): ?>
                        <input type="checkbox" id="makehidden" name="makehidden"
                            data-commentid="<?= $comment['commentID'] ?>"
                            <?= $comment['hidden'] === 1 ? 'checked' : '' ?>>
                        <?php endif ?>
                    </p>
                </div>
                <?php else: ?>
                <?php if (($_SESSION['isAdmin'] !== 1) && ($comment['hidden'] === 1)): ?>
                <!-- if you are not an admin and the comment is deemed hidden do not display -->
                <?php else: ?>
                <!-- otherwise it should be good to display -->
                <div class="posted-comment" style="border: 1px solid black; margin-bottom: 10px;">
                    <p>
                        <?= $comment['content'] ?>
                    </p>
                    <p>
                        <span>posted by: </span>
                        <?= $comment['commenter'] ?>
                    </p>
                </div>
                <?php endif ?>
                <?php endif ?>

                <?php endforeach ?>
            </div>
        </div>
    </main>
    <script src=" scripts/comments.js?1"></script>
</body>

</html>