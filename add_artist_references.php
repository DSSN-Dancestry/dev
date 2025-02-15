<style type="text/css">
  
.add-reference-button{
    display: inline-block;
    border: 1px solid black;
    background:white;
    box-shadow:0 0px 10px;
    float: right;
}
.add-reference-butto:active, .add-reference-butto:focus{
    transform: translateY(2px);
}


#mySidenav_div{
  overflow-y: scroll;
  height: 100%;
  overflow-x: hidden;
  text-align:center;
}

.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 50;
  display:none;
  background: rgba(0, 0, 0, 0.6);
}

.modal-guts {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  padding: 20px 50px 0px 20px;
  border: 5px solid #ddd;
}

.modal .close-button {
  position: absolute;

  /* don't need to go crazy with z-index here, just sits over .modal-guts */
  z-index: 1;

  top: 10px;

  /* needs to look OK with or without scrollbar */
  right: 20px;

  border: 0;
  background: #006400;
  color: white;
  padding: 5px 10px;
  font-size: 1.3rem;
}

.profile-details-class{
  display: none;
  border: 5px solid #ddd;
  margin-bottom: 5px;
  padding: 10px;
}

.lineage_table_text{
  text-align : left;
  margin-left: 2px;
  color: #2199e8;
  text-decoration: none;
  line-height: inherit;
  cursor: pointer;
}

.modal {
  /* This way it could be display flex or grid or whatever also. */
  display: none;

  /* Probably need media queries here */
  width: 70%;
  max-width: 100%;
  height: 70%;
  max-height: 100%;
  position: fixed;
  z-index: 100;
  left: 50%;
  top: 50%;

  /* Use this for centering if unknown width/height */
  transform: translate(-50%, -50%);
  background: white;
  box-shadow: 0 0 60px 10px rgba(0, 0, 0, 0.9);
}

.bgrlgr{
  background-color: lightgreen !important;
  margin-left: -10px!important;
  margin-right: 0px !important;
}


.cursorp{
  cursor: pointer;
}
.bgn{
  background: none !important;
}
.brz{
  border: 0 !important;
}
.mrt10{
  margin-top: 10px;
}
.mrt30{
  margin-top: 30px;
}
.tac{
  text-align: center;
}
.w120p{
  width: 120px;
}
.mrb10{
  margin-bottom: 10px;
}
</style>

<!-- add references modal -->
<div class="modal-overlay" id="references-modal-overlay"></div>

<div class="modal" id="references-modal">
  <input type="button" class="close-button" id="reference-close-button" onclick="saveReferences()" value="X"/>
  <div class="modal-guts" >
    <div class="row">
      <div class="large-12 column" id="reference_space">
      <!-- As you move through the lineage form, please note the sources you used to complete this artist’s lineage by clicking on the references icon at the top right each of each page. -->
      References (please note the sources you used to complete this artist’s lineage here.)      
        <div>
          <textarea id="artist_reference" name="artist_reference" rows ="5" style="height: 75%;"  maxlength="4000" placeholder="Example: 
Biography: Bill T. Jones’ website
Date of Birth: Wikipedia
"><?php
            if (isset($_SESSION["reference_details"]) && $_SESSION['status'] != 0) {
                echo$_SESSION["reference_details"];
            }
            else{
                echo "";
            }?></textarea>
          <!-- <input type="button" value="Close" onclick="event.preventDefault();closereference_detailsencesModal()"/> -->
          <center>
            <input class="secondary success button" id="submit-button-ref" form="artist_references_form" type="button" value="Save & Close" onclick="saveReferences()"/>
          </center>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">

// References modal
function addArtistReferences(){
    $("#references-modal-overlay").css("display", "block");
    $("#references-modal").css("display", "block");
}

function closeReferencesModal(){
    $("#references-modal-overlay").css("display", "none");
    $("#references-modal").css("display", "none");
}

function saveReferences(){
  $.ajax({
    url: "artist_reference.php", // Url to which the request is send
    type: "POST",             // Type of request to be send, called as method
    data: {"artist_reference":$("#artist_reference").val()}, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
    success: function(data)   // A function to be called if request succeeds
    {
      console.log(data);
      closeReferencesModal();
    }
  });
}
</script>