(function() {
    document.addEventListener("DOMContentLoaded", function() {
        let cantidades = document.getElementById("submenu-qty");
        let fechas = document.getElementById("datefilter");
        let locationInput = document.getElementById('location');

        let formFiltro = document.getElementById("formFiltro");

        let lat, lng;
        let banderaVenobox = 0;

        /* *************************************** */
        /* ************** FUNCIONES ************** */
        /* *************************************** */
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
            
            const fechaCheckIn  = new Date(checkIn);
            const fechaActual   = new Date();

            if (fechaCheckIn < fechaActual) {
                Swal.fire(
                    'Fechas incorrectas',
                    'La fecha de entrada no puede ser menor que la fecha actual...',
                    'info'
                )

                event.preventDefault();
                return;
            }

            let checkOut = txtFechas[1].split("/").reverse().join("-");

            lat = Number(locationInput.getAttribute("lat"));
            lng = Number(locationInput.getAttribute("lng"));

            let jsonInfoSesion = {
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

            let personas = 0, habitaciones = 0;
            for (const objeto of occupancies) {
                personas        += Number(objeto.adults);
                personas        += Number(objeto.children);
                habitaciones    += Number(objeto.rooms);
            }

            let txtSweet = (habitaciones    > 1) ? `<p class="fs-6 mb-2 text-center">${habitaciones} habitaciones` : `<p class="fs-6 mb-2 text-center">${habitaciones} habitación`;
            txtSweet    += (personas        > 1) ? ` para ${personas} personas</p>`: ` para ${personas} persona</p>`;

            Swal.fire({
            title: 'Buscando Hoteles...',
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
            let xhrSesion = new XMLHttpRequest();

            xhrSesion.onreadystatechange = function() {
                if (xhrSesion.readyState == 4) {
                    if (xhrSesion.status == 200) {
                        window.location = "./../motordebusqueda";
                    } else {
                        console.log("Ups, algo salió mal, por favor, vuelva a intentar...");
                    }
                }
            };

            // console.log(document.querySelector("meta[name=Csrf]").content);
            // console.log(jsonInfoSesion);

            xhrSesion.open("POST", `./../admin/privado/api/v1/sesion`, true);
            xhrSesion.setRequestHeader('Csrf', document.querySelector("meta[name=Csrf]").content);
            xhrSesion.send(JSON.stringify(jsonInfoSesion));
        }

        let insertarTarjetaHotel = function(divPais, respuesta, cont, img) {
            let ubicacion = `https://www.google.com/maps/embed/v1/search?key=AIzaSyCS7kK67VZxjgmzINsI1_C4zamwkNaUaD4&q=${respuesta.name[cont].replaceAll("&", "and")}+${respuesta.city[cont]}&center=${respuesta.latitude[cont]},${respuesta.longitude[cont]}&zoom=18`;
            let txtEstrellas = "",
                estrellas;

            estrellas = respuesta.categoryCode[cont];
            estrellas = Number(estrellas[0]);

            for (let j = 0; j < estrellas; j++) {
                txtEstrellas += '<img class="star-hotel mt-1" style="height: 17px;" src="medios/img/Estrellarecomendacionesgrande.png" alt="">';
            }

            let template = `
                            <div class="col-md-3 tar-hotel-container">
                                <div class="tar-hotel">
                                    <div id="img-cont-hotel" class="" style="width: 100%; height: 363px;background-size:cover; background-image:url('${img}');">
                                        <img id="franja-decoration1" class="float-start" src="medios/img/Franjasuperior1.png" alt="">
                                        <img class="mt-3 me-3 float-end" src="medios/img/lexgotravelblancochico.png" alt="">
                                        <img id="allinclusive-decoration" class="mt-3 me-3 float-start" src="medios/img/allinclusive2.png" alt="">
                                        <img id="franja-decoration2" class="float-end" src="medios/img/Franjalateralconoceelmundo.png" alt="">
                                    </div>
                                    <div class="cont-tit-hot p-1"><h2 class="mt-3 fw-bold ">${respuesta.name[cont]}</h2></div>
                                    <div class="row p-3">
                                        <div class="col-md-7 ">
                                            <p class="mb-2 border-4 fw-bold" style="font-size:14px; border-bottom: solid #228ce3; color:#228ce3;">${respuesta.city[cont]}·<a data-gall="iframe" class="venoboxframe" data-vbtype="iframe" href=${encodeURI(ubicacion)}> Mostrar en mapa</a></p>
                                            
                                            <div class="nav align-middle">
                                                <h5>HOTEL</h5>
                                                ${txtEstrellas}
                                            </div>
                                            
                                        </div>
                                        <div class="col-md-5 text-center">
                                            <a href="./../detalleshotel?code=${respuesta.code[cont]}" class="btn w-100 rounded-0 text-white fw-bold" style="background: #e3223e;">Ver más</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            `;

            document.getElementById(divPais).firstElementChild.insertAdjacentHTML('beforeend', template);
        }

        /* *************************************** */
        /* *************** EVENTOS *************** */
        /* *************************************** */
        formFiltro.addEventListener("submit", () => { enviarFormulario(event) });
        fechas.addEventListener("focus", (e) => {e.target.blur();})

        // ==================================
        // ======= FUNCTIONALITY CODE =======
        // ==================================
        let xhrTopEspania = new XMLHttpRequest();

        // Procesamiento de la respuesta de la peticion HTTP del formulario
        xhrTopEspania.onreadystatechange = function() {
            if (xhrTopEspania.readyState == 4) {
                if (xhrTopEspania.status == 200) {
                    let respuesta = JSON.parse(xhrTopEspania.responseText);

                    for (let i = 0, l = respuesta.id.length; i < l; i++) {
                        let testerImg = new Image();
                        testerImg.src = respuesta.imgweb[i];

                        testerImg.onload = () => { template = insertarTarjetaHotel("hotelesEspania", respuesta, i, respuesta.imgweb[i]) };
                        testerImg.onerror = () => { template = insertarTarjetaHotel("hotelesEspania", respuesta, i, "medios/img/not-found.png") };
                    }

                    banderaVenobox += 1;

                    if (banderaVenobox > 2) {
                        setTimeout(function() {
                            $('.venoboxframe').venobox();
                        }, 2000);
                    }

                } else {
                    console.log("Ups, algo salió mal, por favor, vuelva a intentar...");
                }
            }
        };

        xhrTopEspania.open("GET", "./../admin/privado/api/v1/ranking?code=ES", true);
        xhrTopEspania.send();

        let xhrTopMexico = new XMLHttpRequest();

        // Procesamiento de la respuesta de la peticion HTTP del formulario
        xhrTopMexico.onreadystatechange = function() {
            if (xhrTopMexico.readyState == 4) {
                if (xhrTopMexico.status == 200) {
                    let respuesta = JSON.parse(xhrTopMexico.responseText);

                    for (let i = 0, l = respuesta.id.length; i < l; i++) {
                        let testerImg = new Image();
                        testerImg.src = respuesta.imgweb[i];

                        testerImg.onload = () => { template = insertarTarjetaHotel("hotelesMexico", respuesta, i, respuesta.imgweb[i]) };
                        testerImg.onerror = () => { template = insertarTarjetaHotel("hotelesMexico", respuesta, i, "medios/img/not-found.png") };
                    }

                    banderaVenobox += 1;

                    if (banderaVenobox > 2) {
                        setTimeout(function() {
                            $('.venoboxframe').venobox();
                        }, 1000);
                    }

                } else {
                    console.log("Ups, algo salió mal, por favor, vuelva a intentar...");
                }
            }
        };

        xhrTopMexico.open("GET", "./../admin/privado/api/v1/ranking?code=MX", true);
        xhrTopMexico.send();

        let xhrTopUSA = new XMLHttpRequest();

        // Procesamiento de la respuesta de la peticion HTTP del formulario
        xhrTopUSA.onreadystatechange = function() {
            if (xhrTopUSA.readyState == 4) {
                if (xhrTopUSA.status == 200) {
                    let respuesta = JSON.parse(xhrTopUSA.responseText);

                    for (let i = 0, l = respuesta.id.length; i < l; i++) {
                        let testerImg = new Image();
                        testerImg.src = respuesta.imgweb[i];

                        testerImg.onload = () => { template = insertarTarjetaHotel("hotelesUSA", respuesta, i, respuesta.imgweb[i]) };
                        testerImg.onerror = () => { template = insertarTarjetaHotel("hotelesUSA", respuesta, i, "medios/img/not-found.png") };
                    }

                    banderaVenobox += 1;

                    if (banderaVenobox > 2) {
                        setTimeout(function() {
                            $('.venoboxframe').venobox();
                        }, 2000);
                    }
                } else {
                    console.log("Ups, algo salió mal, por favor, vuelva a intentar...");
                }
            }
        };

        xhrTopUSA.open("GET", "./../admin/privado/api/v1/ranking?code=US", true);
        xhrTopUSA.send();

        if (document.getElementById("li.nav-item.dropdown")) {
            $('li.nav-item.dropdown').on('click', function(event) {
                // The event won't be propagated up to the document NODE and 
                // therefore delegated events won't be fired
                event.stopPropagation();
            });
        }
    });
})();