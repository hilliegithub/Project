<?php
session_start();
require_once('connect.php');
$processingError = false;

if (isset($_SESSION['user_id']) && isset($_COOKIE['loginMessage'])) {
    $loginMessage = filter_var($_COOKIE['loginMessage'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
}

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0,shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>
        <?= $post['make'] ?>
        <?= $post['model'] ?>
    </title>
</head>

<body>
    <main>
        <?php if ($loginMessage): ?>
        <div class="alert alert-success alert-dismissible fade show mb-0" role="alert">
            <?= $loginMessage ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php endif ?>
        <?php include("navigation.php") ?>
        <div class="container mt-2">
            <h2>
                <?= $post['make'] ?>
                <?= $post['model'] ?>
            </h2>
            <?php if ($post['image_url']): ?>
            <img class="rounded img-thumbnail" src=<?= $post['image_url'] ?> alt="<?= $post['make'] ?>" width="300px">
            <?php endif ?>
            <div class="input-group mb-3 col-12 col-md-6">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">Make:</span>
                </div>
                <!-- <input type="text" class="form-control" placeholder="Username" aria-label="Username"
                                                                                                                                                                                                                                                                                                                                aria-describedby="basic-addon1"> -->
                <input class="form-control" type="text" placeholder="<?= $post['make'] ?>" readonly>
            </div>
            <div class="input-group mb-3 col-12 col-md-6">
                <div class="input-group-prepend">
                    <span class="input-group-text">Model:</span>
                </div>
                <!-- <input type="text" class="form-control" placeholder="Username" aria-label="Username"
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                aria-describedby="basic-addon1"> -->
                <input class="form-control" type="text" placeholder="<?= $post['model'] ?>" readonly>
            </div>
            <div class="input-group mb-3 col-12 col-md-6">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">Year:</span>
                </div>
                <!-- <input type="text" class="form-control" placeholder="Username" aria-label="Username"
                                                                                                                                                                                                                                                aria-describedby="basic-addon1"> -->
                <input class="form-control" type="text" placeholder="<?= $post['year'] ?>" readonly>
            </div>
            <div class="input-group mb-3 col-12 col-md-6">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">Engine:</span>
                </div>
                <!-- <input type="text" class="form-control" placeholder="Username" aria-label="Username"
                                                                                                                            aria-describedby="basic-addon1"> -->
                <input class="form-control" type="text" placeholder="<?= $post['engine'] ?>" readonly>
            </div>
            <div class="m-2">
                <a href="editBikePost.php?id=<?= $post['id'] ?>">
                    Edit This Post
                </a>
            </div>
            <div id="comment-section">
                <div class="create-comment">
                    <form action="createComment.php" method="post">
                        <textarea id="comment" name="comment" rows="4" cols="35" placeholder="What are your thoughts?"
                            required></textarea>
                        <?php if (!isset($_SESSION['user_id'])): ?>

                        <div class="form-row mt-2">
                            <div class="form-group col-12 col-md-6">
                                <input type="text" name="commenter" id="commenter" class="form-control"
                                    placeholder="Tell us your name" required>
                            </div>
                        </div>
                        <input type="text" name="userid" hidden value="none">

                        <?php else: ?>

                        <!-- Username of the commenter also being passed along -->
                        <input type="text" name="commenter" id="commenter" hidden value="<?= $_SESSION['user'] ?>">
                        <input type="text" name="userid" hidden value="<?= $_SESSION['user_id'] ?>">

                        <?php endif ?>

                        <input type="text" name="postid" hidden value="<?= $post['id'] ?>">
                        <div class="form-row mt-2">
                            <button class="btn btn-primary mt-2" id="submit" name="submit" type="submit">+ Add a
                                Comment</button>
                        </div>
                    </form>
                </div>
                <div class="mt-2">
                    <?php foreach ($comments as $comment): ?>
                    <?php if (($_SESSION['isAdmin'] === 1) && ($comment['hidden'] === 1)): ?>
                    <!-- if you are an admin and the comment is deemed hidden still display -->
                    <div class="mt-2 border border-bottom p-1" style="max-width: 400px;">
                        <p>
                            <em>
                                <?= $comment['content'] ?>
                            </em>
                        </p>
                        <small>
                            posted by:
                            <mark>
                                <?= $comment['commenter'] ?>
                            </mark>
                        </small>
                        <?php if (isset($_SESSION['user_id']) && ($_SESSION['isAdmin'] === 1)): ?>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <input type="checkbox" class="form-control" id="makehidden" name="makehidden"
                                        data-commentid="<?= $comment['commentID'] ?>"
                                        <?= $comment['hidden'] === 1 ? 'checked' : '' ?>>
                                </div>
                            </div>

                            <label for="makehidden" class="form-control">Hide Visibility?</label>
                        </div>
                        <?php endif ?>
                        <button class="btn btn-outline-danger btn-sm mt-2" data-btn-id="<?= $comment['commentID'] ?>"
                            name="delete" type="submit" onclick="return confirm('Are you sure?')">Delete
                            Comment</button>
                    </div>
                    <?php else: ?>
                    <?php if (($_SESSION['isAdmin'] !== 1) && ($comment['hidden'] === 1)): ?>
                    <!-- if you are not an admin and the comment is deemed hidden do not display -->
                    <?php else: ?>
                    <!-- otherwise it should be good to display -->
                    <div class="mt-2 border border-bottom p-1" style="max-width: 400px;">
                        <p>
                            <em>
                                <?= $comment['content'] ?>
                            </em>
                        </p>
                        <small>
                            posted by:
                            <mark>
                                <?= $comment['commenter'] ?>
                            </mark>
                        </small>
                        <?php if (isset($_SESSION['user_id']) && ($_SESSION['isAdmin'] === 1)): ?>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <input type="checkbox" class="form-control" id="makehidden" name="makehidden"
                                        data-commentid="<?= $comment['commentID'] ?>"
                                        <?= $comment['hidden'] === 1 ? 'checked' : '' ?>>
                                </div>
                            </div>

                            <label for="makehidden" class="form-control">Hide Visibility?</label>
                        </div>
                        <?php endif ?>
                        <?php if (isset($_SESSION['user_id']) && ($_SESSION['isAdmin'] === 1)): ?>
                        <button class="btn btn-outline-danger btn-sm mt-2" data-btn-id="<?= $comment['commentID'] ?>"
                            name="delete" type="submit" onclick="return confirm('Are you sure?')">Delete
                            Comment</button>
                        <?php endif ?>
                    </div>
                    <?php endif ?>
                    <?php endif ?>

                    <?php endforeach ?>
                </div>
            </div>
        </div>
    </main>
    <script src=" scripts/comments.js?1"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
</body>

</html>