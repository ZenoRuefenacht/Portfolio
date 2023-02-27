<html>
<head>
     <link rel="icon" href="favicon.png" type="image/x-icon">
     <title>Erfolgreich versendet</title>
     <style>
     a {
         text-decoration: none;
         color: turquoise;
     }

     body {
         background-color: black;
         color: white;
     }
     </style>
 </head>

 <body>

 </body>

 <?php
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];
    $to = "mail@zeno-ruefenacht.com";
    $subject = "Feedback von $name";
    $headers = "From: $email";

    mail($to, $subject, $message, $headers);

    echo "Ihre Nachricht wurde gesendet!";
    
    header("Location: https://nutunixi.myhostpoint.ch/web");
    
    ?>


<script>

    /*var interval = setInterval(myURL, 1);

    function myURL() {
        document.location.href = 'https://zeno-ruefenacht.com/feedback/';
        clearInterval(interval);
    }*/
</script>
</html>