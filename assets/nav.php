<!-- ######################     Main Navigation   ########################## -->
<nav>
    <ul class="toolBar">
        <li><a href="index.php" <?php if($path_parts['filename'] == "index"){ print 'class="selected"'; } ?>>Home</a></li>
        <li><a href="about.php" <?php if($path_parts['filename'] == "about"){ print 'class="selected"'; } ?>>About</a></li>
        <li><a href="data.php" <?php if($path_parts['filename'] == "data"){ print 'class="selected"'; } ?>>Data</a></li>
        <li><a href="join.php" <?php if($path_parts['filename'] == "join"){ print 'class="selected"'; } ?>>Join</a></li>
        <li><a href="investors.php" <?php if($path_parts['filename'] == "investors"){ print 'class="selected"'; } ?>>Investors</a></li>
        <li><a href="invest.php" <?php if($path_parts['filename'] == "invest"){ print 'class="selected"'; } ?>>Invest</a></li>
    </ul>
</nav>