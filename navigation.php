<nav>
    <ul>
        <li><a href="index.php">Home</a></li>
        <?php if (!isset($_SESSION['user_id'])): ?>
        <li><a href="register.php">Register</a></li>
        <?php endif ?>
        <li><a href="createPost.php">Create Post</a></li>
        <?php if (!isset($_SESSION['user_id'])): ?>
        <li><a href="login.php">Login</a></li>
        <?php else: ?>
        <li><a href="logout.php">Logout</a></li>
        <?php endif ?>
        <?php if (isset($_SESSION['user_id']) && ($_SESSION['isAdmin'] === 1)): ?>
        <li><a href="manageUsers.php">Manage Users</a></li>
        <?php endif ?>
    </ul>
</nav>