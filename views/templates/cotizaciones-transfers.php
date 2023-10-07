<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contrato Lex Go Tranfers</title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,500;0,700;1,300;1,500;1,700&display=swap');
        @page {
            size: 8.5in 11in;
            margin: 0;
            padding: 0;
        }

        html {
            box-sizing: border-box;
        }

        *, *:before, *:after {
            box-sizing: inherit;
        }

        body {
            width: 8.5in;
            /* height: 11in; */
            font-family: 'Open Sans', Arial, Helvetica, sans-serif;
            margin: 0;
            margin-left: -50px;
        }

        p {
            color: #555;
            font-size: 14px;
        }
        
        .contenedor {
            width: 8in;
            margin: 0 auto;
            margin-top: 135px;
        }

        .encabezado {
            width: 110%;
            height: 125px;
            position: fixed;
            top: 0;
            left: -45px;
            padding: 10px;
        }

        .encabezado__razon-social {
            display: inline-block;
            width: 50%;
        }

        .encabezado__logo {
            margin-bottom: 8px;
        }

        .encabezado__razon-social-info > p {
            font-size: 13px;
            font-weight: 700;
            color: #555;
            margin: 0;
            margin-bottom: 2px;
        }

        .encabezado__fecha {
            margin-left: 35px;
            display: inline-block;
            width: 45%;
            text-align: right;
            vertical-align: top;
        }

        .encabezado__fecha > * {
            margin: 0;
        }

        .encabezado__cotizacion-title {
            display: inline-block;
            width: 75%;
            font-weight: 500;
            color: #555;
            border-bottom: 2px solid #ff7300;
        }
        
        .encabezado__cotizacion-subtitle {
            margin-top: 5px;
            color: #555;
            font-weight: 500;
        }

        .encabezado__validez {
            color: #ff7300;
            font-weight: 700;
            font-size: 13px;
        }

        .encabezado__no-cotizaion {
            color: red;
            font-weight: 700;
            font-size: 18px;
        }

        .footer {
            width: 115%;
            height: 125px;
            position: fixed;
            bottom: -30px;
            left: -50px;
            right: -50px;

            text-align: center;
        }

        .footer__web {
            height: 70px;
            background-color: #141313;
            border-top: solid 10px #ff7300;
        }

        .footer__web p {
            color: white;
        }

        .footer__web > p {
            margin-top: 35px;
            margin-bottom: 0;
        }

        .datos-cliente,
        .datos-generales-tour {
            width: 100%;
            border: solid 1px #f1f1f1;
            padding: 10px;
            margin-bottom: 20px;
        }

        .datos-cliente p {
            margin: 0;
        }

        .tabla-servicios__encabezado {
            background-color: #ff7300;
            padding: 5px 0;
        }

        .tabla-servicios__encabezado p {
            color: white;
            margin: 0;
            text-align: center;
        }


        .tabla-servicios__totales {
            margin-top: 10px;
        }

        .tabla-servicios__totales .tabla-servicios__totales-info-bancaria {
            border-right: solid 1px #f1f1f1;
        }

        .tabla-servicios__totales .tabla-servicios__totales-desglose {
            width: 100%;
            vertical-align: top;
            padding-left: 20px;
        }
        
        .tabla-servicios__totales .tabla-servicios__totales-desglose p {
            width: 100%;
            margin: 0;
        }

        .tabla-servicios__totales-condiciones {
            font-size: 12px;
            margin: 0;
            font-style: italic;
            font-weight: 700;
        }

        .negrita {
            font-weight: 700;
        }

        .border {
            border: solid 1px #f1f1f1;
        }

        .border-inline {
            border-left: solid 1px #f1f1f1;
            border-right: solid 1px #f1f1f1;
        }

        .p10 {
            padding: 10px;
        }
    </style>
