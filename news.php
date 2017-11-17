<!-- Data Loader -->
<?php 
    // Open a CSV file
    $debug = false;
    if(isset($_GET["debug"])){
         $debug = true; 
    }

    $filename = "data/news.csv";

    if ($debug) print '<p>filename is ' . $filename;

    $file=fopen($filename, "r");

    if($debug){
        if($file){
           print '<p>File Opened Succesful.</p>';
        }else{
           print '<p>File Open Failed.</p>';
        }
    } 
?>

<!-- Read CSV -->
<?php 
    if($file){
        if($debug) print '<p>Begin reading data into an array.</p>';

        // read the header row, copy the line for each header row
        // you have.
        $headers[] = fgetcsv($file);

        if($debug) {
            print '<p>Finished reading headers.</p>';
            print '<p>My header array</p><pre>';
            print_r($headers);
            print '</pre>';
        }

        // read all the data
        while(!feof($file)){
            $data[] = fgetcsv($file);
        }

        if($debug) {
            print '<p>Finished reading data. File closed.</p>';
            print '<p>My data array<p><pre> ';
            print_r($data);
            print '</pre></p>';
        }
        
        fclose($file);

        /// Only select the data we need
        // Ensure that we've got an article index
        if(isset($_GET["article"])){
            // Get Index
            $index = $_GET["article"];

            // Pull out selected data
            $selectedData[] = $data[$index];

            // Do the debug
            if($debug) {
                print '<p>Selected a piece of data.</p>';
                print '<p>Selected data array<p><pre> ';
                print_r($selectedData);
                print '</pre></p>';
            }
        }
    } // ends if file was opened 
?>

<!-- Header Loader -->
<?php
    include ("assets/completeTop.php");
?>

<!-- Page Content -->
<content id="newsPage">
    <!-- Go Back Button -->
    <h3 class="newsGoBack"><a href="investors.php">Go Back</a></h3>

    <!-- Top Bar -->
    <div class="backgroundPanel">
        <h1><?php print $selectedData[0][0]; ?></h1>
        <h4>Source: 
            <?php
                // Variables
                $maxLength = 140;

                // Check to see if over character limit
                $inText = $selectedData[0][2];
                if(strlen($inText) >= $maxLength) {
                    // It's longer, trim it
                    $outText = '<span title="'.$inText.'">'.substr($inText,0,$maxLength).'...</span>';
                } else {
                    // It's shorter, send it
                    $outText = $inText;
                }

                // Print the data
                print $outText;
            ?>
        </h4>
    </div>

    <!-- Middle Block -->
    <div class="backgroundPanel">
        <p><?php print str_replace("/COMMA/",",",$selectedData[0][1]); ?>...</p>
        <br>
        <a href=<?php print '"'.$selectedData[0][2].'"'; ?>>Read More Here</a>
    </div>
</content>

<!-- Footer Loader -->
<?php 
    include ("assets/footer.php"); 
?>