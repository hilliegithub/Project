<nav>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="register.php">Register</a></li>
        <li><a href="createPost.php">Create Post</a></li>
        <?php if (!isset($_SESSION['user_id'])): ?>
        <li><a href="login.php">Login</a></li>
        <?php else: ?>
        <li><a href="logout.php">Logout</a></li>
        <?php endif ?>
    </ul>
</nav>