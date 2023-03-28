<nav class="navbar navbar-expand-md navbar-light bg-light">
    <a class="navbar-brand" href="index.php">Home</a>
    <?php if (isset($_SESSION['user_id'])): ?>
    <div>Hello
        <?= $_SESSION['user'] ?>!
    </div>
    <?php endif ?>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup"
        aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav">
            <?php if (!isset($_SESSION['user_id'])): ?>
            <a class="nav-item nav-link active" href="register.php">Register <span class="sr-only">(current)</span></a>
            <?php endif ?>
            <a class="nav-item nav-link" href="allPosts.php">All Posts</a>
            <a class="nav-item nav-link" href="createPost.php">Create Post</a>
            <?php if (!isset($_SESSION['user_id'])): ?>
            <a class="nav-item nav-link" href="login.php">Login</a>
            <?php else: ?>
            <a class="nav-item nav-link" href="logout.php">Logout</a>
            <?php endif ?>
            <?php if (isset($_SESSION['user_id']) && ($_SESSION['isAdmin'] === 1)): ?>
            <a class="nav-item nav-link disabled" href="manageUsers.php">Manage Users</a>
            <?php endif ?>
        </div>
    </div>
</nav>