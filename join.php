<!-- Header Loader -->
<?php
    include ("assets/completeTop.php");
?>

<!-- Background Panel Start -->
<div class="backgroundPanel">

<!-- Page Content -->
<?php
    // 1: Initialize Variables
    // Debug
    if($debug) {
        print '<p>Post Array:</p><pre>';
        print_r($_POST);
        print '</pre>';
    }

    // Security
    $thisURL = $domain.$phpSelf;

    // Forum Variables
    $firstName = "";
    $lastName = "";
    $email = "youremail@uvm.edu";
    $donationLevel = "None (+$0.00)";
    $animals = false;
    $plants = false;
    $biosphere = false;
    $gender = "Male";

    // Forum Error Flags
    $firstNameERROR = false;
    $lastNameERROR = false;
    $emailERROR = false;
    $donationLevelERROR = false;
    $donationTypeERROR = false;
    $totalChecked = 0;
    $genderERROR = false;

    // Misc Variables
    $errorMsg = array();
    $dataRecord = array();
    $mailed = false;

    // 2: Process Submitted Forum
    if(isset($_POST["btnSubmit"])) {

        // Security
        if(!securityCheck($thisURL)) {
            $msg = '<p>Sorry you cannot access this page. ';
            $msg.= 'Security breach detected and reported.</p>';
            die($msg);
        }
        
        // Sanitize/Clean Data
        $firstName = htmlentities($_POST["txtFirstName"], ENT_QUOTES, "UTF-8");
        $lastName = htmlentities($_POST["txtLastName"], ENT_QUOTES, "UTF-8");
        $email = filter_var($_POST["txtEmail"], FILTER_SANITIZE_EMAIL);
        $donationLevel = htmlentities($_POST["lstDonationLevel"], ENT_QUOTES, "UTF-8");

        if(isset($_POST["chkAnimals"])) {
            $animals = true;
            $totalChecked++;
        } else {
            $animals = false;
        }

        if(isset($_POST["chkPlants"])) {
            $plants = true;
            $totalChecked++;
        } else {
            $plants = false;
        }

        if(isset($_POST["chkBiosphere"])) {
            $biosphere = true;
            $totalChecked++;
        } else {
            $biosphere = false;
        }

        $gender = htmlentities($_POST["radGender"], ENT_QUOTES, "UTF-8");

        // Add Data
        $dataRecord[] = $firstName;
        $dataRecord[] = $lastName;
        $dataRecord[] = $email;
        $dataRecord[] = $donationLevel;
        $dataRecord[] = $animals;
        $dataRecord[] = $plants;
        $dataRecord[] = $biosphere;
        $dataRecord[] = $gender;

        // Validation
        if($firstName == "") {
            $errorMsg[] = "Please enter your first name.";
            $firstNameERROR = true;
        }

        if($lastName == "") {
            $errorMsg[] = "Please enter your last name.";
            $lastNameERROR = true;
        }

        if($email == "") {
            $errorMsg[] = "Please enter your email address.";
            $emailERROR = true;
        } else if(!verifyEmail($email)) {
            $errorMsg[] = "Your email address is not valid.";
            $emailERROR = true;
        }

        if($donationLevel == "") {
            $errorMsg[] = "Please choose a donation level.";
            $donationLevelERROR = true;
        }

        if($totalChecked < 1) {
            $errorMsg[] = "Please choose at least one focus.";
            $donationTypeERROR = true;
        }

        if($gender != "Male" AND $gender != "Female" AND $gender != "Other") {
            $errorMsg[] = "Please choose a gender";
            $genderERROR = true;
        }

        // Process Forum (Passed Validation)
        if(!$errorMsg) {
            if($debug) {
                print PHP_EOL.'<p>Form is valid.</p>';
            }

            // Save Data
            $myFolder = "data/";
            $myFileName = "registration";
            $fileExt = ".csv";

            $filename = $myFolder.$myFileName.$fileExt;

            $file = fopen($filename, "a");

            fputcsv($file, $dataRecord);

            fclose($file);

            // Create Message
            $message = "<h2>Your information.</h2>";

            foreach($_POST as $htmlName => $value) {
                print $value;
                print $htmlName;
                
                $message .= "<p>";

                $camelCase = preg_split('/(?=[A-Z])/',substr($htmlName, 3));

                foreach($camelCase as $oneWord) {
                    $message .= $oneWord." ";
                }
                
                $message .= " = ".htmlentities($value,ENT_QUOTES,"UTF-8")."</p>";
            }

            // Mail to User
            $to = $email;
            $cc = "";
            $bcc = "";

            $from = "customer.service@saveNatureBecauseItsGreatInc.com";

            $subject = "Changeing Earth: ";

            $mailed = sendMail($to,$cc,$bcc,$from,$subject,$message);
        }
    }
?>
    

<!-- Page Content Start -->

