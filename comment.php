<html>
<head></head>
<body>
<a href="#popupLogin" data-rel="popup" data-position-to="window" data-transition="pop">
<p align = 'center'><img src='image/add.png'></p>
</a>
<div data-role="popup" id="popupLogin" data-theme="a" class="ui-corner-all">
<form action="popup.php" method="post">
    <div style="padding:10px 20px;">
        <h3>ADD INFO</h3>
        <label for='mail' class='ui-hidden-accessible'>Mail:</label>
        <input name='mail' id='mail' value='' placeholder='Mail' data-theme='a' type='text'>

        <label for='username' class='ui-hidden-accessible'>Username:</label>
        <input name='username' id='username' value='' placeholder='Username' data-theme='a' type='text'>

        <label for='password' class='ui-hidden-accessible'>Password:</label>
        <input name='password' id='password' value='' placeholder='Password' data-theme='a' type='text'>

        <button type="submit" name="Submit">ADD INFO</button>
    </div>
</form>
</body>