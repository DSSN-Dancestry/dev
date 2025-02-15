<?php
// this is the page that allows the user to access their profile, and any
// profiles that they have created for other artists.  This is called from
// the "Contribute Your Lineage" main nav element.


include 'util.php';
my_session_start();

// check that the user is logged in - if not, redirect to login.
// MUST be before any headers (marked with $ sign) but before my_session_start();
if (!isset($_SESSION["user_email_address"])) {
    header('Location: login.php');
    exit;
}
$showArtistMenu = true;
include 'menu.php';

$user_email_address = $_SESSION["user_email_address"];

require 'connect.php';

$conn = getDbConnection();

// fetch the logged in user's profile record
$query_myProfile = "SELECT * FROM artist_profile WHERE artist_email_address = ?";
$statement = $conn->prepare($query_myProfile);
$statement->setFetchMode(PDO::FETCH_ASSOC);
$statement->execute([$user_email_address]);
$count_myProfile = $statement->rowCount();
$result_myProfile = $statement->fetchAll();

// fetch any profiles that they have created for other artists
$query_anotherArtist = "SELECT * FROM artist_profile WHERE profile_name= ? && artist_email_address != ? ";
$statement2 = $conn->prepare($query_anotherArtist);
$statement2->setFetchMode(PDO::FETCH_ASSOC);
$statement2->execute([$user_email_address, $user_email_address]);
$count_anotherArtist = $statement2->rowCount();
$result_anotherArtist = $statement2->fetchAll();

// fetch any profiles created for other artists that were later
// taken over by those other artists
$query_checkEnable = "SELECT * FROM artist_profile WHERE past_profile_name = ?";
$statement3 = $conn->prepare($query_checkEnable);
$statement3->setFetchMode(PDO::FETCH_ASSOC);
$statement3->execute([$user_email_address]);
$count_checkEnable = $statement3->rowCount();
$result_checkEnable = $statement3->fetchAll();

?>

<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profiles | Choreographic Lineage</title>
    <script src="js/platform.js"></script>
    <script type="text/javascript" src="js/browserCheck.js"></script>
    <script>
        window.onload = function() {
            strict_check();
        }
    </script>
</head>
<style type="text/css">
    .mrt10 {
        margin-top: 10px !important;
    }

    @media only screen and (max-width: 1000px) {
        #title_div {
            padding-left: 10px;
        }
    }
</style>

<body>
    <div class="row">
        <div id="network_display_div" class="mrt10i">
            <div id="tab_bar_row" class="row tab">
                <button class="tablinks small-2 columns profile" style="float: left;width: 50%;" id="event" onclick="window.location.href = 'profiles.php';">My Profile</button>
                <button class="tablinks small-2 columns other_artist_profile" style="float: left;width: 50%;" id="add_event" onclick="window.location.href = 'other_artist_profiles.php';">Other Artist Profiles</button>
            </div>
        </div>
        <div class="medium-12">
            <section>
                <form id="profiles_form" name="profiles_form" method="post" action="profiles_mediator.php" enctype="multipart/form-data">
                    <fieldset>
                        <legend id="title_div">
                            <strong>
                                <h3>My Profile</h3>
                            </strong>
                        </legend>
                        <div class="row">
                            <div class="small-12 column">
                                <table>
                                    <thead>
                                        <tr>
                                            <th width="200">Name</th>
                                            <th width="200">Email Address</th>
                                            <th width="200">Progress</th>
                                            <th width="300"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($count_myProfile == 0) {
                                            echo "<td> " . $_SESSION["user_firstname"] . " " . $_SESSION["user_lastname"] . "</td>";
                                            echo "<td> " . $_SESSION["user_email_address"] . " </td>";
                                            echo "<td>0%</td>";
                                            echo "<td> <button class='button' type='submit' name='user_contribute_lineage' value=''";
                                            echo "<span>Get Started</span>";
                                            echo "</button></td>";
                                        } else {
                                            echo "<td>" . $result_myProfile[0]['artist_first_name'] . " " . $result_myProfile[0]['artist_last_name'] . "</td>";
                                            echo "<td>" . $result_myProfile[0]['artist_email_address'] . "</td>";
                                            echo "<td>" . $result_myProfile[0]['STATUS'] . "%</td>";
                                            echo '<td style="display: flex; justify-content: space-evenly; width: 450px">';
                                            echo "<button class='button mrt10' type='submit' name='artist_relation_add' 	value=" . $result_myProfile[0]['artist_profile_id'] . ">";
                                            echo "<span>Add Lineal Relationships</span>";
                                            echo "</button>";
                                            echo "<button class='primary button mrt10' type='submit' name='artist_profile_edit' value=" . $result_myProfile[0]['artist_profile_id'] . ">";
                                            echo "<span>Edit</span>";
                                            echo "</button>";
                                            //echo "<button class='success  button' type='submit' name='artist_profile_view' value=" . $result_myProfile[0]['artist_profile_id'] . ">";
                                            //echo "<span>View</span>";
                                            //echo "</button>";
                                            echo "<button class='alert button mrt10' type='submit' name='artist_profile_delete' value=" . $result_myProfile[0]['artist_profile_id'] . " onclick='confirmDelete();'>";
                                            echo "<span>Delete</span>";
                                            echo "</button>";
                                            echo "</td>";
                                            echo '<td style="width: 110px;">';
                                            $display = "";
                                            if ($result_myProfile[0]['last_update_date'] && $result_myProfile[0]['last_update_date'] != "0000-00-00 00:00:00") {
                                                try {
                                                    $lastUpdated = DateTime::createFromFormat('Y-m-d H:i:s', $result_myProfile[0]['last_update_date']);

                                                    $display = "Last updated: " . $lastUpdated->format('M d, Y');
                                                } catch (Exception $e) {
                                                    $display = "Unable to get the last updated timestamp";
                                                }
                                            }


                                            echo "<em> " . $display . "</em>";

                                            echo "</td>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </section>
        </div>
    </div>

    <div class="row">
        <div class="small-3 column">
            &nbsp;
        </div>
        <div class="small-3 column">
            &nbsp;
        </div>
    </div>



    <?php include 'footer.php'; ?>

    <script>
        function confirmDelete() {
            var c = confirm("Warning: You are about to delete this entire profile! Click 'OK' to cancel.");
            if (c == true) {

            } else {
                event.preventDefault();
            }
        }
    </script>
</body>

</html>