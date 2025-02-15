<?php
include 'util.php';
my_session_start();

include 'menu.php';


$id = $_GET['id'];



if (isset($_SESSION["user_email_address"])) {
    include 'connection_open.php';

    $sql1 = "select status from bugs where id='$id';";
    $event_profile1 = mysqli_query($dbc, $sql1) or die('Error querying database.: '  .mysqli_error($dbc));
    $row1 = mysqli_fetch_array($event_profile1);

    $query = "SELECT * FROM bugs where id='$id'";
    $event_profile = mysqli_query($dbc, $query) or die('Error querying database.: '  .mysqli_error($dbc));
    $row = mysqli_fetch_array($event_profile);
    $imageURL = 'uploads/'.$row["file_name"];
    //echo $imageURL;

    $query_all_comments = "SELECT comment, uploaded_on FROM bug_comments where id='$id' ORDER BY uploaded_on DESC;";
    $result_all_comments = mysqli_query($dbc, $query_all_comments) or die('Error querying database.: ' . mysqli_error());
}
?>
<html>
<head>
  <title>Help Page</title>
  <style>

    .footer{
      margin-top: 2.9%;
    }
  </style>

</head>

<div class="container">

  <div style="text-align:center">
    <h4>Issue Description</h4>
  </div>

  <div class="row">

      <form action="issue_mediator.php?id=<?php echo $id ?>" method="post" enctype="multipart/form-data">
        <label for="fname">Name<font size="3" color="red">*</font></label>
        <input type="text" id="name" name="name" placeholder="Your name" value="<?php echo $row['user_name'] ?>" required DISABLED>
        <label for="lname">Email<font size="3" color="red">*</font></label>
        <input type="Email" id="mail" name="mail" placeholder="Your Email-ID" value="<?php echo  $row['user_email_id'] ?>" required DISABLED>
        <label for="issuetitle">What is the issue about?<font size="3" color="red">*</font></label>
        <input type="text" id="title" name="title" placeholder="Heading" value="<?php echo  $row['issue_title'] ?>" required DISABLED>
        <label for="subject">Description<font size="3" color="red">*</font></label>
        <textarea id="subject" name="content" placeholder="Please start writing here" style="height:110px" required DISABLED><?php echo htmlspecialchars($row['user_comment']); ?></textarea>
        <label for="subject">Screenshot</label><br>
        <img src="<?php echo $imageURL; ?>" alt="No screenshot Uploaded!!" height = "700" width = "700"/><br><br>
        <label for="category"><B>Select Category for the issue:</B></label>


        <select name="category">
          <option value="">Select...</option>
          <option value="New Feature" <?php echo ($row['category']=='New Feature') ? "selected" : ""; ?>>New Feature</option>
          <option value="Enhancement" <?php echo ($row['category']=='Enhancement') ? "selected" : ""; ?>>Enhancement</option>
          <option value="Bug" <?php echo ($row['category']=='Bug') ? "selected" : ""; ?>>Bug</option>

        </select>



        <label for="severity"><B>Mark Priority:</B></label>
        <select name="severity">
          <option value="4 -">Select...</option>
          <option value="3 - Low" <?php echo ($row['severity']=='3 - Low') ? "selected" : ""; ?>>3 - Low</option>
          <option value="2 - Moderate" <?php echo ($row['severity']=='2 - Moderate') ? "selected" : ""; ?>>2 - Moderate</option>
          <option value="1 - High" <?php echo ($row['severity']=='1 - High') ? "selected" : ""; ?>>1 - High</option>
        </select>

        <label for="comment"><B>Add Comment</B></label>
        <input type="text" id="comment" name="comment" placeholder="Also mention from whom is the comment"style="width: 100%;"/>
        <input type="submit"value="Set Priority or Add Comment" class='button'/>



        <br><br>
        <B> Comments:</B>
        <table id ='usetTable' class='display'>
          <thead>
              <tr>
                  <th width="200">Comment</th>
                  <th width="200">Added On</th>

              </tr>
          </thead>
          <?php
          echo "<tbody>";
          while ($resultant_all_comments = mysqli_fetch_array($result_all_comments)) {
              echo "<tr>";
              echo "<td>" . $resultant_all_comments['comment']."</td>";
              echo "<td>" . $resultant_all_comments['uploaded_on']."</td>";
              //echo "<td>" . date("F d, Y h:i:s A", strtotime($resultant_all_comments['uploaded_on'])) . "</td>";
              echo "</tr>";
          }
          echo "</tbody>";
          echo "</table>";
          ?>





        <?php
          if ($row1['status'] == '1') {
              echo "<a onClick='cnf($id);' class='button'>Mark Resolved</a>";
          } else {
              echo "<a onClick='cnf1($id);' class='button'>Mark Unresolved</a>";
          }
        ?>


        <a href="notify_issue.php?id=<?php echo $id ?>" class='button'>Notify Client</a>
        </div>

      </form>

    </div>

</div>
<script>

$("#comment").click(function () {
  location.href='issue_mediator.php?id='+id;
    });

function cnf(id)
      {
            if (confirm("Mark this issue resolved ?"))
                 location.href='resolve_issue.php?id='+id;
      }

function cnf1(id)
{
      if (confirm("Mark this issue as unresolved ?"))
            location.href='unresolve_issue.php?id='+id;
}
</script>
<div class="footer">
  <?php
  include 'footer.php';
  ?>
  </div>
  <script type="text/javascript">
        $(document).ready( function () {
        $('#usetTable').DataTable();
} );
</script>
</div>
</html>