</head>
<body>
    <header class="encabezado">
        <div class="encabezado__razon-social">
            <div class="encabezado__logo">
                <img src="./public/medios/img/logo-lexgotransfers.png" alt="Logo Lex Go Tranfers">
            </div>

            <div class="encabezado__razon-social-info">
                <p>Transportadora Maya Tours SA de CV</p>
                <p>TMSJIKHBFGUSDGBD2564</p>
                <p>Calle 5 #280 x 38 y 40 Fracc. Campestre Mérida Yucatán.</p>
            </div>
        </div>

        <div class="encabezado__fecha">
            <h2 class="encabezado__cotizacion-title">Cotización</h2>
            <h3 class="encabezado__cotizacion-subtitle"> Fecha: 14/08/2021</h3>
            <h4 class="encabezado__validez">Validez hasta 20/08/2021</h4>
            <p class="encabezado__no-cotizaion">005892</p>
        </div>
    </header>

    <section class="contenedor">
        <table class="datos-cliente" style="width: 100%;">
            <tbody>
                <tr>
                    <td style="width: 60%;"><p>Cliente: Josecito Poderoso</p></td>
                    <td style="width: 40%;"><p>Asesor (a): Alexander Lopez <span style="font-size: 50%;">tel:9992590694</span> </p></td>
                </tr>

                <tr>
                    <td style="width: 60%;"><p>Domicilio: Tu corazón <3 </p></td>
                    <td style="width: 40%;"><p>Tel: 1234567890 </p></td>
                </tr>

                <tr>
                    <td style="width: 60%;"><p>Colonia:</p></td>
                    <td style="width: 40%;"><p>e-Mail: reybsandoval@gmail.com</p></td>
                </tr>
            </tbody>
        </table>

        <div class="datos-generales-tour">
            <table style="width: 100%;">
                <tbody>
                    <tr>
                        <td style="width: 60%;"><p>Tour: Xel-há</p></td>
                        <td style="width: 40%;"><p>Fecha a cotizar: Octubre</p></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="tabla-servicios">
            <table class="border" style="width: 100%; text-align: center; border-spacing: 0;">
                <thead class="tabla-servicios__encabezado">
                    <tr>
                        <td style="width: 20%;"><p>Pax</p></td>
                        <td style="width: 40%;"><p>Descripción</p></td>
                        <td style="width: 20%;"><p>P/P <span style="font-size: 65%;">MXN</span></p></td>
                        <td style="width: 20%;"><p>Total <span style="font-size: 65%;">MXN</span></p></td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border-inline" style="width: 20%;"><p>5</p></td>
                        <td class="border-inline" style="width: 40%;"><p>Adultos</p></td>
                        <td class="border-inline" style="width: 20%;"><p>$2,971.79</p></td>
                        <td class="border-inline" style="width: 20%;"><p>$14,485.79</p></td>
                    </tr>

                    <tr>
                        <td class="border-inline" style="width: 20%;"><p>1</p></td>
                        <td class="border-inline" style="width: 40%;"><p>Transportación Luxury Suburban</p></td>
                        <td class="border-inline" style="width: 20%;"><p>$1,485.89</p></td>
                        <td class="border-inline" style="width: 20%;"><p>$1,485.89</p></td>
                    </tr>

                    <tr>
                        <td class="border" colspan="3"><p style="text-align: left;"><span class="negrita">Comentarios: </span>Proin consequat lorem tortor, in sagittis felis sodales id. Vivamus sed turpis eu lectus ornare tincidunt finibus eget magna. Pellentesque nec urna erat. Cras eget eros lectus. </p></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>

            <div class="tabla-servicios__totales border p10">
                <table style="width: 100%;">
                    <tbody>
                        <tr>
                            <td style="width: 60%;">
                                <div class="tabla-servicios__totales-info-bancaria">
                                    <p style="margin: 7px 0;">Método de pago</p>
                                    <p style="margin: 0; margin-bottom: 20px;">Condiciones de pago:</p>

                                    <p class="tabla-servicios__totales-condiciones">**Las estancias pueden variar según disponibilidad</p>
                                    <p class="tabla-servicios__totales-condiciones">**Precios sujetos a cambios sin previo aviso</p>
                                </div>
                            </td>
                            <td style="width: 40%;">
                                <div class="tabla-servicios__totales-desglose">
                                    <div style="display: inline-block; width: 48%; text-align: right;">
                                        <p>Sub-total: </p>
                                        <p class="negrita">TOTAL: </p>
                                        <p>Aparta 20%: </p>
                                    </div>

                                    <div style="display: inline-block; width: 40%; text-align: right; padding-right: 20px;">
                                        <p><span class="negrita">$16,344.84</span></p>
                                        <p><span class="negrita">$16,344.84</span></p>
                                        <p><span class="negrita">$3,268.84</span></p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <footer class="footer">
        <p>reservaciones@lexgotransfers.com</p>

        <div class="footer__web">
            <p>www.lexgo<span style="color: #ff7300;">transfers</span>.com</p>
        </div>
    </footer>
</body>
</html>