<article id="main">
    <?php
        // 3: Display Forum
        // Show errors or the forum
        if(isset($_POST["btnSubmit"]) AND empty($errorMsg)) {
            print '<h2>Thank you for providing your information</h2>';
            print '<p>For your records a copy of this data has ';

            if(!$mailed) {
                print "not ";
            }
            print 'been sent</p>';

            print '<p>To: '.$email.'</p>';

            print $message;
        } else { // ELSE IS CLOSED BELOW
            print '<h2>Register for Our News Letter Today!</h2>';
            print '<p class="form-heading">Here you can sign up for our newsletter and learn all about CBBR Inc! And don\'t worry! We\'ll keep you information safe.</p>';

            // Error Messages
            if($errorMsg) {
                print '<div id="frmErrors">'.PHP_EOL;
                print '<h2>Your form has the following mistakes that need to be fixed.</h2>'.PHP_EOL;
                print '<ol>'.PHP_EOL;
                
                foreach($errorMsg as $err) {
                    print '<li>'.$err.'</li>'.PHP_EOL;
                }

                print '</ol>'.PHP_EOL;
                print '</div>'.PHP_EOL;
            }
    ?>

    <!-- HTML Forum -->
    <form action="<?php print $phpSelf; ?>" id="frmRegister" method="post">
        <fieldset class="contact">
            <legend>Contact Information</legend>
            <p>
                <label class="required text-field realLabel" for="txtFirstName">First Name:</label>
                <input autofocus
                    <?php if ($firstNameERROR) print 'class="mistake"'; ?>
                    id="txtFirstName"
                    class="standardInput"
                    maxLength="45"
                    name="txtFirstName"
                    onfocus="this.select()"
                    placeholder="Enter your first name"
                    tabindex="100"
                    type="text"
                    value="<?php print $firstName; ?>">
            </p>

            <p>
                <label class="required text-field realLabel" for="txtLastName">Last Name:</label>
                <input autofocus
                    <?php if ($lastNameERROR) print 'class="mistake"'; ?>
                    id="txtLastName"
                    class="standardInput"
                    maxLength="45"
                    name="txtLastName"
                    onfocus="this.select()"
                    placeholder="Enter your last name"
                    tabindex="100"
                    type="text"
                    value="<?php print $lastName; ?>">
            </p>

            <p>
                <label class="required text-field realLabel" for="txtEmail">Email:</label>
                <input 
                    <?php if($emailERROR) print 'class="frmMistake"'; ?>
                    id="txtEmail"
                    class="standardInput"
                    maxLength="45"
                    name="txtEmail"
                    onfocus="this.select()"
                    placeholder="Enter a valid email address"
                    tabindex="120"
                    type="text"
                    value="<?php print $email; ?>">
            </p>

            <p>
                <label class="required realLabel">Gender:</label>
            </p>
            <p>
                <label class="radio-field">
                    <input type="radio"
                        id="radGenderMale"
                        name="radGender"
                        value="Male"
                        tabindex="572"
                        <?php if($gender == "Male") echo 'checked="checked"'; ?>>
                    Male</label>
            </p>
            <p>
                <label class="radio-field">
                    <input type="radio"
                        id="radGenderFemale"
                        name="radGender"
                        value="Female"
                        tabindex="573"
                        <?php if($gender == "Female") echo 'checked="checked"'; ?>>
                    Female</label>
            </p>
            <p>
                <label class="radio-field">
                    <input type="radio"
                        id="radGenderOther"
                        name="radGender"
                        value="Other"
                        tabindex="574"
                        <?php if($gender == "Other") echo 'checked="checked"'; ?>>
                    Other</label>
            </p>
        </fieldset> <!-- Ends Contact -->

        <fieldset class="information">
            <legend>Donation Level</legend>
            <p>
                <label class="required realLabel" for="donationLevel">Donation:</label>
                <select id="donationLevel"
                    name="lstDonationLevel"
                    tabindex="520">
                    <option <?php if($donationLevel=="None (+$0.00)")print " selected "; ?>
                        value="None (+$0.00)" class="dropDownText">
                        None (+$0.00)
                    </option>
                    <option <?php if($donationLevel=="Supporter (+$10.00)")print " selected "; ?>
                        value="Supporter (+$10.00)" class="dropDownText">
                        Supporter (+$10.00)
                    </option>
                    <option <?php if($donationLevel=="Founder (+$15.00)")print " selected "; ?>
                        value="Founder (+$15.00)" class="dropDownText">
                        Founder (+$15.00)
                    </option>
                </select>
            </p>

            <p>
                <label class="required realLabel">Donation Focus:</label>
            </p>
            <p>
                <label class="checkField">
                    <input <?php if($animals) print "checked "; ?>
                        id="chkAnimals"
                        name="chkAnimals"
                        tabindex="420"
                        type="checkbox"
                        value="CBBR Research and Development">
                CBBR Research and Development</label>
            </p>
            <p>
                <label class="checkField">
                    <input <?php if($plants) print "checked "; ?>
                        id="chkPlants"
                        name="chkPlants"
                        tabindex="430"
                        type="checkbox"
                        value="CBBR Advertising">
                CBBR Advertising</label>
            </p>
            <p>
                <label class="checkField">
                    <input <?php if($biosphere) print "checked "; ?>
                        id="chkBiosphere"
                        name="chkBiosphere"
                        tabindex="440"
                        type="checkbox"
                        value="CBBR Staff Fund">
                CBBR Staff Fund</label>
            </p>
        </fieldset>

        <fieldset class="buttons">
            <input class="button"
                id="btnSubmit"
                name="btnSubmit"
                tabindex="900"
                type="submit"
                value="Register">
        </fieldset> <!-- Ends Buttons -->
    </form>

    <?php
        } // FROM THE 'ELSE' IN 'DISPLAY FORM'
    ?>
</article>
<br>

<!-- Background Panel End -->
</div>

<!-- Footer Loader -->
<?php 
    include ("assets/footer.php"); 
?>