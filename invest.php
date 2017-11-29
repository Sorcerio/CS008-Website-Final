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
    $accountType = "Public";
    $donationAmount = 0.00;
    $message = "None";

    // Forum Error Flags
    $firstNameERROR = false;
    $lastNameERROR = false;
    $emailERROR = false;
    $accountTypeERROR = false;
    $donationAmountERROR = false;
    $messageERROR = false;

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
        $accountType = htmlentities($_POST["lstAccountType"], ENT_QUOTES, "UTF-8");
        $donationAmount = htmlentities($_POST["txtDonationAmount"], ENT_QUOTES, "UTF-8");
        $message = htmlentities($_POST["txtMessage"], ENT_QUOTES, "UTF-8");

        // Add Data
        $dataRecord[] = $firstName;
        $dataRecord[] = $lastName;
        $dataRecord[] = $email;
        $dataRecord[] = $accountType;
        $dataRecord[] = $donationAmount;
        $dataRecord[] = $message;

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

        if($accountType == "") {
            $errorMsg[] = "Please choose an account type.";
            $accountTypeERROR = true;
        }

        if($donationAmount == "") {
            $errorMsg[] = "Please enter a investment amount.";
            $donationAmountERROR = true;
        }

        if($donationAmount == "") {
            $errorMsg[] = "Please enter an investment message.";
            $donationAmountERROR = true;
        }

        // Process Forum (Passed Validation)
        if(!$errorMsg) {
            if($debug) {
                print PHP_EOL.'<p>Form is valid.</p>';
            }

            // Save Data
            $myFolder = "data/";
            $myFileName = "investors";
            $fileExt = ".csv";

            $filename = $myFolder.$myFileName.$fileExt;

            $file = fopen($filename, "a");

            fputcsv($file, $dataRecord);

            fclose($file);

            // Create Message
            $message = "<h2>Investor information.</h2>";

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

            $from = "investorSerivces@cbbr.com";

            $subject = "Your Investment: ";

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
            print '<h2>Sign Up as an Investor Today!</h2>';
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
        </fieldset> <!-- Ends Contact -->

        <fieldset class="information">
            <legend>Investment Information</legend>
            <p>
                <label class="required realLabel" for="accountType">Account Type:</label>
                <select id="accountType"
                    name="lstAccountType"
                    tabindex="520">
                    <option <?php if($accountType=="Public")print " selected "; ?>
                        value="Public" class="dropDownText">
                        Public
                    </option>
                    <option <?php if($accountType=="Private")print " selected "; ?>
                        value="Private" class="dropDownText">
                        Private
                    </option>
                </select>
            </p>

            <p>
                <label class="required text-field realLabel" for="txtDonationAmount">Investment Amount:</label>
                <input autofocus
                    <?php if ($donationAmountERROR) print 'class="mistake"'; ?>
                    id="txtDonationAmount"
                    class="standardInput"
                    maxLength="100"
                    name="txtDonationAmount"
                    onfocus="this.select()"
                    placeholder="Enter the amount you would like to invest."
                    tabindex="100"
                    type="number"
                    value="<?php print $donationAmount; ?>">
            </p>

            <p>
                <label class="required text-field-large realLabel" for="txtMessage">Message:</label>
                <input autofocus
                    <?php if ($messageERROR) print 'class="mistake"'; ?>
                    id="txtMessage"
                    class="standardInput"
                    maxLength="300"
                    name="txtMessage"
                    onfocus="this.select()"
                    placeholder="Enter the message you'd like to attach to your investment."
                    tabindex="100"
                    type="text"
                    value="<?php print $message; ?>">
            </p>
        </fieldset>

        <div class="buttons">
            <input class="button"
                id="btnSubmit"
                name="btnSubmit"
                tabindex="900"
                type="submit"
                value="Register as an Investor">
        </div> <!-- Ends Buttons -->
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