<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Schulverwaltung">
    <meta name="author" content="Sebastian Reinhold">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <!--<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">-->
    <link rel="stylesheet" href="../style/admincp.css" type="text/css">
    <!--Tabulator einbindung-->
    <!-- <link href="https://unpkg.com/tabulator-tables@4.6.2/dist/css/tabulator.min.css" rel="stylesheet"> -->
    <!--<link href="https://unpkg.com/tabulator-tables@4.7.0/dist/css/bootstrap/tabulator_bootstrap4.min.css" rel="stylesheet">-->
    <link href="../js/tabulator/css/tabulator_bootstrap4.css" rel="stylesheet">
    <script type="text/javascript" src="https://unpkg.com/tabulator-tables@4.7.0/dist/js/tabulator.min.js"></script>
    <!--<script type="text/javascript" src="../js/tabulator/js/tabulator.js"></script>-->
    <script type="text/javascript" src="../js/sheetjs/xlsx.full.min.js"></script>
    <script src="../js/js.js"></script>
    <title>Schulverwaltung <?php if(isset($_GET[ 'site' ])) echo "- ".$_GET[ 'site' ]; ?></title>
    <?php
    header('X-Content-Type-Options: nosniff');
	ini_set('error_reporting', E_ERROR)
    ?>
</head>