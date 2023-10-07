<!-- Footer -->
<footer class="text-center p-0 text-lg-start bg-light text-muted">
    <!-- Section: Links  -->
    <section id="footer-1" class="py-4">
        <div class="container text-center text-md-start mt-5">
        <!-- Grid row -->
        <div class="row mt-3">
            <!-- Grid column -->
            <div class="col-md-3 col-lg-4 col-xl-3 mx-auto mb-4 d-flex align-items-center justify-content-center">
            <!-- Content -->
                <a href="./"><img class="image-footer d-flex justify-content-center" src="medios/img/lexgotravelblancochico.png"></a>
            </div>
            <!-- Grid column -->

            <!-- Grid column -->
            <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mb-4">
            <!-- Links -->
            <h6 class="text-uppercase fw-bold mb-4 text-white text-decoration-underline">
                Destinos
            </h6>
            <p class="text-white-cont">
                <a href="#!" class="text-white">Estados Unidos</a>
            </p>
            <p class="text-white-cont">
                <a href="#!" class="text-white">Latinoamerica</a>
            </p>
            <p class="text-white-cont">
                <a href="#!" class="text-white">America del sur</a>
            </p>
            <p class="text-white-cont">
                <a href="#!" class="text-white">Europa</a>
            </p>
            <p class="text-white-cont">
                <a href="#!" class="text-white">Oriente</a>
            </p>
            </div>
            <!-- Grid column -->

            <!-- Grid column -->
            <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mb-4">
            <!-- Links -->
            <h6 class="text-uppercase fw-bold mb-4 text-white text-decoration-underline">
                Hoteles & Resorts
            </h6>
            <p class="text-white-cont">
                <a href="#!" class="text-white">Fiesta Americana</a>
            </p>
            <p class="text-white-cont">
                <a href="#!" class="text-white">Palace Resorts</a>
            </p>
            <p class="text-white-cont">
                <a href="#!" class="text-white">Hard Rock</a>
            </p>
            <p class="text-white-cont">
                <a href="#!" class="text-white">Oasis</a>
            </p>
            <p class="text-white-cont">
                <a href="#!" class="text-white">RIU</a>
            </p>
            </div>
            <!-- Grid column -->

            <!-- Grid column -->
            <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mb-md-0 mb-4">
            <!-- Links -->
            <h6 class="text-uppercase fw-bold mb-4 text-white text-decoration-underline">
                Información
            </h6>
            <p class="text-white-cont">
                <a href="#!" class="text-white">Acerca de</a>
            </p>
            <p class="text-white-cont">
                <a href="#!" class="text-white">Términos y condiciones</a>
            </p>
            <p class="text-white-cont">
                <a href="#!" class="text-white">Politica de cancelación</a>
            </p>
            <p class="text-white-cont">
                <a href="#!" class="text-white">Preguntas frecuentes</a>
            </p>
            <p class="text-white-cont">
                <a href="#!" class="text-white">Membresias</a>
            </p>
            </div>
            <!-- Grid column -->
        </div>
        <!-- Grid row -->
        </div>
    </section>
    <!-- Section: Links  -->

    <!-- Copyright -->
    <div class="text-center p-2" style="background-color:#228ce3;">
        <div class="text-center">
            <ul class="nav d-flex align-items-center justify-content-center">
                
                <li class="nav-item"><a class="navbar-brand" href="#"><img class="" src="medios/img/iconofacefooter.png" alt=""></a></li>
                <li class="nav-item"><a class="navbar-brand" href="#"><img class="" src="medios/img/Instagramfooter.png" alt=""></a></li>
                <li class="nav-item"><a class="navbar-brand" href="#"><img class="" src="medios/img/iconoyoutubeinferiorfooter.png" alt=""></a></li>
                <li class="nav-item"><a class="navbar-brand" href="#"><img class="" src="medios/img/iconotripadvisorfooter.png" alt=""></a></li>

            </ul>
        </div>
        <p id="footertext" class="text-white my-2" style="text-align: center;">
        © LexGo Travel, Todos los derechos reservados  
        <a class="text-reset fw-bold" href="https://mdbootstrap.com/">| All rights reserved |</a>
        <a class="text-reset fw-bold" href="https://mdbootstrap.com/">Términos y condiciones </a>
        <a class="text-reset fw-bold" href="https://mdbootstrap.com/">| Aviso de privacidad |</a>
        <a class="text-reset fw-bold" href="https://mdbootstrap.com/">Alto estándar de higiene</a>
        </p>
    </div>
    <!-- Copyright -->
</footer>

<!-- BOOTSTRAP 5.1.3 -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>

<!-- Calendario filtro -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<!-- VENOBOX (MAPAS IFRAMES) -->
<script type="text/javascript" src="js/venobox/venobox.min.js"></script>

<!-- SWEET ALERT2 -->
<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<!-- CUSTOM -->
<script type="text/javascript" src="js/script.js" language="javascript"></script>

<script>
    function verificarReserva() {
        Swal.fire({
            title: 'Escriba su Referencia o Folio',
            input: 'text',
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
            cancelButtonColor: "#e3223e",
            confirmButtonColor: '#228ce3',
            confirmButtonText: 'Verificar',
            showLoaderOnConfirm: true
        }).then((result) => {
            if (result.value) {
                window.location.href = "./../reservacion.php?codigo=" + result.value;
            }
        })
    };
</script>

<!-- <script src="https://www.tripadvisor.com.mx/WidgetEmbed-cdsratingsonlywide?border=true&amp;locationId=21452258&amp;display_version=2&amp;uniq=329&amp;lang=es_MX" async=""></script> -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.11.0/sweetalert2.js"></script> -->
