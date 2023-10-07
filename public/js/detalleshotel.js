(function() {
    document.addEventListener("DOMContentLoaded", function() {
        let cantidades = document.getElementById("submenu-qty");
        let fechas = document.getElementById("datefilter");
        let locationInput = document.getElementById('location');
        let dropDownHabitaciones = document.getElementsByClassName('dropdown_numeroHabitaciones');

        // Datos para detalles de reservación
        let drsDescuento = document.getElementById("drs-descuento");
        let drsPrecioPersona = document.getElementById("drs-precio-persona");
        let drsPrecioTotal = document.getElementById("drs-precio-total");
        let drsInfoReserva = document.getElementById("drs-info-reserva");
        let drsListaPersonas = document.getElementById("drs-lista-personas");
        let drsListaHabitaciones = document.getElementById("drs-lista-habitaciones");

        let formFiltro = document.getElementById("formFiltro");

        let lat, lng, checkRateArr = [],
            checkRateArrObj = [],
            hotelCode = "",
            tarjetasHotel;
        let precioOriginal, precioDescuento, precioPersona, descuentoTotalAplicado = 0,
            precioTotalPersona = 0,
            precioTotal = 0;
        let cantidadHabitaciones = 0,
            cantidadAdultos = 0,
            cantidadMenores = 0;

        let regexp = /(\d+)/g;

        // Mensaje de confirmacion de habitacion añadida
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 1500,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        let xhrSesionPost = new XMLHttpRequest();

        /* *************************************** */
        /* ************** FUNCIONES ************** */
        /* *************************************** */
        let formatearNumero = function(numero) {
            return String(numero.toFixed(2)).replace(/(?<!\..*)(\d)(?=(?:\d{3})+(?:\.|$))/g, '$1,')
        }

        let unicodeBase64Decode = function(text) {
            return decodeURIComponent(Array.prototype.map.call(atob(text), function(c) { return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2); }).join(''));
        }


        let enviarFormulario = function(event) {
            event.preventDefault();

            // Procesamiento de las cantidades de habitaciones y personas
            let occupancies = [];
            
            for (let lista of cantidades.children) {
                
                let cantidadAdultos = lista.querySelectorAll('input.quantity')[0].value;
                let divMenores      = lista.children[1].firstElementChild.firstElementChild;

                let cantidadMenores = divMenores.querySelectorAll('input.quantity')[0].value;

                let objeto = {
                    rooms: 1,
                    adults: cantidadAdultos,
                    children: cantidadMenores
                };
                
                if (cantidadMenores) {
                    let paxes       = [];
                    let selectEdades = divMenores.querySelectorAll('select');

                    for (let i = 0, l = selectEdades.length; i < l; i++) {
                        let selectMenores = selectEdades[i];
                        
                        if (selectMenores.options[selectMenores.selectedIndex].value == "Edad") {
                            Swal.fire(
                                'Menores en tu busqueda?',
                                'Debes especificar la edad de cada uno, esto nos servira para determinar los precios de los hoteles',
                                'info'
                            )

                            event.preventDefault();
                            return;
                        }

                        paxes.push({
                            type: "CH",
                            age: selectMenores.options[selectMenores.selectedIndex].value
                        });
                    }

                    objeto.paxes = paxes;
                }

                occupancies.push(objeto);
            }

            // Procesamiento de las fechas
            let txtFechas = fechas.value;
            // let txtFechas = "01/11/2021 - 07/11/2021";

            txtFechas = txtFechas.split("-");
            txtFechas[0] = txtFechas[0].trim();
            txtFechas[1] = txtFechas[1].trim();

            let checkIn = txtFechas[0].split("/").reverse().join("-");

            const fechaCheckIn = new Date(checkIn);
            const fechaActual = new Date();

            if (fechaCheckIn < fechaActual) {
                Swal.fire(
                    'Fechas incorrectas',
                    'La fecha de entrada no puede ser menor que la fecha actual...',
                    'info'
                )

                return;
            }

            let checkOut = txtFechas[1].split("/").reverse().join("-");

            lat = Number(locationInput.getAttribute("lat"));
            lng = Number(locationInput.getAttribute("lng"));

            let jsonFormulario = {
                stay: {
                    checkIn,
                    checkOut
                },
                occupancies: occupancies,
                geolocation: {
                    latitude: lat,
                    longitude: lng,
                    radius: 30,
                    unit: "km"
                },
                placesAutocomplete: locationInput.value
            }

            xhrSesionPost.onreadystatechange = function() {
                if (xhrSesionPost.readyState == 4) {
                    if (xhrSesionPost.status == 200) {
                        location.reload();
                    } else {
                        console.log("Ups, algo salió mal, por favor, vuelva a intentar...");
                    }
                }
            };

            let personas = 0, habitaciones = 0;
            for (const objeto of occupancies) {
                personas        += Number(objeto.adults);
                personas        += Number(objeto.children);
                habitaciones    += Number(objeto.rooms);
            }

            let txtSweet = (habitaciones    > 1) ? `<p class="fs-6 mb-2 text-center">${habitaciones} habitaciones` : `<p class="fs-6 mb-2 text-center">${habitaciones} habitación`;
            txtSweet    += (personas        > 1) ? ` para ${personas} personas</p>`: ` para ${personas} persona</p>`;

            let timerInterval
            Swal.fire({
            title: 'Actualizando Búsqueda',
            html: ` <div class ="d-flex flex-column align-items-center justify-content-center">
                        <p class="fs-6 mb-2 text-center">Destino: <span class="fw-bold">${locationInput.value}</span></p>
                        ${txtSweet}
                        <p class="fs-6 text-center">Fechas: <span class="fw-bold">${txtFechas[0]}</span> >>> <span class="fw-bold">${txtFechas[1]}</span></p>
                    </div>`,
                didOpen: () => {
                    Swal.showLoading()
                },
            })

            // AJAX para guardar la seleccion en la sesion    
            xhrSesionPost.open("POST", `./../admin/privado/api/v1/sesion`, false);
            xhrSesionPost.setRequestHeader('Csrf', document.querySelector("meta[name=Csrf]").content);
            xhrSesionPost.send(JSON.stringify(jsonFormulario));
        }

        let definirRateKeySesion = function(valor) {
            let objetoNuevoRateKey;

            if (!valor.length) {
                objetoNuevoRateKey = {
                    rateKey: {

                    }
                }
            } else {
                objetoNuevoRateKey = {
                    rateKey: {
                        hotelCode,
                        tarjetasHotel,
                        precioTotal,
                        habitaciones: valor
                    }
                }
            }

            // AJAX para guardar la seleccion en la sesion        
            xhrSesionPost.open("POST", `./../admin/privado/api/v1/sesion`, true);
            xhrSesionPost.setRequestHeader('Csrf', document.querySelector("meta[name=Csrf]").content);
            xhrSesionPost.send(JSON.stringify(objetoNuevoRateKey));
        }

        let eliminarHabitacionSesion = function(event) {
            event.preventDefault();
            [descuentoResta, personaResta, precioResta] = atob(event.target.getAttribute("preciosResta")).split(",");
            let numeroHabitaciones = atob(event.target.getAttribute("qty"));

            descuentoTotalAplicado -= descuentoResta;
            precioTotalPersona -= personaResta * numeroHabitaciones;
            precioTotal -= precioResta * numeroHabitaciones;

            drsDescuento.textContent = `-MXN$${formatearNumero(descuentoTotalAplicado)}- descuento aplicado`;
            drsPrecioPersona.textContent = `-MXN$${formatearNumero(precioTotalPersona)}`;
            drsPrecioTotal.textContent = `-MXN$${formatearNumero(precioTotal)}`;

            for (let i = 0, l = checkRateArrObj.length; i < l; i++) {
                if (checkRateArrObj[i].rateKey == atob(event.target.getAttribute("rateKey"))) {
                    checkRateArr.splice(checkRateArr.indexOf(checkRateArrObj[i].rateKey, 1));
                    checkRateArrObj.splice(i, 1);
                    i--;
                    l--;
                }
            }

            definirRateKeySesion(checkRateArrObj);
            event.target.parentElement.parentElement.removeChild(event.target.parentElement);

            Toast.fire({
                icon: 'error',
                iconColor: '#ff0000',
                title: 'Habitación eliminada.'
            });
        }

        let actualizarDetallesReservaSticky = function(numeroHabitaciones, nombreHabitacion, rateKey, descuentoResta, personaResta, precioResta, tipoServicio) {
            let divResumenHabitaciones = document.createElement("div");
            divResumenHabitaciones.style.display = "flex";
            divResumenHabitaciones.style.justifyContent = "space-between";
            divResumenHabitaciones.style.alignItems = "center";

            let pListaHabitaciones = document.createElement("p");
            pListaHabitaciones.style.color = "#228ce3";
            pListaHabitaciones.textContent = "-" + numeroHabitaciones + " habitacion(es) " + nombreHabitacion + " - " + tipoServicio;
            pListaHabitaciones.style.textDecoration = "underline";
            pListaHabitaciones.style.textAlign = "left";
            pListaHabitaciones.style.width = "75%";
            pListaHabitaciones.onmouseover = function() {
                pListaHabitaciones.style.cursor = "pointer";
            }

            let aEliminarHabitacion = document.createElement("a");
            aEliminarHabitacion.textContent = "x";
            aEliminarHabitacion.setAttribute("rateKey", btoa(rateKey));
            aEliminarHabitacion.setAttribute("preciosResta", btoa(`${descuentoResta},${personaResta},${precioResta}`));
            aEliminarHabitacion.setAttribute("qty", btoa(`${numeroHabitaciones}`));
            aEliminarHabitacion.classList.add("btn-cerrar");
            aEliminarHabitacion.href = "";
            //aEliminarHabitacion.style.color = "#ff0000";
            aEliminarHabitacion.addEventListener("click", () => { eliminarHabitacionSesion(event) });


            divResumenHabitaciones.appendChild(pListaHabitaciones);
            divResumenHabitaciones.appendChild(aEliminarHabitacion);

            drsListaHabitaciones.appendChild(divResumenHabitaciones);
        }

        let iniciarDetallesReservacion = function() {
            // AJAX para obtener los datos de la sesión
            let xhrSesionGet = new XMLHttpRequest();

            // Procesamiento de la respuesta de la peticion HTTP del formulario
            xhrSesionGet.onreadystatechange = function() {
                if (xhrSesionGet.readyState == 4) {
                    if (xhrSesionGet.status == 200) {
                        let respuesta = JSON.parse(xhrSesionGet.responseText);

                        if (respuesta.hasOwnProperty("occupancies")) {
                            let cantidades = respuesta.occupancies;

                            cantidadHabitaciones = cantidades.length, cantidadAdultos = 0, cantidadMenores = 0;

                            for (let i = 0, l = cantidadHabitaciones; i < l; i++) {
                                cantidadAdultos += Number(cantidades[i].adults);
                                cantidadMenores += Number(cantidades[i].children);
                            }

                            if (cantidadAdultos > 0) {
                                let pListaPersonas = document.createElement("p");
                                pListaPersonas.style.color = "#228ce3";
                                pListaPersonas.textContent = "-" + cantidadAdultos + " Adulto(s)";

                                drsListaPersonas.appendChild(pListaPersonas);
                            }

                            if (cantidadMenores > 0) {
                                let pListaPersonas = document.createElement("p");
                                pListaPersonas.style.color = "#228ce3";
                                pListaPersonas.textContent = "-" + cantidadMenores + " Menor(es)";

                                drsListaPersonas.appendChild(pListaPersonas);
                            }
                        }

                        if (respuesta.hasOwnProperty("rateKey")) {
                            hotelCode = respuesta.rateKey.hotelCode;
                            tarjetasHotel = respuesta.rateKey.tarjetasHotel;

                            if (respuesta.rateKey.hasOwnProperty("habitaciones")) {
                                if (respuesta.rateKey.habitaciones.length > 0) {
                                    for (let i = 0, l = respuesta.rateKey.habitaciones.length; i < l; i++) {
                                        checkRateArrObj.push(respuesta.rateKey.habitaciones[i]);
                                        checkRateArr.push(respuesta.rateKey.habitaciones[i].rateKey);

                                        precioOriginal = respuesta.rateKey.habitaciones[i].precioOriginal;
                                        precioDescuento = respuesta.rateKey.habitaciones[i].precioDescuento;
                                        precioPersona = precioDescuento / (cantidadAdultos + cantidadMenores);

                                        precioTotalPersona += (precioPersona) * respuesta.rateKey.habitaciones[i].numeroHabitaciones;
                                        precioTotal += (precioDescuento) * respuesta.rateKey.habitaciones[i].numeroHabitaciones;
                                        descuentoTotalAplicado += (precioOriginal - precioDescuento) * respuesta.rateKey.habitaciones[i].numeroHabitaciones;

                                        let tipoServicio = respuesta.rateKey.habitaciones[i].tipoServicio;

                                        actualizarDetallesReservaSticky(respuesta.rateKey.habitaciones[i].numeroHabitaciones, respuesta.rateKey.habitaciones[i].nombreHabitacion, respuesta.rateKey.habitaciones[i].rateKey, (precioOriginal - precioDescuento) * respuesta.rateKey.habitaciones[i].numeroHabitaciones, precioPersona, precioDescuento, tipoServicio);
                                    }

                                    drsDescuento.textContent = `-MXN$${formatearNumero( descuentoTotalAplicado )}- descuento aplicado`;
                                    drsPrecioPersona.textContent = `-MXN$${formatearNumero( precioTotalPersona) }`;
                                    drsPrecioTotal.textContent = `-MXN$${formatearNumero( precioTotal )}`;
                                }
                            }
                        }
                    } else {
                        console.log("Ups, algo salió mal, por favor, vuelva a intentar...");
                    }
                }
            };

            xhrSesionGet.open("GET", `./../admin/privado/api/v1/sesion`, true);
            xhrSesionGet.send();
        }

        let actualizarHabitacionesSesion = function(elemento) {
            let rateKey = unicodeBase64Decode(elemento.parentElement.getAttribute("bookingratekey"));

            if (checkRateArr.indexOf(rateKey)) {
                let nombreHabitacion = unicodeBase64Decode(elemento.parentElement.getAttribute("namehabitacion"));
                let tipoServicio = unicodeBase64Decode(elemento.parentElement.getAttribute("tipoServicio"));

                let numeroHabitaciones = elemento.textContent.split("(")[0].trim();
                let divPrecios = elemento.parentElement.parentElement.parentElement;
                let politicasCancelacion = JSON.parse(unicodeBase64Decode(elemento.parentElement.getAttribute("cancelaciones")));

                precioOriginal = Number(divPrecios.children[1].textContent.match(regexp).join(""));
                precioDescuento = Number(divPrecios.children[2].textContent.match(regexp).join(""));
                precioPersona = Number(divPrecios.children[4].textContent.match(regexp).join(""));

                if (window.location.href.split("?")[1].split("=")[1] != hotelCode) {
                    checkRateArr = [];
                    checkRateArrObj = [];
                    hotelCode = window.location.href.split("?")[1].split("=")[1];
                    tarjetasHotel = JSON.parse(unicodeBase64Decode(elemento.parentElement.getAttribute("tarjetashotel")));

                    precioTotalPersona = (precioPersona) * numeroHabitaciones;
                    precioTotal = (precioDescuento) * numeroHabitaciones;
                    descuentoTotalAplicado = (precioOriginal - precioDescuento) * numeroHabitaciones;

                    drsListaHabitaciones.innerHTML = "";
                } else {
                    precioTotalPersona += (precioPersona) * numeroHabitaciones;
                    precioTotal += (precioDescuento) * numeroHabitaciones;
                    descuentoTotalAplicado += (precioOriginal - precioDescuento) * numeroHabitaciones;
                }

                drsDescuento.textContent = `-MXN$${formatearNumero(descuentoTotalAplicado)}- descuento aplicado`;
                drsPrecioPersona.textContent = `-MXN$${formatearNumero(precioTotalPersona)}`;
                drsPrecioTotal.textContent = `-MXN$${formatearNumero(precioTotal)}`;

                actualizarDetallesReservaSticky(numeroHabitaciones, nombreHabitacion, rateKey, (precioOriginal - precioDescuento) * numeroHabitaciones, precioPersona, precioDescuento, tipoServicio);

                checkRateArr.push(rateKey);
                checkRateArrObj.push({
                    nombreHabitacion,
                    tipoServicio,
                    numeroHabitaciones,
                    precioOriginal,
                    precioDescuento,
                    politicasCancelacion,
                    rateKey,
                });

                let result = checkRateArrObj.filter((item, index) => {
                    return checkRateArr.indexOf(item.rateKey) === index;
                });

                definirRateKeySesion(result);

                Toast.fire({
                    icon: 'success',
                    iconColor: '#228ce3',
                    title: 'Habitación añadida...'
                });
            } else {
                Toast.fire({
                    icon: 'error',
                    iconColor: '#f8bb86',
                    title: 'Habitación ya añadida...'
                });
            }

        }

        /* *************************************** */
        /* *************** EVENTOS *************** */
        /* *************************************** */
        formFiltro.addEventListener("submit", () => { enviarFormulario(event) });

        for (let dropDown of dropDownHabitaciones) {
            for (let i = 2, l = dropDown.childElementCount; i < l; i++) {
                dropDown.children[i].addEventListener("click", () => {
                    event.preventDefault();
                    actualizarHabitacionesSesion(dropDown.children[i]);
                });
            }
        }

        // ==================================
        // ======= FUNCTIONALITY CODE =======
        // ==================================
        $(document).ready(function() {
            $('.venoboxframe').venobox();
        });

        iniciarDetallesReservacion();
    });
})();