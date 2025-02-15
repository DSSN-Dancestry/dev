<?php
include 'util.php';
require 'utils.php';
my_session_start();
$showAdminMenu = true;
checkAdmin();
include 'menu.php';
?>
<title>Maintain Genres | Dancestry</title>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<style type="text/css">
  #usetTable_length {
    width: 170px;
  }

  #usetTable_length label {
    display: flex;
    justify-content: space-between;
  }

  #usetTable_length select {
    width: 50px;
  }

  #usetTable_filter label {
    text-align: left;
  }

  #usetTable_filter input {
    margin-left: 0px;
  }

  @media only screen and (max-width: 1000px) {}
</style>
<script src="js/platform.js"></script>
<script type="text/javascript" src="js/browserCheck.js"></script>
<script>
  window.onload = function() {
    strict_check();
  }
</script>
<div id="adnub_display_div" class="mrt10i row" style="padding-left: 10px; padding-right: 10px;">
  <div id="tab_bar_row" class="row tab">
    <button class="tablinks small-2 columns admin_console_tab" style="float: left;width: 20%;" id="event" onclick="window.location.href = 'delete_user.php';">Maintain Users
    </button>
    <button class="tablinks small-2 columns admin_console_tab" style="float: left;width: 20%;" id="add_event" onclick="window.location.href = 'maintain_genres.php';">Maintain Genres
    </button>
    <button class="tablinks small-2 columns admin_console_tab" style="float: left;width: 20%;" id="feature_event" onclick="window.location.href = 'feature_management.php';">Feature Management
    </button>
    <button class="tablinks small-2 columns admin_console_tab" style="float: left;width: 20%;" onclick="window.location.href = 'maintain_network.php';">Update Network Cache
    </button>
    <button class="tablinks small-2 columns admin_console_tab" style="float: left;width: 20%;" onclick="window.location.href = 'maintain_logs.php';">Admin Logs
    </button>
  </div>
</div>
<div class="row" style="padding-left: 10px; padding-right: 10px;">
  <div class="medium-12">
    <section>
      <legend><strong>
          <h3>Maintain Genres</h3>
      </legend></strong>
      <strong>Add Genre</strong>
      <div class="sectionbox">
        <form id="add_genre_form" name="add_genre_form">
          <label>Genre Name *
            <input type="text" name="genre_name" id="genre_name"></input>
          </label>

        </form>
      </div>
      <div class="row">
        <div class="large-12 buttonbar column">
          <button class="success button education_button" id="addGenre" type="button">
            <span>Add Genre</span>
          </button>
        </div>
      </div>
      <div class="row">
        <div class="large-12 buttonbar column">
          <p id="genreMessage"></p>
        </div>
      </div>

      <form id="delete_genre_form" name="delete_genre_form" method="post" action="genrecontroller.php" enctype="multipart/form-data">
        <input type="hidden" name="">
        <fieldset>

          <div class="row">
            <div class="small-12 column">
              <table id='usetTable' class='display'>
                <thead>
                  <tr>
                    <th width="200">Category</th>
                    <th width="200">Genre Name</th>
                    <th width="300"></th>
                  </tr>
                </thead>

                <tbody id='genreBody'>

                </tbody>
              </table>

            </div>
          </div>
        </fieldset>
      </form>
    </section>
  </div>
</div>
</body>

<div class="footer" style="margin-top:4.5%">
  <?php
  include 'footer.php';
  ?>
</div>

<script type="text/javascript">
  var table;
  var genres;

  function confirmDelete(id) {
    console.log("confirm delete!");
    //var c = confirm("Warning: You are about to delete this genre! Click 'OK' to continue.");
    //  if(!c){
    //    event.preventDefault();
    //    }else{

    fetch("genrecontroller.php", {
        method: "post",
        headers: {
          'Content-Type': 'application/json',
        },
        mode: "cors",
        body: JSON.stringify({
          action: "deleteGenres",
          genre_id: "" + id
        })
      })
      .then(res => res.json())
      .then(
        result => {
          //console.log("cool beans");
          loadGenres();
        },
        error => {
          alert("error " + error);
        }
      );
    //    }
  }




  function loadGenres() {

    fetch("genrecontroller.php", {
        method: "post",
        body: JSON.stringify({
          action: "getGenres"
        })
      })
      .then(res => res.json())
      .then(
        result => {
          genres = [];
          table.clear();
          for (let i = 0; i < result['genres'].length; i++) {


            let genre = result['genres'][i];
            genres.push(genre.genre_name);
            let deletebutton = "<button class='alert button' type='button' name='genre_id' id=" + genre.genre_id + " onclick='confirmDelete(" + genre.genre_id + ");'>";
            deletebutton += "<span>Delete</span>";
            deletebutton += "</button>";

            table.row.add([genre.category, genre.genre_name, deletebutton]).draw(false);

          }
        },
        error => {
          alert("error! load_genre-maintain_genres");
        }
      );
  }

  function messageFade() {
    var element = document.getElementById("genreMessage");
    element.classList.add("fade-out");
    setTimeout(messageClear, 5000);
  }

  function messageShow(message, type) {
    $('#genreMessage').html(message);

    if (type == "error") {
      $('#genreMessage').css("background", "red");
    } else {
      $('#genreMessage').css("background", "green");
    }
    $('#genreMessage').css("visibility", "visible");
    messageFade();
  }

  function messageClear() {
    $('#genreMessage').css("visibility", "hidden");
    var element = document.getElementById("genreMessage");
    element.classList.remove("fade-out");
    $('#genreMessage').html("");

  }

  $(document).ready(function() {

    table = $('#usetTable').DataTable();

    loadGenres();


    $('#addGenre').on('click', function() {
      let newgenre = $('#genre_name').val();

      if (genres.includes(newgenre)) {
        messageShow("Genre " + newgenre + " already exists!", "error");
      } else {
        fetch("genrecontroller.php", {
            method: "post",
            body: JSON.stringify({
              action: "addOrEditGenres",
              category: "Dance",
              genre_name: newgenre
            })
          })
          .then(res => res.json())
          .then(

            result => {
              console.log(result);
              let deletebutton = "<button class='alert button' type='button' name='genre_id' id=" + result['Record Id'] + " onclick='confirmDelete(" + result['Record Id'] + " );'>";
              deletebutton += "<span>Delete</span>";
              deletebutton += "</button>";

              table.row.add(["Dance", newgenre, deletebutton]).draw();
              messageShow("Genre " + newgenre + " has been added.", "success");
              $("#genre_name").val("");
              genres.push(newgenre);
            },
            error => {
              alert("maintain_genres error!");
            }
          );
      }
    })
  });
</script>