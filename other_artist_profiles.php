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
    <title>Other Artist Profiles | Choreographic Lineage</title>
</head>
<style type="text/css">
    .mrt10 {
        margin-top: 10px !important;
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
                        <legend><strong>
                                <h3>Other Artist Profiles</h3>
                            </strong></legend>
                        <div class="row">
                            <div class="small-12 column">
                                <table>
                                    <thead>
                                        <tr>
                                            <th width="200">Artist Name</th>
                                            <th width="200">Artist Email Address</th>
                                            <th width="200">Progress</th>
                                            <th width="300"></th>
                                        </tr>
                                    </thead>
                                    <?php
                                    echo "<tbody>";
                                    if ($count_anotherArtist != 0) {
                                        foreach ($result_anotherArtist as $anotherArtist) {
                                            // echo "<tr style='background-color: transparent !important;'>";
                                            echo "<tr>";
                                            echo "<td>" . $anotherArtist['artist_first_name'] . " " . $anotherArtist['artist_last_name'] . "</td>";
                                            // My code
                                            // echo "<td>" . $resultant_anotherArtist['artist_email_address'] . "</td>";
                                            if (strpos($anotherArtist['artist_email_address'], 'dummyemail@') === false) {
                                                echo "<td>" . $anotherArtist['artist_email_address'] . "</td>";
                                            } else {
                                                echo "<td>" . '' . "</td>";
                                            }

                                            // My code ends
                                            echo "<td>" . $anotherArtist['STATUS'] . "%</td>";
                                            echo '<td style="display: flex; justify-content: space-evenly; width: 450px">';
                                            echo "<button class='button mrt10' type='submit' name='artist_relation_add' 	value=" . $anotherArtist['artist_profile_id'] . ">";
                                            echo "<span>Add Lineal Relationships</span>";
                                            echo "</button>";

                                            echo "<button class='primary button mrt10' type='submit' name='artist_profile_edit' value=" . $anotherArtist['artist_profile_id'] . ">";
                                            echo "<span>Edit</span>";
                                            echo "</button>";

                                            //echo "<button class='success  button' type='submit' name='artist_profile_view' value=" . $anotherArtist['artist_profile_id'] . ">";
                                            //echo "<span>View</span>";
                                            //echo "</button>";

                                            echo "<button class='alert  button mrt10' type='submit' name='artist_profile_delete' value=" . $anotherArtist['artist_profile_id'] . " onclick='confirmDelete();'>";
                                            echo "<span>Delete</span>";
                                            echo "</button>";
                                            echo "</td>";
                                            echo '<td style="width: 110px;">';
                                            $display = "";
                                            if ($anotherArtist['last_update_date'] && $anotherArtist['last_update_date'] != "0000-00-00 00:00:00") {
                                                try {
                                                    $lastUpdated = DateTime::createFromFormat('Y-m-d H:i:s', $anotherArtist['last_update_date']);
                                                    $display = "Last updated " . $lastUpdated->format('M d, Y');
                                                } catch (Exception $e) {
                                                    $display = "";
                                                }
                                            }


                                            echo "<em> " . $display . "</em>";
                                            echo "</td>";
                                            echo "</tr>";
                                        }
                                    }
                                    // // Nov 9 2019 My code
                                    // if ($count_checkEnable > 0) {
                                    //     foreach ($result_checkEnable as $checkEnable) {
                                    //         // echo "<tr style='background-color: transparent !important;'>";
                                    //         echo "<tr>";
                                    //         echo "<td>" . $checkEnable['artist_first_name'] . " " . $checkEnable['artist_last_name'] . "</td>";
                                    //         echo "<td>" . $checkEnable['artist_email_address'] . "</td>";
                                    //         echo "<td>".$checkEnable['STATUS']."%</td>";
                                    //         echo "<td>";
                                    //         echo "<button class='secondary  button mrt10' type='submit' disabled name='artist_relation_add' 	value=" . $checkEnable['artist_profile_id'] . " >";
                                    //         echo "<span>Add Lineal Relationships</span>";
                                    //         echo "</button>";

                                    //         echo "<button class='button mrt10' type='submit' disabled name='artist_profile_edit' value=" . $checkEnable['artist_profile_id'] . ">";
                                    //         echo "<span>Edit</span>";
                                    //         echo "</button>";

                                    //         //echo "<button class='success  button' type='submit' disabled name='artist_profile_view' value=" . $checkEnable['artist_profile_id'] . ">";
                                    //         //echo "<span>View</span>";
                                    //         //echo "</button>";

                                    //         echo "<button class='alert  button mrt10' type='submit' disabled name='artist_profile_delete' value=" . $checkEnable['artist_profile_id'] . " onclick='confirmDelete();'>";
                                    //         echo "<span>Delete</span>";
                                    //         echo "</button>";
                                    //         echo "</td>";

                                    //         echo "<td>";
                                    //         $display = "";
                                    //         if ($anotherArtist['last_update_date'] && $anotherArtist['last_update_date'] != "0000-00-00 00:00:00") {
                                    //             try {
                                    //                 $lastUpdated = DateTime::createFromFormat('Y-m-d H:i:s', $anotherArtist['last_update_date']);
                                    //                 $display = "updated ".$lastUpdated->format('m-d-Y');
                                    //             } catch (Exception $e) {
                                    //                 $display = "";
                                    //             }
                                    //         }
                                    //         echo "<em> ". $display . "</em>";
                                    //         echo "</td>";
                                    //         echo "</tr>";
                                    //     }
                                    // }
                                    // Nov 9 2019 My code ends
                                    echo "</tbody>";
                                    echo "</table>";
                                    ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="small-3 column">
                                <button class="button" type="submit" name="artist_profile_add" value="<?php echo $user_email_address; ?>">
                                    <span>Add Artist</span>
                                </button>
                            </div>

                            <div class="small-3 column">
                                &nbsp;
                            </div>
                            <div class="small-3 column">
                                &nbsp;
                            </div>
                        </div>
                    </fieldset>
                </form>
            </section>
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

</html>