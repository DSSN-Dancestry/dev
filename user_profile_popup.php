<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<style type="text/css">
.table_lineage{
  width:100%;margin-left:8px;margin-right:2px;background-color:#eee;
}
.mrt10{margin-top: 10px;}
.mrb5{margin-bottom: 10px;}

.biography{
  font-size: 15px;
  margin-bottom: 2px;
  color: #4743f7;
  text-align : center;
}

.pic{
  height: 240px;
  overflow:hidden;
  width: 340px;
  margin-bottom: 1px;
}
.info{
  text-align : center;
  font-weight: bold;
  /* background-color:#000; */
  margin-bottom: 5px;
}

.name{
  text-align : center;
  font-weight: bold;
  margin-bottom: 2px;
}

.education{
  text-align : center;
  margin-bottom: 2px;
  margin-left: 2px;
}
.genre{
  text-align : center;
  margin-bottom: 2px;
}
.tal{
  text-align: left;
}
</style>

<script type="text/javascript">
var urlVal = '';
var docVal = '';
var add_first_name_lineage = "<button onclick='displayFirstNameLinage()' class='tal'><i class='fa fa-plus'></i> First Name's Lineage: </button>";
var remove_first_name_lineage = "<button onclick='hideFirstNameLinage()' class='tal'><i class='fa fa-minus'></i> First Name's Lineage: </button>";
var add_rev_first_name_lineage = "<button onclick='displayRevFirstNameLinage()' class='tal'><i class='fa fa-plus'></i> First Name is listed in the following Artists' Lineage: </button>";
var remove_rev_first_name_lineage = "<button onclick='hideRevdisplayFirstNameLinage()' class='tal'><i class='fa fa-minus'></i> First Name is listed in the following Artists' Lineage: </button>";

// TODO : this is broken.  It is called from the biography popup, but doesn't load the artist bio.
function openBioPopUp() {
  console.log('urlVal = '+urlVal);
  console.log('docVal = '+docVal);

  if(docVal != ""){
    window.open(docVal);
  }
  else{
    // console.log(urlval);
    if(urlVal.startsWith("http") || urlVal.startsWith("https") || urlVal.startsWith("ftp"))
    {
      window.open(urlVal, '_blank', 'width=400,height=400');
    } else{
      var myWindow= window.open('', '_blank', 'width=400,height=400');
      myWindow.document.write(urlVal);
    }
  }
}

function displayFirstNameLinage(){
  $("#div_lineal_lines").show();
  $("#artist_lineage_text").html(remove_first_name_lineage);
}

function hideFirstNameLinage(){
  $("#div_lineal_lines").hide();
  $("#artist_lineage_text").html(add_first_name_lineage);
}

function displayRevFirstNameLinage(){
  $("#div_rev_lineal_lines").show();
  $("#rev_artist_lineage_text").html(remove_rev_first_name_lineage);
}

function hideRevdisplayFirstNameLinage(){
  $("#div_rev_lineal_lines").hide();
  $("#rev_artist_lineage_text").html(add_rev_first_name_lineage);
}

