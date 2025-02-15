<?php
  include 'util.php';
  my_session_start();
  include 'menu.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Thank You</title>
    <style type="text/css">
        .confirmation_container{
            margin-top: 3%;
        }
        .p{
            word-wrap: normal;
        }
        .button_container{
            margin: auto;
        }
    </style>
    <script src="submit_database_request.js"></script>
    <script>
      function updateTimeline(){
        submitJson(null, 'artistcontroller.php', {"action":"addOrEditArtistProfile","artist_profile_id":"<?php echo $_SESSION['artist_profile_id']?>", "status":"100", "completed_date":"now()"}, null);
      }

    </script>
</head>

<body onload="updateTimeline();">
<div class="confirmation_container">
    <div class="row">
        <div class="small-12 medium-8 large-8 small-centered columns">
            <h1 class="text-center">Thank you for your contribution!</h1>
        </div>
    </div>
    <div class="row">
        <div class="small-12 small-centered columns">
            <br/> <br/>

            <p>Send any comments or questions regarding this survey or the Dancestry project to <strong><a href="mailto:Dancestryglobal@gmail.com">Dancestryglobal@gmail.com</a></strong></p>

        </div>
    </div>
    <hr>

    <div class="row">
        <div class="small-2 columns">
            <button class="button" id="profile" type="button" onclick="window.open('profiles.php','_self');">
                <span>Back to Profile</span>
            </button>
        </div>
        <div class="small-2 columns">
            <button class="button" id="explore" type="button" onclick="window.open('lineage_index.php','_self');">
                <span>Explore the Network</span>
            </button>
        </div>
        <div class="column">
        </div>
    </div>
</div>

<script>

    function addArtistProfileLogs(addLog){
        if(addLog == "true"){
            $.ajax({
                url:"logcontroller.php",
                type:'POST',
                data:JSON.stringify({
                    "action":"addUserLogs",
                    "data":{'user': '<?php echo($_SESSION['user_id']);?>', 'oper': 'Edited lineage information', 'det': '<?php echo $_SESSION["artist_profile_id"] ?>'}
                }),
                success:function(){
                }
            })
        }
	}

</script>

</body>
</html>