<?php

// ----INCLUDE APIS------------------------------------
// Include our Website API
include("api/api.inc.php");


// ----PAGE GENERATION LOGIC---------------------------

function createPage()
{
       // HTML form for adding a new event with CSS, JavaScript for validation and AJAX
       $formHTML = <<<FORM
       <!-- jQuery CDN -->
       <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
   
       <style>
           body { font-family: Arial, sans-serif; }
           form { 
               display: flex;
               flex-wrap: wrap; /* Allows form items to wrap onto the next line */
               margin: 20px; 
               padding: 20px; 
               border: 1px solid #ccc;
               background-color: #f4f4f4;
           }
           .form-row { /* Defines a container for each form row */
               display: flex;
               width: 100%;
               align-items: center;
               margin-bottom: 10px;
           }
           label { 
               flex: 1;
               margin-right: 10px;
               padding-right: 10px;
               text-align: right;
           }
           input, textarea, select { 
               flex: 3; /* Larger flex-grow value allows inputs to take more space */
               min-width: 250px; /* Ensures inputs do not become too small */
           }
           textarea {
               min-height: 80px; /* Adjust height for text area */
           }
           input[type="submit"] { 
               width: auto;
               padding: 10px 20px;
               margin-top: 10px;
               margin-left: auto; /* Aligns the submit button to the right */
           }
       </style>
       
       <h1>Add New Event</h1>
       <form id="eventForm">
           <div class="form-row">
               <label for="eventName">Event Name:</label>
               <input type="text" id="eventName" name="eventName" required>
           </div>
           <div class="form-row">
               <label for="eventDetails">Event Details:</label>
               <textarea id="eventDetails" name="eventDetails" required></textarea>
           </div>
           <div class="form-row">
               <label for="eventDate">Date and Time:</label>
               <input type="datetime-local" id="eventDate" name="eventDate" required>
           </div>
           <div class="form-row">
               <label for="eventLocation">Location:</label>
               <input type="text" id="eventLocation" name="eventLocation" required>
           </div>
           <div class="form-row">
               <label for="eventCategory">Category:</label>
               <select id="eventCategory" name="eventCategory" required>
                   <option value="Music">Music</option>
                   <option value="Poetry">Poetry</option>
                   <option value="Comedy">Comedy</option>
               </select>
           </div>
           <div class="form-row">
               <label for="artistDetails">Artist Name:</label>
               <input type="text" id="artistDetails" name="artistDetails">
           </div>
           <div class="form-row">
               <label for="artistLink">Artist Details:</label>
               <input type="url" id="artistLink" name="artistLink">
           </div>
           <input type="submit" value="Submit">
       </form>
   
       <div id="message"></div> <!-- Placeholder for server response message -->
   
       <script>
           $(document).ready(function() {
               $('#eventForm').on('submit', function(e) {
                   e.preventDefault(); // Prevent default form submission
                   $.ajax({
                       type: 'POST',
                       url: 'submit_event.php', // PHP script to process the form
                       data: $(this).serialize(), // Serialize form data
                       success: function(response) {
                           $('#message').html(response); // Display response from server
                       },
                       error: function() {
                           $('#message').html('Error processing your request.');
                       }
                   });
               });
           });
       </script>
   FORM;
   
       $homePageContent = <<<PAGE
           <h2>This is the Home Page.</h2>
       PAGE;

      
   

    // Combine home page content and the form
   
    $totalContent = $homePageContent . $formHTML ; // Combine them
    return $totalContent;
}

// ----BUSINESS LOGIC---------------------------------
$tpageContent = createPage();

// ----BUILD OUR HTML PAGE----------------------------
// Create an instance of our Page class
$tindexPage = new MasterPage("Home Page");
$tindexPage->setDynamic1($tpageContent);
$tindexPage->renderPage();

?>