function getUserProfile(artist_profile_data){
  artist_profile_id = artist_profile_data.artist_profile_id;
  $.ajax({
    url:"artistrelationcontroller.php",
    type:'POST',
    data:JSON.stringify(artist_profile_data),
    success:function(res){

      if(artist_profile_data.add_log == "True"){
          $.ajax({
              url:"logcontroller.php",
              type:'POST',
              data:JSON.stringify({
                  "action":"addUserLogs",
                  "data":{'user': '<?php echo($_SESSION['user_id']);?>', 'oper': 'Viewed', 'det': res.profileDetails[0]['artist_profile_id']}
              }),
              success:function(){
              }
          })
      }
      
      urlVal = res.profileDetails[0]['artist_biography_text'];
      docVal = res.profileDetails[0]['artist_biography'];

      // Update First Name and Last name
      add_first_name_lineage = "<button onclick='displayFirstNameLinage()' class='tal'><i class='fa fa-plus'></i> "+res.profileDetails[0]['artist_first_name']+"'s Lineage: </button>";
      remove_first_name_lineage = "<button onclick='hideFirstNameLinage()' class='tal'><i class='fa fa-minus'></i> "+res.profileDetails[0]['artist_first_name']+"'s Lineage: </button>";
      add_rev_first_name_lineage = "<button onclick='displayRevFirstNameLinage()' class='tal'><i class='fa fa-plus'></i> "+res.profileDetails[0]['artist_first_name']+" is listed in the following Artists' Lineage: </button>";
      remove_rev_first_name_lineage = "<button onclick='hideRevdisplayFirstNameLinage()' class='tal'><i class='fa fa-minus'></i> "+res.profileDetails[0]['artist_first_name']+" is listed in the following Artists' Lineage: </button>";

      // Bio Image Link
      var photo = "./img/profileNoPic.png";
      // if(res.profileDetails[0]['is_user_artist'] !== "artist") photo = "./img/noProfile.png";
      if(res.profileDetails[0]['artist_photo_path']!="") photo = res.profileDetails[0]['artist_photo_path'];
      // First Name, Last Name and Email
      var code=''+
      '<div hidden id="linkfirstname">'+res.profileDetails[0]['artist_first_name']+'</div>'+
      '<div hidden id="linklastname">'+res.profileDetails[0]['artist_last_name']+'</div>'+
      '<div hidden id="linkemail">'+res.profileDetails[0]['artist_email_address']+'</div>';
      if(res.profileDetails[0]['artist_website'] !== 'null'){
        code += '<div hidden id="linkwebsite">'+res.profileDetails[0]['artist_website']+'</div>';
      }

      // Bio Image, Artist First Name, Artist Last Name
      code += '<div id="mySidenav_div">'+
      '<div hidden id="bioTextValue">'+res.profileDetails[0]['artist_biography_text']+'</div>'+
      '<div hidden id="bioDocValue">'+res.profileDetails[0]['artist_biography']+'</div>'+
      ' <img class="pic" id="artist_pic" src = "'+photo+'"/>'+
      '<div class="info"></div>'+
      '<div id="artist_name" class="name">'+res.profileDetails[0]['artist_first_name']+' '+res.profileDetails[0]['artist_last_name']+'</div>';

      // DoB, DoD (if present), Genre (if present), Education (if present)
      code += '<div id="artist_dob" class="dob" ><b> Date of Birth </b><br> '+res.profileDetails[0]['artist_dob']+'</div>';
      if((res.profileDetails[0]['artist_dod'] !== '') && (res.profileDetails[0]['artist_dod'] != '0000-00-00')){
        code += '<div id="artist_dod" class="dod" ><b> Date of Death </b><br> '+res.profileDetails[0]['artist_dod']+'</div>';
      }
      if(res.profileDetails[0]['genre'] !== ''){
        if(res.profileDetails[0]['user_genres'] && res.profileDetails[0]['user_genres'] != ""){
          res.profileDetails[0]['genre'] += ", "+res.profileDetails[0]['user_genres'];
        }

        code += '<div id="artist_genre" class="genre" ><b> Genre </b><br> '+res.profileDetails[0]['genre']+'</div>';
      }
      if(res.education.length!=0){
        code+='<div id="artist_education" class="education" ><b> University </b><br>';
        res.education.forEach(function(i){
          code=code+i.institution_name+'<br>';
        });
        code = code + '</div>';
      }

      // Artist Bio PopUp and Lineal Lines (Lineal Artist for the given Artist)
      code = code + '<div id="artist_bio_div"> ';
      code = code + ' <a href="javascript:void(0)" onclick="openBioPopUp()" id="artist_biography" class="biography">Click here for biography</a>';
      code = code + '</div>   ';

      // Lineal Lines: Lineal artists are the people with whom you have studied, danced, collaborated and have been influenced by.
      // if(res.relations.length != 0){
        code = code + '<div id="artist_lineage_text" class="lineage_table_text mrt10">';
        code = code + add_first_name_lineage;
        code = code + '</div>';
        code = code + ' <div class="row" id="div_lineal_lines" hidden> ';
        code = code + '   <table id="artist_lineals" class="display table_lineage">';
        res.relations.forEach(function(i){
          code = code + '   <tr><td class="large-6 column">'+i.artist_relation+'</td> <td class="large-6 column">'+i.artist_name_2+'</td></tr>';
        });
        code = code + '   </table>';
        code = code + ' </div>';
      // }

      // Added by
        code = code + ' <div id="rev_artist_lineage_text" class="lineage_table_text mrt10 mrb5">';
        code = code + add_rev_first_name_lineage;
        code = code + '</div>';
        code = code + ' <div class="row" id="div_rev_lineal_lines" hidden>';
        code = code + '  <table id="rev_artist_lineals" class="display table_lineage">';
        res.added_by_relations.forEach(function(i){
          code = code + '    <tr><td class="large-6 column">'+i.artist_name_1+'</td><td class="large-6 column">'+i.artist_relation+'</td></tr>';
        });
        code = code + '    </table>';
        code = code + ' </div>';
        code = code + '</div>';
      // }
      if (res.profileDetails[0]['is_user_artist'] !== "artist" && res.relations.length == 0) code = '<div style="padding-top: 20px"><p>This artist does not yet have a profile in Dancestry.</p></div>'
      $('#prof_space').html(code);
      $(".profile-details-class").css("display", "block");
    }
  });
}
</script>
