<?php
include 'util.php';
my_session_start();

include 'menu.php';

?>

<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Contact | Dancestry</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/foundation/6.2.1/foundation.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300|Asap">
    <link rel="stylesheet" href="../css/global.css">
    <style>
        .portrait {
            width: 200px;
        }
        html,body{
            height: 100%;
            margin:0;
            padding:0;
        }
        .footer{
            margin-top: 13rem;
        }
    </style>
</head>

<body>


<div class="row">
    <div class="medium-8 column text-justify">
        <h2 class="text-center">Contact</h2>
        <hr>
        <section>
            <p><strong>Email : </strong><a href="mailto:Dancestryglobal@gmail.com">Dancestryglobal@gmail.com</a></p>
        </section>
        <section>
            <p><strong>Phone Number : </strong> +1 716-645-0605</p>
        </section>
    </div>
</div>

</body>
<div class="footer">

<?php
include 'footer.php';
?>
</div>
</html>
