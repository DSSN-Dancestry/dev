<!-- This is the progress bar displayed in all the steps of gathering an artist profile
     the CSS that styles it can be found in progressbar.css -->
<script>
  let status = <?php echo isset($_SESSION['status'])?$_SESSION['status']:0; ?>;
  console.log("status is "+status);
</script>


<div class="row">
  <ul class="progressbar">
    <li id="first" class="<?php echo (isset($_SESSION['status']) &&  $_SESSION['status'] > 0)?'complete':''; ?>" style="cursor:pointer; color:blue" onclick="window.open('add_artist_profile.php','_self');" ><p id="firstlink" >Add Artist Profile</p></li>
    <li id="second" class="<?php echo (isset($_SESSION['status']) &&  $_SESSION['status'] > 25)?'complete':''; ?>"><p id="secondlink" >Add Artist Personal Info</p></li>
    <li id="third" class="<?php echo (isset($_SESSION['status']) &&  $_SESSION['status'] > 50)?'complete':''; ?>"><p id="thirdlink"  >Add Artist Biography / Photo</p></li>
    <li id="fourth" class="<?php echo (isset($_SESSION['status']) &&  $_SESSION['status'] > 75)?'complete':''; ?>"><p id="fourthlink" >Add Lineage</p></li>
  </ul>
</div>

<script>

  // color in the progressbar to show which stage we are currently on
  $(".progressbar li").removeClass("active");

  let stage = '<?php echo $_SESSION["timeline_stage"] ?>';
  console.log("timeline stage is "+stage);
  if (stage == 'profile'){
    $("#first").toggleClass("active");
  }else if (stage == 'personal'){
      $("#second").toggleClass("active");
  }else if (stage == 'bio'){
      $("#third").toggleClass("active");
  }else if (stage == 'lineage'){
      $("#fourth").toggleClass("active");
  }

  // render the progressbar links clickable if you have progressed to that point
  if (status > 0) {
    console.log("setting second link");
    $("#second").click(window.open.bind(null,'add_artist_personal_information.php','_self'));
    $("#second").css("cursor", "pointer");
    $("#second").css("color", "blue");
  }
  if (status > 25) {
    $("#third").click(window.open.bind(null, 'add_artist_biography.php','_self'));
      $("#third").css("cursor", "pointer");
      $("#third").css("color", "blue");
  }
  if (status > 50) {
    $("#fourth").click(window.open.bind(null, 'add_lineage.php','_self'));
      $("#fourth").css("cursor", "pointer");
      $("#fourth").css("color", "blue");
  }


</script>
