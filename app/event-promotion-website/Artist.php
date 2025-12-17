<?php

// ----INCLUDE APIS------------------------------------
// Include our Website API
include("api/api.inc.php");
include("connection.php");  // Include the database connection



// ----PAGE GENERATION LOGIC---------------------------
function createPage()
{

    // HTML content for logged-in users
    $tcontent = <<<PAGE
    <h2>Add New Artist</h2>
    <form id="artistForm" method="POST" enctype="multipart/form-data" style="max-width: 500px; margin: 20px auto; padding: 20px; border-radius: 8px; box-shadow: 0 2px 15px rgba(0,0,0,0.1); background: #fff;">
        <div style="margin-bottom: 10px;">
            <label for="name" style="display: block; margin-bottom: 5px;">Name:</label>
            <input type="text" id="name" name="name" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
        </div>
        <div style="margin-bottom: 10px;">
            <label for="bio" style="display: block; margin-bottom: 5px;">Biography:</label>
            <textarea id="bio" name="bio" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; height: 100px;"></textarea>
        </div>
        <div style="margin-bottom: 10px;">
            <label for="social" style="display: block; margin-bottom: 5px;">Social Media Username:</label>
            <input type="text" id="social" name="social" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
        </div>
        <div style="margin-bottom: 10px;">
            <label for="picture" style="display: block; margin-bottom: 5px;">Picture:</label>
            <input type="file" id="picture" name="picture" required style="width: 100%; padding: 8px; border-radius: 4px;">
        </div>
        <input type="submit" value="Add Artist" style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;">
    </form>
    <div id="response" style="max-width: 500px; margin: 20px auto; padding: 10px; background-color: #f4f4f4; border-radius: 8px;"></div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#artistForm').submit(function(event) {
            event.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: 'add_artist.php',
                type: 'POST',
                data: formData,
                success: function(data) {
                    var response = JSON.parse(data);
                    if (response.success) {
                        var artistHtml = "<li class='artist-card'>" +
                            "<div class='artist-img'><img src='uploads/" + response.picture + "' alt='Artist Image'></div>" +
                            "<div class='artist-info'>" +
                            "<strong>Name:</strong> " + response.name + "<br>" +
                            "<strong>Bio:</strong> " + response.bio + "<br>" +
                            "<strong>Social Media:</strong> " + response.social + "<br>" +
                            "</div>" +
                            "</li>";
                        $('.artist-list').append(artistHtml);
                    }
                    $('#response').html(response.message);
                },
                cache: false,
                contentType: false,
                processData: false
            });
        });
    });
    </script>
    PAGE;
    
    return $tcontent;
}

function displayArtistList($conn) {
    $sql = "SELECT name, biography, social_media, picture FROM artist";
    $result = $conn->query($sql);

    $tcontent = "<style>
                    .artist-list { list-style: none; padding: 0; }
                    .artist-card { 
                        display: flex; 
                        align-items: center; 
                        margin-bottom: 20px; 
                        padding: 10px; 
                        background: #f4f4f4; 
                        border-radius: 10px; 
                        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
                    }
                    .artist-img { 
                        flex: 0 0 100px; 
                        margin-right: 20px; 
                    }
                    .artist-img img { 
                        width: 100px; 
                        height: auto; 
                        border-radius: 5px; 
                    }
                    .artist-info {
                        flex: 1;
                    }
                </style>
                <h2>List of Artists</h2>
                <ul class='artist-list'>";

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $tcontent .= "<li class='artist-card'>
                            <div class='artist-img'><img src='uploads/" . htmlspecialchars($row['picture']) . "' alt='Artist Image'></div>
                            <div class='artist-info'>
                                <strong>Name:</strong> " . htmlspecialchars($row['name']) . "<br>
                                <strong>Bio:</strong> " . htmlspecialchars($row['biography']) . "<br>
                                <strong>Social Media:</strong> " . htmlspecialchars($row['social_media']) . "<br>
                            </div>
                          </li>";
        }
    } else {
        $tcontent .= "No artists found.";
    }

    $tcontent .= "</ul>";

    return $tcontent;

    

}



// ----BUSINESS LOGIC---------------------------------
$tpagecontent = createPage();
$tpagecontent2 = displayArtistList($conn);

// ----BUILD OUR HTML PAGE----------------------------
// Create an instance of our Page class
$tindexpage = new MasterPage("Home Page");
$tindexpage->setDynamic1($tpagecontent);
$tindexpage->setDynamic2($tpagecontent2);
$tindexpage->renderPage();

?>
