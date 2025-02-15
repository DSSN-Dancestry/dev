<?php
  // This page is the one that appears in between steps 3 and 4 when you are
  // adding lineage, and it describes what each type of Relationship means
  // adding a comment to test that I can push

  include 'util.php';
  require 'connect.php';
  my_session_start();


  include 'menu.php';
  $_SESSION["timeline_stage"] = "lineage";

  // update the progress through adding lineage so that if we save and return, we will
  // come back to this page again rather than starting at the beginning.  This seems crazy?
  $fname=$_SESSION["artist_first_name"];
  $conn = getDbConnection();
  $sql = "UPDATE artist_profile SET status=75 WHERE artist_first_name = ?";
  $statement = $conn->prepare($sql);
  $statement->execute([$fname]);

?>

<html>
<head>
	<title>About Lineage</title>
    <link href="css/progressbar.css" rel="stylesheet">
  </head>

<body>

	<form id="biography" class="">
		<?php include 'progressbar.php'; ?>
		<div class="row">
			<div style="clear: both">
                            <h2  style="display:inline;"><strong>ABOUT LINEAGE</strong></h2>
                            <h5  style="display:inline; float: right; color: #006400;"><?php echo "<strong>(You are in ".$_SESSION['timeline_flow']." mode)</strong>";?></h5>
                        </div>
		</div>
		<div class="row">
			<p>There are four types of lineal lines or <strong>Relationships:</strong></p>
			<p>1. <strong>DANCED IN THE WORK OF </strong> - Choreographers whose work you have danced in.<br>
			2. <strong>STUDIED UNDER</strong> - Teachers under whom you have studied.<br>
			3. <strong>COLLABORATED WITH</strong> - Artists with whom you have collaborated.<br>
			4. <strong>INFLUENCED BY </strong> - People with whom you have NOT studied, danced or collaborated, but who have significantly influenced your work such as other artists, authors,  <br>
      &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp; theorists, etc.  You do not need to have a relationship with this person in order to list them as having an impact on  your work.<br><br>

      <!-- 4. <strong>INFLUENCED BY </strong> - People who have significantly influenced your work, such as artists, authors, philosophers, etc. You do not need to have a <br> &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;relationship with this person in order to list them as having an impact on your work.<br><br> -->
			<br><br>
			<strong>Please click the "Next" button to contribute artist's lineage.</strong>
			</p>


		</div>






        <br/>
		<div class="row">
			<div class="large-2 small-8 columns">
				<button class="button" type="button" name="user_profile_submit" id="previous" onclick="window.open('add_artist_biography.php','_self')">
					<span>Previous</span>
				</button>
			</div>
			<div class="large-2 small-8 columns">
				<button class="button" type="button" name="user_profile_submit" id="next" onclick="window.open('add_lineage.php','_self')">
					<span>Next</span>
				</button>
			</div>
			<div class="large-3 small-8 columns">
            <button class="button" id="next1" type="button" onclick="window.open('profiles.php','_self')">
                <span>Save and Continue Later</span>
            </button>
        </div>
			<div class="column">
			</div>
		</div>
	</form>



</body>

<?php

include 'footer.php';
closeConnections();
?>

</html>
