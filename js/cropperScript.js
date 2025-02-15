// the target size
var TARGET_W = 1800;      /*to set cropping width and height area*/
var TARGET_H = 1800;

// show loader while uploading photo
function submit_photo() {
	// display the loading texte
	$('#loading_progress').html('<img src="images/loader.gif"> Uploading your photo...');
}

// show_popup : show the popup
function show_popup(id) {
	// show the popup
	$('#'+id).show();
}

// close_popup : close the popup
function close_popup(id) {
	// hide the popup
	$('#'+id).hide();
}

// show_popup_crop : show the crop popup
function show_popup_crop(url) {
	// change the photo source
	$('#cropbox').attr('src', url);

	var img = $('#cropbox')[0];
	$(img).on('load', function() {
		var width = img.naturalWidth;
		var height = img.naturalHeight;

		var x = (width/height)*64;
		var y = (x+10) + "vh";
		x = x+"vh";
		$("#cropbox").css("height", "64vh");
		$("#cropbox").css("width", x);

		$("#modal").css("max-height", "75vh");
		$("#modal").css("max-width", y);

		$("#modal").css("min-height", "75vh");
		$("#modal").css("min-width", y);

		console.log(x);
		console.log(y);

	});
	
	// destroy the Jcrop object to create a new one
	try {
		jcrop_api.destroy();
	} catch (e) {
		// object not defined
	}
	// Initialize the Jcrop using the TARGET_W and TARGET_H that initialized before
    $('#cropbox').Jcrop({
      aspectRatio: TARGET_W / TARGET_H,
      setSelect:   [ 100, 100, TARGET_W, TARGET_H ],
      onSelect: updateCoords
    },function(){
        jcrop_api = this;
    });

    // store the current uploaded photo url in a hidden input to use it later
	$('#photo_url').val(url);
	// hide and reset the upload popup
	$('#popup_upload').hide();
	$('#loading_progress').html('');
	$('#photo').val('');

	
	$('#modal').css('display', 'block');
	$('#popup_crop').show();
		
}

function crop_photo() {
    var x_ = $('#x').val();
    var y_ = $('#y').val();
    var w_ = $('#w').val();
    var h_ = $('#h').val();
    var photo_url_ = $('#photo_url').val();
    var targ_w = TARGET_W;
    var targ_h = TARGET_H;

    $('#popup_crop').hide();
    $('#photo_container').html('<img src="images/loader.gif"> Processing...');

    var img = new Image();
    img.onload = function() {

        var canvas = document.createElement('canvas');
        var ctx = canvas.getContext('2d');

        canvas.width = targ_w;
        canvas.height = targ_h;

        var original_w = img.width;
        var original_h = img.height;
        var rx = original_w / $('#cropbox').width();
        var ry = original_h / $('#cropbox').height();
        var crop_x = parseInt(x_ * rx);
        var crop_y = parseInt(y_ * ry);
        var crop_w = parseInt(w_ * rx);
        var crop_h = parseInt(h_ * ry);

        ctx.drawImage(img, crop_x, crop_y, crop_w, crop_h, 0, 0, targ_w, targ_h);
        var cropped_data_url = canvas.toDataURL();

        var formData = new FormData();
        formData.append('cropped_image', cropped_data_url);
        formData.append('photo_url', photo_url_);

        $.ajax({
            url: 'upload_cropped_image.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log(response);
                $('#photo_container img').attr('src', response);
                $('#modal').css('display', 'none');
                reloadPage();
            },
            error: function(xhr, textStatus, errorThrown) {
                console.log("AJAX request failed: " + textStatus + ", " + errorThrown + ", " + xhr);
            }
        });
    };
    img.src = photo_url_;
}

// updateCoords : updates hidden input values after every crop selection
function updateCoords(c) {
	$('#x').val(c.x);
	$('#y').val(c.y);
	$('#w').val(c.w);
	$('#h').val(c.h);
}

function closeModal() {
	$('#modal').hide();
}