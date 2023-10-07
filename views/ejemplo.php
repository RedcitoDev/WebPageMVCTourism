<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo; ?></title>

    <link rel="icon" type="image/png" href="./../../../../medios/img/Fabicon.png">

    <!-- jQuery -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>

    <!-- BOOTSTRAP 5.1.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!-- SWEET ALERT2 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.11.0/sweetalert2.css"/>

    <!--FUENTE-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href= "https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- CUSTOM -->
    <link href="./../../../../css/style3.css"     rel="stylesheet" type="text/css">
    <link href="./../../../../css/style2.css"     rel="stylesheet" type="text/css">
    <link href="./../../../../css/video.css"      rel="stylesheet" type="text/css">
</head>
<body>

<div id="header">
    <div class="fixed-top">
        <div class="bg-white ">
            <div id="menu-sup" class="container">
                <header class="navbar p-0 navbar-expand-md navbar-dark bd-navbar bgdefault">
                    <div class="container-fluid">
                        <div class="row col-sm-12 col-md-12 col-xl-12 text-center text-sm-center text-md-center text-xl-start">
                            <div class="col-sm-12 col-md-4 col-xl-3 d-flex align-items-center mmoviltop20 icon-orange sect-men-1">
                                <a class="navbar-brand m-0 p-0" href="#"><img class="d-flex align-items-center" src="./../../../../medios/img/iconoCovid.png" alt="">
                                <a href="#" class="nav-link link-dark px-2 active" aria-current="page">Covid-19 Información</a></a>
                            </div>
                            <div class="col-sm-12 col-md-4 col-xl-6 text-end mmoviltop20 py-2 sect-men-2">
                                <li class="nav-item"><a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-mdb-toggle="dropdown" aria-expanded="false" ><img class="" src="./../../../../medios/img/Telefonoencabezado.png" alt=""> 999 123 4567 </a></li>
                                <li class="nav-item align-middle"><a href="javascript:;" onclick="verificarReserva()" class="nav-link link-light bg-black px-1 py-1 my-1 mx-1">Verifica tu reservación</a></li>
                            </div>
                            <div class="col-sm-12 col-md-4 col-xl-3 mmoviltop20 py-2 sect-men-3">
                                <ul class="list-unstyled fs-3 m-0 list-inline text-center azul">
                                    <li class="nav-item"><a class="navbar-brand" href="#"><img class="" src="./../../../../medios/img/iconofacencabezado.png" alt=""></a></li>
                                    <li class="nav-item"><a class="navbar-brand" href="#"><img class="" src="./../../../../medios/img/iconoinstagramencabezado.png" alt=""></a></li>
                                    <li class="nav-item"><a class="navbar-brand" href="#"><img class="" src="./../../../../medios/img/iconoyoutubeencabezado.png" alt=""></a></li>
                                    <li class="nav-item"><a class="navbar-brand" href="#"><img class="" src="./../../../../medios/img/iconotripadvisorencabezado.png" alt=""></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </header>
            </div>
        </div>
       
        <nav id="barra-inf" class="navbar navbar-expand-lg navbar-dark bg-dark static-top">
            <div class="container">
                <a class="navbar-brand" href="index.php">
                <img src="./../../../../medios/img//lexgotravelgrande.png" alt="logo lexgo travel" style="height: 51px;">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a id="op-menu" class="link nav-link active fw-bold" aria-current="page" href="./">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a id="op-menu" class="link nav-link fw-bold slider_left text-bd" href="nosotros">Nosotros</a>
                    </li>
                    <li class="nav-item">
                        <a id="op-menu" class="link nav-link fw-bold slider_left text-bd" href="destinos">Destinos</a>
                    </li>
                    
                    <li class="nav-item">
                        <a id="op-menu" class="link nav-link fw-bold slider_left text-bd" href="contacto">Contacto</a>
                    </li>
                </ul>
                </div>
            </div>
        </nav>
    </div>

    <div style="height: 145px;">
    </div>
</div>
    
</body>
</html>