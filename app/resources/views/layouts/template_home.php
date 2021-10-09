<!DOCTYPE html>
<html>

<head>
    <title>Chat</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <!--===============================================================================================-->
    <link rel="icon" type="image/png" href="<?php echo BASE_URL ?>assets/images/icons/favicon.ico" />
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL ?>assets/vendor/bootstrap/css/bootstrap.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL ?>assets/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL ?>assets/css/home/home.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL ?>assets/vendor/emojionearea/dist/emojionearea.min.css">

</head>

<body>
    {{content}}

    <!--===============================================================================================-->
    <script src="<?php echo BASE_URL ?>assets/vendor/jquery/jquery-3.2.1.min.js"></script>
    <!--===============================================================================================-->
    <script src="<?php echo BASE_URL ?>assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!--===============================================================================================-->
    <script src="<?php echo BASE_URL ?>assets/js/home/home.js"></script>
    <!--===============================================================================================-->
    <script src="<?php echo BASE_URL ?>assets/vendor/emojionearea/dist/emojionearea.min.js"></script>
</body>

</html>

<script>
    $("#message").emojioneArea({
        pickerPosition : "top",
        tonesStyle     : "bullet"
    })
</script>