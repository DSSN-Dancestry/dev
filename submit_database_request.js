function submitJsonOld(formname, suburl, data, callback) {
  var form = data;
  if (!form) {
    form = $(formname).serializeJSON();
  }
  console.log("Making ajax call");
  $.ajax({
    url: suburl,
    type: "POST",
    dataType: "json",
    contentType: "application/json; charset=UTF-8",
    data: JSON.stringify(form),
    success: function(maindta) {
      console.log("query returns");
      if (callback) {
        console.log("executing callback");
        //alert("executing callback");
        callback(maindta);
      }
    },
    error: function(jqXHR, textStatus, errorThrown) {
      var message = "ERROR:" + errorThrown;
      console.log("ERROR in submitJSON : " + message);
      //alert(message);
    }
  });
  return false;
}

function submitJson(formname, suburl, data, callback, addLog) {

  var form = data;
  if (!form) {
    form = $(formname).serializeJSON();
  }
  console.log("calling fetch");
  console.log(JSON.stringify(form));
  fetch(suburl, {
    method: "post",
    body: JSON.stringify(form)
  })
    .then(res => res.json() ) // note, you can't put anyting other than res.json() here or it breaks
    .then(
      result => {
        console.log("query returns");
        console.log(result);
        console.log(suburl);
        console.log(form);
        if(addLog){
          addArtistProfileLogs(addLog);
        }
        if (callback) {
          callback(result);
        }
      },
      error => {

        var message = "ERROR:" + error;
        console.log("ERROR in submitJSON : " + message);
      }
    );
  console.log("fetch was called");
}

function printJson() {
  document.getElementById("display").innerHTML =
    "<pre>" + JSON.stringify(maindta, null, 2) + "</pre>";
}

function getJson(suburl, callback) {
  $.ajax({
    url: suburl,
    type: "GET",

    success: function(maindta) {
      callback(maindta);
    },
    error: function(jqXHR, textStatus, errorThrown) {
      var message = "ERROR:" + errorThrown;
    }
  });
}

function loadContent(suburl, data, callback) {
  //alert("Hi load "+JSON.stringify(data,null,2));

  $.ajax({
    url: suburl,
    type: "GET",
    dataType: "json",
    contentType: "application/json; charset=UTF-8",
    data: JSON.stringify(data),
    success: function(maindta) {
      if (callback) {
        callback(maindta);
      } else {
        document.getElementById("contentframe").innerHTML = maindta;
      }
    },
    error: function(jqXHR, textStatus, errorThrown) {
      var message = "ERROR:" + errorThrown;
    }
  });
}