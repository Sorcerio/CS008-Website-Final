<!-- ######################     Page header   ############################## -->
<header>
    <!-- Header Text | Only shown on index.php -->
    <?php
        if ($path_parts['filename'] == "index") {
            print '<h1 id="headerTitle">Clean Burning Black Rocks Inc.</h1>';
        }
    ?>

    <!-- Generic CBBR Image -->
    <img src="images/cbbr.png" alt="A Clean Burning Black Rock TM" id="headerImage">
</header>