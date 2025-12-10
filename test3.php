<?php
$Status = "";
if( isset($_POST['Form_Name']) && $_POST['Form_Name'] == 'Application_Submitted' ) {
  echo "<script>console.log('post');</script>";
  $file_new_name = "";
  if (isset($_FILES["Lease_agreement_showing_size_and_number_of_pets_allowed"])) {
    // uploading the lease
    $error_message = "";
    $file = $_FILES["Lease_agreement_showing_size_and_number_of_pets_allowed"];
    $file_name = $_FILES["Lease_agreement_showing_size_and_number_of_pets_allowed"]["name"];

    $file_name = $_FILES["Lease_agreement_showing_size_and_number_of_pets_allowed"]["name"];
    $file_tmp_name = $_FILES["Lease_agreement_showing_size_and_number_of_pets_allowed"]["tmp_name"];
    $file_size = $_FILES["Lease_agreement_showing_size_and_number_of_pets_allowed"]["size"];
    $file_error = $_FILES["Lease_agreement_showing_size_and_number_of_pets_allowed"]["error"];
    $file_type = $_FILES["Lease_agreement_showing_size_and_number_of_pets_allowed"]["type"];

    $file_ext = explode('.', $file_name);
    $file_actual_ext = strtolower(end($file_ext));

    $allowed = array("jpg", "jpeg", "png", "pdf", "doc", "docx");

    if ( in_array($file_actual_ext, $allowed) ) {
      if ($file_error === 0) {
        if ($file_size < 1000000) {
          $file_new_name = uniqid('', true).".".$file_actual_ext;
          $file_destination = $_SERVER['DOCUMENT_ROOT']."/browser/leases/".$file_new_name;
          move_uploaded_file($file_tmp_name, $file_destination);
          $error_message = "File uploaded";
        } else {
          $error_message = "File too large";
        }
      } else {
        $error_message = "File upload error";
      }
    } else {
      $error_message = "File type not allowed"; 
    }
  }
  
  // Submitting the application
  $Date_Entered = date('Y-m-d H:i:s');
  $Form_Fields = "";
  $Active = "Yes";
  $Lease = $file_new_name;
  $Name = filter_input(INPUT_POST, "Name");
  $Favorite = "No";
  if ( $Lease == null ) {
    $Lease = "Blank";
  }
  foreach( $_POST as $field => $value ) {
    if ( is_array( $field ) ) {
      foreach( $field as $item ) {
        echo $item;
      }
    } else {
      $field = str_replace("_"," ",$field);
      $Form_Fields = $Form_Fields . "<p class='field-wrapper'><b class='field'>$field</b><br><i class='value'>$value</i></p>";
    }
  }

  if ( $Form_Fields == null || $Date_Entered == null || $Active == null || $Lease == null ) {
    $err_msg = "All Values Not Entered<br>";
    include($_SERVER['DOCUMENT_ROOT'].'/db_error.php');
  } else {
    require_once($_SERVER['DOCUMENT_ROOT'].'/db_connect.php');
    $query = 'INSERT INTO Applications(ID, Date_Entered, Form_Fields, Active, Lease, Name, Favorite) VALUES(:ID, :Date_Entered, :Form_Fields, :Active, :Lease, :Name, :Favorite)';
    
    $stm = $db->prepare($query);
    $stm->bindValue(':ID', null, PDO::PARAM_INT);
    $stm->bindValue(':Date_Entered', $Date_Entered);
    $stm->bindValue(':Form_Fields', $Form_Fields);
    $stm->bindValue(':Active', $Active);
    $stm->bindValue(':Lease', $Lease);
    $stm->bindValue(':Name', $Name);
    $stm->bindValue(':Favorite', $Favorite);
    $execute_success = $stm->execute();
    $FormId = $db->lastInsertId();
    $stm->closeCursor();
    if(!$execute_success){
      print_r($stm->errorInfo()[2]);
    } else {
      $Status = "Your Application Has Been Submitted!";

      $headers = "MIME-Version: 1.0" . "\r\n";
      $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
      $headers .= 'From: <contactus@dexterfoundation.com>' . "\r\n";
      $leaseCopy = ( $Lease === "Blank" ) ? "<i>No lease submitted</i>" : "<a href='http://www.dexterfoundation.com/browser/leases/$Lease'>View Lease [+]</a>";
      $emailBody ="
      <html>
        <head></head>
        <body>
          <a style='font-family: 'Segoe UI', Helvetica, sans-serif;font-size:30px' href='http://dexterfoundation.com/applications/?id=$FormId'>
            View full application on website - $Name
          </a>
          <br><br>
          $leaseCopy
          <br>
          $Form_Fields
        </body>
      </html>
      ";
      mail("joey.puopolo5@gmail.com", "Application - ".$Name, $emailBody, $headers);
     
    }
  }
  
} 
?>
<!DOCTYPE html>
<html>
<head>
  <?php include($_SERVER['DOCUMENT_ROOT']."/modules/head.php"); ?>
