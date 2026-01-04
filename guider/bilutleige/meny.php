
<nav style="background-color: rgba(0, 123, 255, 1);">
    <a href="index.php">Heim</a>
    <?php if(isset($_SESSION['brukar_id'])): ?>
        <a href="minside.php">Mi Side</a>
    <?php if(isset($_SESSION['rolle']) && $_SESSION['rolle'] == 'admin'): ?>
            <a href="admin.php" style="color: #ff9900;">ADMIN PANEL</a>
    <?php endif; ?>
        <a href="loggut.php">Logg ut (<?php echo htmlspecialchars($_SESSION['namn']); ?>)</a>
    <?php else: ?>
        <a href="logginn.php">Logg inn</a>
        <a href="registrer.php">Registrer deg</a>
    <?php endif; ?>
</nav>