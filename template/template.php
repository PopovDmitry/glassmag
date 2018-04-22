<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Тандем-заказ стеклопакетов</title>
    <link rel="icon" type="image/vnd.microsoft.icon" href="/template/img/favicon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap -->
    <link href="/template/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="bg-dark text-white">
    <div class="container col-12 col-sm-12 col-md-12 col-lg-9 col-xl-6">
	    <?php
            $view = $router->getView();
            include ($view);
	    ?>
	</div>
    <script src="/template/js/bootstrap.min.js"></script>
</body>
</html>