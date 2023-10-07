<!DOCTYPE html>
<html lang="es">
<head>
    <?php
        if ( $_SERVER["DOCUMENT_ROOT"] == 'C:/xampp/htdocs' ) {
            $servidor = "http://localhost/lexgotravel/";
        } else {
            $servidor = "https://lexgotravel.com/";
        }
    ?>
    <base href="<?php echo $servidor ?>public/"  >

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto | Lex Go Travel</title>
    <?php include ("links.php") ?>

</head>
<body>
    <!--HEADER-->
    <?php include ("header.php") ?>
    <!--FIN HEADER-->

    <!--BODY-->

    <!--PORTADA-->
    <div>
        <!--PORTADA-->
        <div class="" id="portada-nosotros" STYLE="height: 335px !important; background-image: url(medios/img/portada-contacto.jpg) !important; background-attachment: fixed; background-position: bottom; background-repeat: no-repeat; background-size: cover; border-bottom: 10px solid #228ce3;">
            <div class="px-4 py-5 text-center pt-0 d-flex align-items-center justify-content-center"  id="pantalla" style="height: 100%; background: #0000006b;">
                
                <h1 class="text-center text-white">Contacto</h1>
            </div>
            
        </div>
        <!--FIN PORTADA-->
        <div class="container py-4" id="filtro">
            <form id="formFiltro" action="motordebusqueda" method="POST">
                <div class="row px-5">
                    
                    <style>
                        .puntero{
                            cursor: pointer;
                        }

                        .ocultar{
                            display: none;
                        }

                        #location::placeholder {
                            color: white;
                        }
                    </style>
                    <div class="col-md-3 opt-filter">
                        <!-- <select id="location">
                            <option value="" selected disabled>Seleccionar destino</option>
                            
                        </select> -->

                        <input id="location" placeholder="Seleccionar destino" style="background-color:transparent; border: none; color: white;"/>
                    </div>
                    <div class="dropdown col-md-3 text-center">
                        <nav role="navigation" class="nav-hab">
                            <div>
                                <ul class="nav-items m-0" style="padding:1px;">
                                    <li class="nav-item dropdown">
                                        <a href="" class="nav-link"><p style="color:White !important">
                                            <div class="mx-auto d-flex align-items-center justify-content-center text-center">
                                                <img class="mx-2" src="medios/img/mdb/iconohabitacionmotor.png" alt="" style="height: 19px;"><p id="cantidadHabitaciones" class="text-white">1</p>
                                                <img class="mx-2" src="medios/img/mdb/iconoadultomotor.png" alt="" style="height: 19px;"><p id="cantidadAdultos" class="text-white">1</p>
                                                <img class="mx-2" src="medios/img/mdb/iconomenormotor.png" alt="" style="height: 19px;"><p id="cantidadMenores" class="text-white">0</p>
                                            </div>
                                        </p></a>
                                        <nav class="submenu">
                                            <div id="submenu-qty" class="submenu-qty">
                                                <ul class="submenu-items clonar">
                                                <li class="submenu-item">
                                                        <a href="#" class="submenu-link">
                                                            <div class="">
                                                                <div class="row col-12 ">
                                                                    <div class="col-sm-5 ">
                                                                        <h3 for="" class="color" style="color:#228ce3 !important;">Adultos</h3>
                                                                        <label for="" class="white" style="font-size: 12px;">Desde los 18 años</label>
                                                                    </div>  
                                                                    <div class="col-sm-3 inputnumber">
                                                                        <div class="number-input">
                                                                            <button class="minus minusAdults"></button>
                                                                            <input class="quantity" min="1" max="8" name="quantity" value="1" type="number">
                                                                            <button class="plus plusAdults"></button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </li> 
                                                    <li class="submenu-item">
                                                        <a href="#" class="submenu-link">
                                                            <div class="">
                                                                <div class="row col-12">
                                                                    <div class="col-sm-5">
                                                                        <h3 for="" class="color" style="color:#228ce3 !important;">Menores</h3>
                                                                        <label for="" class="white" style="font-size: 12px;">Menores de 17 años</label>
                                                                    </div>
                                                                    <div class="col-sm-3 inputnumber">
                                                                        <div class="number-input">
                                                                            <button class="minus minusMinors"></button>
                                                                            <input class="quantity" min="0" name="quantity" value="0" type="number">
                                                                            <button class="plus plusMinors"></button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>

                                            <div class="d-flex btn-add-habit">
                                                <button class="btn btn-primary" id="agregar">Agregar habitación +</button>
                                            </div>
                                        </nav>
                                    </li> 
                                </ul> 
                            </div>
                            
                        </nav>
                    </div>
                    <div class="col-md-3 text-center">
                        <input id="datefilter" class="form-control op-fil" type="text" placeholder="Check in - Check out" aria-label="Search" name="datefilter" required autocomplete="off">
                    </div>
        
                        <div class="col-md-3 text-center d-grid gap-2">
                            <input id="btn-filtro" type="submit" class="btn text-light rounded-0 boton-busc" value="Buscar">
                        </div>
                                        
                </div>
            </form>

        </div>
            <!--FIN FILTRO-->
        </div>



        <div class="container cont-form-contact">
            <div class="p-3  tit-contact" style="background:#05365e; margin-top: 31px;"><h2 class="text-center text-white">¡Estamos para servirte!</h2></div>
            <div class="p-3 my-3"><p class="text-center">Si deseas una atención personalizada, escríbenos aquí</p></div>
            
            <form id="formContacto" method="" action="" class="needs-validation">
                <div class="row p-5 m-0 p-0 form-contacto" style="background-image: url(medios/img/Pattern.png) !important; background-attachment: fixed; background-size: contain;"">
                    <div class="col-md-6 p-5 cont-form-campor">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Nombre</label>
                            <input type="text" name="nombre" class="contacto form-control rounded-0 border-0" id="nombre" placeholder="Nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Correo de contacto</label>
                            <input type="email" name="correo" class="contacto form-control rounded-0 border-0" id="apellido" placeholder="Correo para contactarle" required>
                        </div>
                        <div class="p-5 text-center text-contact-num">
                            <p class="text-center">Si necesitas una atención más a detalle, llámanos al</p>
                            <div class="d-flex align-items-center justify-content-center text-num-cont"><h3>999 123 4567</h3><p class="text-center">Línea Lex Go Travel</p></div>
                        </div>
                        
                    </div>
                    <div class="col-md-6 p-5 cont-form-campor">
                        <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">País de residencia</label>
                            <select class="form-select rounded-0 border-0" aria-label="Default select example" name="pais">
                                <option selected>Ingresa tu país de providencia.</option>
                                <option value="Mexico">Mexico</option>
                                <option value="USA">Estados Unidos</option>
                                <option value="Taiwan">Taiwan</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Teléfono</label>
                            <input type="text" name="telefono" class="contacto form-control rounded-0 border-0" id="telefono" placeholder="Teléfono" required>
                        </div>
                        <div class="mb-3">
                            <textarea id="mensaje" class="contacto h-100 form-control mt-5 rounded-0 border-0" name="mensaje" placeholder="Mensaje..." required></textarea>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-center">
                        <button type="button" onclick="enviarCorreo()" id="enviar" name="button" value="Enviar Mensaje" class="btn w-25 rounded-0 text-white fw-bold" style="background: #e3223e; margin: 0px 97px;">Enviar</button>
                    </div>
                    <div class="p-5 text-center text-contact-num-movil" style="display: none;">
                        <p class="text-center">Si necesitas una atención más a detalle, llámanos al</p>
                        <div class="d-flex align-items-center justify-content-center text-num-cont"><h3>999 123 4567</h3><p class="text-center">Línea Lex Go Travel</p></div>
                    </div>
                </div>
            </form>          
        </div>

        
    </div>
    <!--FIN PORTADA-->
        

    <!--FIN BODY-->

    <!--FOOTER-->
        <?php include ("footer.php") ?>
    <!--FIN FOOTER-->
    <script src="js/filtro.js"></script>
</body>
<script>
    function enviarCorreo(){
        $.ajax({
            type: "post",
            url: "templates/contacto.php",
            data: $("#formContacto").serialize(),
            success: function(response){
                const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                iconColor: '#228ce3',
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
                })

                Toast.fire({
                icon: 'Excelente',
                title: 'Su mensaje fue enviado correctamente!'
                })
            }
        })
    }
</script>
</html>