</head>
<body data-pagename="Adoption Application">
  <?php include($_SERVER['DOCUMENT_ROOT']."/modules/header.php"); ?>

  <section class="adoption-application-thank-you content" data-status="<?php if ($Status != '') echo 'yes'?>">
    <div class="wrapper">
      <header>
        <h1>
          Thank You!
            <small>
              <?php 
                if ( $Status != "" ) {
                  $redirect = "
                  <script>setTimeout(function(){window.location.replace('http://dexterfoundation.com');}, 5000)</script>
                  ";
                  echo "$redirect $Status";

                }
              ?>
            </small>
          </h1>
          <p>redirecting back to home...</p>
          <div class="btn-wrapper flex">
            <a href="http://dexterfoundation.com" class="btn btn-orange">Go Home</a>
            <a href="https://facebook.com/Dexter-Foundation-231468836941028/" class="btn btn-orange">View Our Facebook Page</a>
          </div>
        </header>
    </div>
  </section>
  <section class="adoption-application-form content form">
    <div class="wrapper">
      <header>
        <h1>Applications</h1>
        <h2>For Fostering and Adopting</h2>
        <p>We are dedicated to rescuing dogs in the Southern California Area and placing them in a loving, forever home.</p>
      </header>
      <div class="content">
        <p>Thank you for you interest in adopting a dog from The Dexter Foundation!</p>
        <p>All information provided herein will be kept confidential. Once received, your application will be reviewed by our Adoption Placement Panel.</p>
        <p>Please bear with us, as The Dexter Foundation is totally run by volunteers with full time jobs. Once your application has been reviewed and approved, we will contact you to schedule a required home check visit. We know you are very excited about bringing a new dog into your home, but please understand that our representatives do rescue work on a volunteer basis.</p>
        <p> If you have any questions please email us, and include your full name, the date of your application along with your phone number. Again, thank you for your interest in adopting a rescue from The Dexter Foundation!</p>
      </div>  
      
    <form method="POST" action="http://dexterfoundation.com/test3/" enctype="multipart/form-data">
      <fieldset>
        <ul>
          <li class="half">
            <div class="field">
              <label>Full Name</label>
              <input value="" type="text" name="Name" required>
            </div>
          </li>
          <li class="half">
            <div class="field">
              <label>Age</label>
              <input value="" type="text" name="Age">
            </div>
          </li>
          <li class="half">
            <div class="field">
              <label>Street Address</label>
              <input value="" type="text" name="Home_Address" required>
            </div>
          </li>
          <li class="half">
            <div class="field">
              <label>City</label>
              <input value="" type="text" name="City" required>
            </div>
          </li>
          <li class="half">
            <div class="field">
              <label>State</label>
              <input value="" type="text" name="State" required>
            </div>
          </li>
              <li class="half">
            <div class="field">
              <label>Zip</label>
              <input value="" type="text" name="Zip" required>
            </div>
          </li>
          <li class="half">
            <div class="field">
              <label>Email</label>
              <input value="" type="email" name="Email_Address" required>
            </div>
          </li>
          <li class="half">
            <div class="field">
              <label>Home Phone</label>
              <input value="" type="tel" name="Home_Phone" required>
            </div>
          </li>
          <li class="half">
            <div class="field">
              <label>Cell Phone</label>
              <input value="" type="tel" name="Cell_Phone">
            </div>
          </li>
          <li class="half">
            <div class="field">
              <label>When is the best time to reach you?</label>
              <textarea name="Best_time_to_reach"></textarea>
            </div>
          </li>
          <li class="form-section">
            <h3>Family Information</h3>
          </li>
          <li class="half">
            <div class="field">
              <label>Living Status</label>
              <select name="Living_Status">
                <option value=""></option>
                <option value="I Live Alone">I Live Alone</option>
                <option value="I Live With Others">I Live With Others</option>
              </select>
            </div>
          </li>
          <li class="half">
            <div class="field">
              <label>If you live with others, do they approve of you bringing this dog into the home?</label>
              <select name="Approval_From_Others">
                <option value=""></option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
              </select>
            </div>
          </li>
              <li class="half">
            <div class="field">
              <label>Does your spouse/significant other approve of you adopting this dog?</label>
              <select name="Approval_from_spouse_or_significant_other">
                <option value=""></option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
              </select>
            </div>
          </li>
          <li class="half">
            <div class="field">
              <label>Name of spouse/significant other</label>
              <input value="" type="text" name="Name_of_spouse_or_significant_other">
            </div>
          </li>
          <li class="half">
            <div class="field">
              <label>Age of spouse/significant other</label>
              <input value="" type="text" name="Age_of_spouse_or_significant_other">
            </div>
          </li>
          <li class="half">
            <div class="field">
              <label>Do you have children visit your home regularly or living in your home?</label>
              <select name="Children_visiting_or_living_here">
                <option value=""></option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
              </select>
            </div>
          </li>
          <li class="half">
            <div class="field">
              <label>If so, how many children?</label>
              <input value="" type="text" name="Amount_Of_Children">
            </div>
          </li>
          <li class="half">
            <div class="field">
              <label>What are their ages?</label>
              <input value="" type="text" name="Ages_Of_Children">
            </div>
          </li>
          <li class="half">
            <div class="field">
              <label>Please list others in your household who will have contact with the dog; such as a nanny, maid, etc. If there is nobody else, the please specify.</label>
              <input value="" type="text" name="Others_who_will_have_contact_with_the_dog">
            </div>
          </li>
          <li class="form-section">
            <h3>Accomodations</h3>
          </li>
          <li class="half">
            <div class="field">
              <label>Why do you want to adopt a rescued dog?</label>
              <textarea name="Why_do_you_want_to_adopt_a_rescue"></textarea>
            </div>
          </li>
          <li class="half">
            <div class="field">
              <label>What qualities do you like in a rescued dog?</label>
              <textarea name="What_qualities_do_you_like_in_a_rescue"></textarea>
            </div>
          </li>
          <li class="half">
            <div class="field">
              <label>What qualities or traits don’t you want to find in a rescued dog?</label>
              <textarea name="What_qualities_do_you_not_want_in_a_rescue"></textarea>
            </div>
          </li>
          <li class="half">
            <div class="field">
              <label>Are you committed to caring for this dog for its lifetime?</label>
              <select name="Are_you_committed_to_caring_for_this_dog_for_its_lifetime">
                <option value=""></option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
              </select>
            </div>
          </li>
          <li class="half">
            <div class="field">
              <label>Where will the dog stay during the day?</label>
              <input value="" type="text" name="Where_will_the_dog_stay_during_the_day">
            </div>
          </li>
          <li class="half">
            <div class="field">
              <label>How many hours per day are you out of the house? Do you work full time, work from home, retired, or stay at home?.</label>
              <input value="" type="text" name="How_many_hours_per_day_are_you_out_of_the_house" required>
            </div>
          </li>
          <li class="half">
            <div class="field">
              <label>Will the dog have access to the house AND yard while left alone?</label>
              <select name="Will_dog_have_access_to_house_and_yard_while_alone">
                <option value=""></option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
              </select>
            </div>
          </li>
          <li class="half">
            <div class="field">
              <label>Who will let the dog out?</label>
              <input value="" type="text" name="Who_will_let_the_dog_out">
            </div>
          </li>
          <li class="half">
            <div class="field">
              <label>Do you have a dog sitter?</label>
              <select name="Do_you_have_a_dog_sitter">
                <option value=""></option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
              </select>
            </div>
          </li>
          <li class="half">
            <div class="field">
              <label>Do you have a dog walker?</label>
              <select name="Do_you_have_a_dog_walker">
                <option value=""></option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
              </select>
            </div>
          </li>
          <li class="half">
            <div class="field">
              <label>Do you have a dog door?</label>
              <select name="Do_you_have_a_dog_door">
                <option value=""></option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
              </select>
            </div>
          </li>
          <li class="half">
            <div class="field">
              <label>Where will the dog sleep?</label>
              <input value="" type="text" name="Where_will_the_dog_sleep">
            </div>
          </li>
          <li class="half">
            <div class="field">
              <label>Will the dog be allowed in the furniture?</label>
              <select name="Will_the_dog_be_allowed_on_the_furniture">
                <option value=""></option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
              </select>
            </div>
          </li>
          <li class="half">
            <div class="field">
              <label>Do you own your own home or rent?</label>
              <select name="Do_you_own_your_own_home_or_rent">
                <option value=""></option>
                <option value="Own">Own</option>
                <option value="Rent">Rent</option>
              </select>
            </div>
          </li>
          <li class="half">
            <div class="field">
              <label>Does the landlord approve of dogs on the property?</label>
              <select name="Landlord_Approval">
                <option value=""></option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
              </select>
            </div>
          </li>
          <li class="half">
            <div class="field">
              <label>Please give the name and number of your <b>landlord</b> as they will be contacted to verify that you are allowed to have animals.</label>
              <input value="" type="text" name="Name_and_phone_number_of_landlord">
            </div>
          </li>
          <li class="half">
            <div class="field">
              <label>Please attach a copy of your lease or notarized statement from your landlord stating the number and size of pets you are allowed to have on the property.</label>
              <input value="" type="file" name="Lease_agreement_showing_size_and_number_of_pets_allowed">
            </div>
          </li>
          <li class="half">
            <div class="field">
              <label>Please explain what kind of home you have. ie house, apartment, condo, etc.</label>
              <input value="" type="text" name="Type_of_home">
            </div>
          </li>
          <li class="half">
            <div class="field">
              <label>Do you have a fenced-in yard?</label>
              <select name="Do_you_have_a_fenced_in_yard">
                <option value=""></option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
              </select>
            </div>
          </li>
          <li class="half">
            <div class="field">
              <label>Please explain what kind of fence you have. ie chain link, picket, etc.</label>
              <input value="" type="text" name="Type_of_fence">
            </div>
          </li>
          <li class="half">
            <div class="field">
              <label>What is the height of the fence?</label>
              <input value="" type="text" name="Height_of_fence">
            </div>
          </li>
          <li class="half">
            <div class="field">
              <label>How are you going to exercise this dog?</label>
              <input value="" type="text" name="How_will_you_exercise_the_dog">
            </div>
          </li>
          <li class="half">
            <div class="field">
              <label>How are you going to keep this dog from running away or becoming lost?</label>
              <input value="" type="text" name="How_will_you_keep_the_dog_from_running_away">
            </div>
          </li>
          <li class="form-section">
            <h3>Rescue Dog Information</h3>
            <p>Please note - The Dexter Foundation rarely has puppies. But please tell us your preferences.</p>
          </li>
          <li class="half">
            
            <div class="field">
              <label>Are you interested in a specific Dexter Foundation dog(s) listed on our homepage or petfinder.com page?</label>
              <select name="Interested_in_a_specific_dog">
                <option value=""></option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
              </select>
            </div>
          
          </li>
          <li class="half">
            <div class="field">
              <label>Which dog are you interested in?</label>
              <input value="" type="text" name="Specific_dogs_name">
            </div>
          </li>
          <li class="half">
            <div class="field">
              <label>Which are you interested in?</label>
              <select name="Interested_in_a_specific_dog_gender">
                <option value=""></option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="No Preference">No Preference</option>
              </select>
            </div>
          </li>
          <li class="half">
            <div class="field">
              <label>What age are you interested in?</label>
              <select name="Interested_in_a_specific_dog_age">
                <option value=""></option>
                <option value="Older Dog">Older Dog</option>
                <option value="Middle-aged Dog">Middle-aged Dog</option>
                <option value="Younger Dog">Younger Dog</option>
                <option value="No Age Preference">No Age Preference</option>
              </select>
            </div>
          </li>
          <li class="checkboxes">
            <ul>
              <li class="half"><p>Would you accept a dog with any of the following issues? Check all that apply.</p></li>
              <li class="checkbox">
                <label>Scarring</label>
                <input type="checkbox" name="Acceptable_condition_1" value="Scarring">
              </li>
              <li class="checkbox">
                <label>Skin Problems</label>
                <input type="checkbox" name="Acceptable_condition_2" value="Skin Problems">
              </li>
              <li class="checkbox">
                <label>Snorting</label>
                <input type="checkbox" name="Acceptable_condition_3" value="Snorting">
              </li>
              <li class="checkbox">
                <label>Special Diet</label>
                <input type="checkbox" name="Acceptable_condition_4" value="Special Diet">
              </li>
              <li class="checkbox">
                <label>Allergies</label>
                <input type="checkbox" name="Acceptable_condition_5" value="Allergies">
              </li>
              <li class="checkbox">
                <label>Arthritis</label>
                <input type="checkbox" name="Acceptable_condition_6" value="Arthritis">
              </li>
              <li class="checkbox">
                <label>Balance Problems</label>
                <input type="checkbox" name="Acceptable_condition_7" value="Balance Problems">
              </li>
              <li class="checkbox">
                <label>Blindness</label>
                <input type="checkbox" name="Acceptable_condition_8" value="Blindness">
              </li>
              <li class="checkbox">
                <label>Deafness</label>
                <input type="checkbox" name="Acceptable_condition_9" value="Deafness">
              </li>
              <li class="checkbox">
                <label>Ear Discharge</label>
                <input type="checkbox" name="Acceptable_condition_10" value="Ear Discharge">
              </li>
              <li class="checkbox">
                <label>Hair Loss</label>
                <input type="checkbox" name="Acceptable_condition_11" value="Hair Loss">
              </li>
              <li class="checkbox">
                <label>Heart Disease</label>
                <input type="checkbox" name="Acceptable_condition_12" value="Heart Disease">
              </li>
              <li class="checkbox">
                <label>Incontinence</label>
                <input type="checkbox" name="Acceptable_condition_13" value="Incontinence">
              </li>
              <li class="checkbox">
                <label>Limping</label>
                <input type="checkbox" name="Acceptable_condition_14" value="Limping">
              </li>
              <li class="checkbox">
                <label>Myelinopathy</label>
                <input type="checkbox" name="Acceptable_condition_15" value="Myelinopathy">
              </li>
              <li class="checkbox">
                <label>One-eyed</label>
                <input type="checkbox" name="Acceptable_condition_16" value="One-eyed">
              </li>
              <li class="checkbox">
                <label>Spinal Deformity</label>
                <input type="checkbox" name="Acceptable_condition_17" value="Spinal Deformity">
              </li>
              <li class="checkbox">
                <label>Three Legged</label>
                <input type="checkbox" name="Acceptable_condition_18" value="Three Legged">
              </li>
              <li class="checkbox">
                <label>Separation Anxiety</label>
                <input type="checkbox" name="Acceptable_condition_19" value="Separation Anxiety">
              </li>
            </ul>
          </li>
          <li class="half">
            <div class="field">
              <label>Would you adopt two dogs that have come from the same home &amp; need to stay together?</label>
              <select name="Would_you_adopt_two_dogs_who_need_to_stay_together">
                <option value=""></option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
              </select>
            </div>
          </li>
          <li class="half">
            <div class="field">
              <label>Does the dog need to be cat-friendly?</label>
              <select name="Does_the_dog_need_to_be_cat_friendly">
                <option value=""></option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
                <option value="Doesn't Matter">Doesn't Matter</option>
              </select>
            </div>
          </li>
          <li class="half">
            <div class="field">
              <label>Does the dog need to be dog-friendly?</label>
              <select name="Does_the_dog_need_to_be_dog_friendly">
                <option value=""></option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
                <option value="Doesn't Matter">Doesn't Matter</option>
              </select>
            </div>
          </li>
          <li class="form-section">
            <h3>Previous Pet History</h3>
          </li>
          <li class="half">
            <div class="field">
              <label>Have you ever owned a dog before?</label>
              <select name="Have_you_ever_owned_a_dog_before">
                <option value=""></option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
              </select>
            </div>
          </li>
          <li class="half">
            <div class="field">
              <label>Tell us about your previously owned dogs. List the breed, and the number of years they were with you.</label>
              <textarea name="Dog_ownership_history"></textarea>
            </div>
          </li> 
          <li class="half">
            <div class="field">
              <label>Do you have any in your home currently?</label>
              <select name="Do_you_have_any_dogs_in_your_home_currently">
                <option value=""></option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
              </select>
            </div>
          </li>
          <li class="half">
            <div class="field">
              <label>Please list their breed, age, and sex.</label>
              <input value="" type="text" name="Current_dogs_breed_age_and_sex">
            </div>
          </li>
          <li class="half">
            <div class="field">
              <label>Are they spayed/neutered</label>
              <select name="Are_your_current_dogs_spayed_or_neutered">
                <option value=""></option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
              </select>
            </div>
          </li>
          <li class="half">
            <div class="field">
              <label>Please explain why they're not spayed/neutered.</label>
              <textarea name="Why_the_current_dogs_arent_spayed_or_neutered"></textarea>
            </div>
          </li>
          <li class="half">
            <div class="field">
              <label>Are all your current pets up to date on their annual vaccinations?</label>
              <select name="Are_your_current_pets_up_to_date_on_their_annual_vaccinations">
                <option value=""></option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
              </select>
            </div>
          </li>
          <li class="half">
            <div class="field">
              <label>In addition to the pets identified above, what other pets have you owned int he last five years?</label>
              <textarea name="Other_pets_owned_in_the_past_five_years"></textarea>
            </div>
          </li>
          <li class="half">
            <div class="field">
              <label>Have you owned any other pets more than five years ago?</label>
              <select name="Have_you_owned_any_other_pets_more_than_five_years_ago">
                <option value=""></option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
              </select>
            </div>
          </li>
          <li class="half">
            <div class="field">
              <label>Please describe how long ago you owned these pets, and describe the animals.</label>
              <textarea name="Description_of_pets_from_greater_than_five_years_ago"></textarea>
            </div>
          </li>
          <li class="half">
            <div class="field">
              <label>Why do you no longer have these pets?</label>
              <input value="" type="text" name="Why_do_you_no_longer_have_these_pets">
            </div>
          </li>
          <li class="half">
            <div class="field">
              <label>Do you have any experience with formal obedience training of dogs?</label>
              <select name="Do_you_have_any_experience_with_formal_obedience_training_of_dogs">
                <option value=""></option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
              </select>
            </div>
          </li>
          <li class="half">
            <div class="field">
              <label>Please explain the methodds of training and where you learned how to train a dog.</label>
              <textarea name="Explain_your_training_methods_and_where_you_learned_how_to_train"></textarea>
            </div>
          </li>
          <li class="half">
            <div class="field">
              <label>Have you filled out an adoption application with any other rescue organization?</label>
              <select name="Have_you_filled_out_an_adoption_application_with_any_other_rescue_organization">
                <option value=""></option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
              </select>
            </div>
          </li>
          <li class="half">
            <div class="field">
              <label>To whom did you apply, and when?</label>
              <input value="" type="text" name="To_which_rescue_organization_did_you_apply_and_when">
            </div>
          </li>
          <li class="half">
            <div class="field">
              <label>Have you adopted a dog from a rescue organization in the past?</label>
              <select name="Have_you_adopted_a_dog_from_a_rescue_organization_in_the_past">
                <option value=""></option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
              </select>
            </div>
          </li>
          <li class="half">
            <div class="field">
              <label>From whom and when did you adopt? Please list their name and contact number if applicable.</label>
              <textarea name="From_whom_and_when_did_you_adopt"></textarea>
            </div>
          </li>
          <li class="half">
            <div class="field">
              <label>How did you hear about The Dexter Foundation?</label>
              <select name="How_did_you_hear_about_us">
                <option value=""></option>
                <option value="My Vetrinarian">My Vetrinarian</option>
                <option value="American Kennel Club">American Kennel Club</option>
                <option value="Internet">Internet</option>
                <option value="Friend">Friend</option>
                <option value="Other">Other</option>

              </select>
            </div>
          </li>
          <li class="half">
            <div class="field">
              <label>If you selected <i>Other</i> above, how did you hear about us?</label>
              <input value="" type="text" name="How_did_you_hear_about_us_other">
            </div>
          </li>
          <li class="form-section">
            <h3>References</h3>
            <p>We would like to contact two personal references. Please provide their name and phone number</p>
          </li>
          <li class="form-title">
            <p>First Reference</p>
          </li>
          <li class="half">
            <div class="field">
              <label>Name</label>
              <input value="" type="text" name="First_reference_name">
            </div>
          </li>
          <li class="half">
            <div class="field">
              <label>Phone</label>
              <input value="" type="tel" name="First_reference_phone">
            </div>
          </li>
          <li class="form-title">
            <p>Second Reference</p>
          </li>
          <li class="half">
            <div class="field">
              <label>Name</label>
              <input value="" type="text" name="Second_reference_name">
            </div>
          </li>
          <li class="half">
            <div class="field">
              <label>Phone</label>
              <input value="" type="tel" name="Second_reference_phone">
            </div>
          </li>
          <li class="form-section">
            <h3>Release for Veterinary Reference</h3>
          </li>
          <li class="half">
            <div class="field">
              <label>My current veterinarian's name</label>
              <input value="" type="text" name="Current_Veterinarian_name">
            </div>
          </li>
          <li class="half">
            <div class="field">
              <label>My current veterinarian's address</label>
              <input value="" type="text" name="Current_Veterinarian_address">
            </div>
          </li>
          <li class="half">
            <div class="field">
              <label>My current veterinarian's phone number</label>
              <input value="" type="tel" name="Current_Veterinarian_phone">
            </div>
          </li>
          <li class="form-title">
            <p>If you do not currently have a veterinarian, please provide us details about who you plan to use.</p>
          </li>
          <li class="half">
            <div class="field">
              <label>Planned Veterinarian's Name</label>
              <input value="" type="text" name="Planned_Veterinarian_Name">
            </div>
          </li>
          <li class="half">
            <div class="field">
              <label>Planned Veterinarian's Address</label>
              <input value="" type="text" name="Planned_Veterinarian_Address">
            </div>
          </li>
          <li class="half">
            <div class="field">
              <label>Planned Veterinarian's Phone</label>
              <input value="" type="tel" name="Planned_Veterinarian_Phone">
            </div>
          </li>
          <li class="form-title">
            <p class="inline">I, <input class="field" type="text" name="Full_Name" placeholder="Full Name Here Please">, hereby give permission for any veterinarian providing service to me/my animals to release medical information on any/all of my animals to the Dexter Foundation, Inc. <br>
                  This release is not limited to the veterinarian named above. Please let your vet know we will be calling for a vet reference.</p>
          </li>
          <li class="">
            <div class="field">
              <label>I agree to the terms above.</label>
              <select name="I_agree_to_the_terms_above">
                <option value="I do not agree">I do not agree</option>
                <option value="I Agree">I Agree</option>
              </select>
            </div>
          </li>
          <li class="">
            <input type="hidden" value="Application_Submitted" name="Form_Name">
            <input type="submit" value="Submit Application">
          </li>
        </ul>
      </fieldset>
    </form>
  </div>
</section>

  <footer>
    <?php include($_SERVER['DOCUMENT_ROOT']."/modules/footer.php"); ?>
  </footer>
</body>

</html>