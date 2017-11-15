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
    } // ends if file was opened 
?>

<!-- Header Loader -->
<?php
    include ("assets/completeTop.php");
?>

<!-- Page Content -->
<content>
    <!-- Top Bar -->
    <div class="backgroundPanel">
        <h1 class="investorStock">Clean Burning Black Rock Inc. (CBBR) | <span id="stockPrice">$243.74</span> | <span id="stockPercentage" class="greenText">+2.31%</span></h1>
    </div>

    <!-- Bottom Boxes -->
    <div class="flexBoxContainer">
        <div class="backgroundPanel investorsFlexBox">
            <h1 class="bpHeader">News</h1>
            <ol class="contentList">
                <?php
                    foreach($data as $row) {
                        print '<li>';
                        print '<h3><a href="#">'.$row[0].'</a></h3>'; // STILL NEED TO DO THE LINK!
                        print '<p>'. str_replace("/COMMA/",",",substr($row[1],0,300)).'...</p>';
                        print '</li>';
                    }
                ?>

                <!-- 
                <li>
                    <h3><a href="#">Article Header</a></h3>
                    <p>Some text for each item about some stuff.</p>
                </li>
                -->
            </ol>
            <p>That's all we've got so far!</p>
        </div>

        <div class="backgroundPanel investorsFlexBox">
            <h1 class="bpHeader">Investor Info</h1>
            <ol class="contentList">
                <li>
                    <h3>Name Of Investor</h3>
                    <p>Short Description. Net Worth: $0.00 Billion</p>
                </li>
            </ol>
            <p>That's all we've got so far!</p>
        </div>
    </div>
</content>

<!-- Footer Loader -->
<?php 
    include ("assets/footer.php"); 
?>