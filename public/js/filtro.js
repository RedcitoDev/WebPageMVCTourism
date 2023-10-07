(function () {
    document.addEventListener("DOMContentLoaded", function () {
        if (window.location.href.split("?")[0].split("/").pop() === 'motordebusqueda' || window.location.href.split("?")[0].split("/").pop() === 'motordebusqueda.php') {
            let cantidadHabitacionesParagraph       = document.getElementById("cantidadHabitaciones");
            let cantidadAdultosParagraph            = document.getElementById("cantidadAdultos");
            let cantidadMenoresParagraph            = document.getElementById("cantidadMenores");
            let agregar                             = document.getElementById('agregar');
            let contenido                           = document.getElementById('submenu-qty');

            let btnMasAdultos                       = document.getElementsByClassName("plusAdults");
            let btnMenosAdultos                     = document.getElementsByClassName("minusAdults");
            let btnMasMenores                       = document.getElementsByClassName("plusMinors");
            let btnMenosMenores                     = document.getElementsByClassName("minusMinors");
            
            let subMenuQty                          = document.getElementsByClassName("submenu-items");

            let checkInInput                        = document.getElementById("checkIn");
            let checkOutInput                       = document.getElementById("checkOut");
            let cantidadHabitaciones = 0, cantidadAdultos = 0, cantidadMenores = 0;
            
            /* *************************************** */
            /* ************** FUNCIONES ************** */
            /* *************************************** */
            let iniciarCantidadesFiltro = function() {
                // AJAX para obtener los datos de la sesión
                let xhrSesion = new XMLHttpRequest();
        
                // Procesamiento de la respuesta de la peticion HTTP del formulario
                xhrSesion.onreadystatechange = function() {
                    if(xhrSesion.readyState == 4) {
                        if(xhrSesion.status == 200) {
                            let respuesta = JSON.parse(xhrSesion.responseText);

                            if(respuesta.hasOwnProperty("placesAutocomplete")) {
                                placesAutocomplete.setVal(respuesta.placesAutocomplete);
                                document.getElementById('location').setAttribute("lat", respuesta.geolocation.latitude);
                                document.getElementById('location').setAttribute("lng", respuesta.geolocation.longitude);
                            }
                            
                            if(respuesta.hasOwnProperty("occupancies")){
                                let cantidades = respuesta.occupancies;

                                cantidadHabitaciones = cantidades.length, cantidadAdultos = 0, cantidadMenores = 0;

                                for (let i = 0, l = cantidadHabitaciones; i < l; i++) {
                                    cantidadAdultos += Number(cantidades[i].adults);
                                    cantidadMenores += Number(cantidades[i].children);

                                    if (i > 0) {
                                        agregarHabitacion();
                                    }

                                    // Actualizar la cantidad de adultos en su seccion correspondiente
                                    subMenuQty[i].children[0].children[0].children[0].children[0].children[1].children[0].children[1].value = cantidades[i].adults;

                                    // Actualizar la cantidad de menores en su seccion correspondiente
                                    subMenuQty[i].children[1].children[0].children[0].children[0].children[1].children[0].children[1].value = cantidades[i].children;

                                    if(cantidades[i].children) {
                                        for (let j = 0, m = cantidades[i].children; j < m; j++) {
                                            agregarEdadMenor(subMenuQty[i].children[1].children[0].children[0], j + 1);

                                            let edadMenor =  Number(cantidades[i].paxes[j].age);
                                            subMenuQty[i].children[1].children[0].children[0].lastElementChild.lastElementChild.firstElementChild.selectedIndex = edadMenor;
                                        }
                                    }
                                }

                                actualizarCantidades(cantidadHabitaciones, cantidadAdultos, cantidadMenores);
                            }

                            if(respuesta.hasOwnProperty("stay")) {
                                let checkIn     = respuesta.stay.checkIn;
                                let checkOut    = respuesta.stay.checkOut;

                                checkInInput.value = checkIn;
                                checkOutInput.value = checkOut;
                            }
                        } else {
                            console.log("Ups, algo salió mal, por favor, vuelva a intentar...");
                        }
                    }
                };
        
                xhrSesion.open("GET", `./../admin/privado/api/v1/sesion`, true);
                xhrSesion.send();
            }

            let agregarHabitacion = function() {
                let template = `<ul class="submenu-items">
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
                                </ul>`;

                contenido.insertAdjacentHTML('beforeend', template);
                clon = contenido.lastElementChild;
                
                clon.getElementsByClassName("plusAdults")[0].addEventListener("click", () => {sumarAdultos(event)});
                clon.getElementsByClassName("minusAdults")[0].addEventListener("click", () => {restarAdultos(event)});
                clon.getElementsByClassName("plusMinors")[0].addEventListener("click", () => {sumarMenores(event)});
                clon.getElementsByClassName("minusMinors")[0].addEventListener("click", () => {restarMenores(event)});

                let spanEliminarHabitacion = document.createElement("span");
                spanEliminarHabitacion.className = "badge badge-pill badge-danger text-black puntero ocultar .mt-n2";
                spanEliminarHabitacion.textContent = "Eliminar habitación";

                clon.appendChild(spanEliminarHabitacion);
            }

            let actualizarCantidades = function(qtyHabitaciones, qtyAdultos, qtyMenores) {
                cantidadHabitacionesParagraph.textContent = qtyHabitaciones;
                cantidadAdultosParagraph.textContent = qtyAdultos;
                cantidadMenoresParagraph.textContent = qtyMenores;
            }

            let obtenerCantidades = function() {
                let qtyHabitaciones = subMenuQty.length, qtyAdultos = 0, qtyMenores = 0;

                for (let habitacion of subMenuQty) {
                    qtyAdultos += Number(habitacion.children[0].children[0].children[0].children[0].children[1].children[0].children[1].value);
                    qtyMenores += Number(habitacion.children[1].children[0].children[0].children[0].children[1].children[0].children[1].value);
                }

                return [qtyHabitaciones, qtyAdultos, qtyMenores];
            }

            let sumarAdultos = function(event) {
                event.target.parentNode.querySelector('input[type=number]').stepUp();

                let [qtyHabitaciones, qtyAdultos, qtyMenores] = obtenerCantidades();
                actualizarCantidades(qtyHabitaciones, qtyAdultos, qtyMenores);
            }

            let restarAdultos = function(event) {
                event.target.parentNode.querySelector('input[type=number]').stepDown();

                let [qtyHabitaciones, qtyAdultos, qtyMenores] = obtenerCantidades();
                actualizarCantidades(qtyHabitaciones, qtyAdultos, qtyMenores);
            }

            let sumarMenores = function(event) {
                event.target.parentNode.children[1].stepUp();
                let numeroMenor = event.target.parentNode.children[1].value;

                agregarEdadMenor(event.target.parentNode.parentNode.parentNode.parentNode, numeroMenor);
                
                let [qtyHabitaciones, qtyAdultos, qtyMenores] = obtenerCantidades();
                actualizarCantidades(qtyHabitaciones, qtyAdultos, qtyMenores);
            }

            let restarMenores = function(event) {
                event.target.parentNode.children[1].stepDown();

                let contenedor = event.target.parentNode.parentNode.parentNode.parentNode;

                if (contenedor.childElementCount > 1) {
                    contenedor.removeChild(contenedor.lastElementChild);
                }

                let [qtyHabitaciones, qtyAdultos, qtyMenores] = obtenerCantidades();
                actualizarCantidades(qtyHabitaciones, qtyAdultos, qtyMenores);
            }

            let agregarEdadMenor = function(elemento, numeroMenor) {
                let templateEdad = `<div class="row justify-content-between align-items-center mb-2 contenedorSelectMenor">
                                        <div class="col-6 ms-3 p-0">
                                            <p>Edad del menor ${numeroMenor}</p> 
                                        </div>

                                        <div class="col-4 p-0">
                                            <select class="form-select" aria-label="Default select example">
                                                <option selected disabled>Edad</option>
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                                <option value="6">6</option>
                                                <option value="7">7</option>
                                                <option value="8">8</option>
                                                <option value="9">9</option>
                                                <option value="10">10</option>
                                                <option value="11">11</option>
                                                <option value="12">12</option>
                                                <option value="13">13</option>
                                                <option value="14">14</option>
                                                <option value="15">15</option>
                                                <option value="16">16</option>
                                                <option value="17">17</option>
                                            </select>
                                        </div>
                                    </div>`;

                elemento.insertAdjacentHTML('beforeend', templateEdad);
            }

            /* *************************************** */
            /* *************** EVENTOS *************** */
            /* *************************************** */        
            agregar.addEventListener('click', e => {
                e.preventDefault();

                agregarHabitacion();

                let [qtyHabitaciones, qtyAdultos, qtyMenores] = obtenerCantidades();
                actualizarCantidades(qtyHabitaciones, qtyAdultos, qtyMenores);
            });

            contenido.addEventListener('click', e =>{
                e.preventDefault();
                
                if(e.target.classList.contains('puntero')){
                    let contenedor  = e.target.parentNode;
                
                    contenedor.parentNode.removeChild(contenedor);
                }

                let [qtyHabitaciones, qtyAdultos, qtyMenores] = obtenerCantidades();
                actualizarCantidades(qtyHabitaciones, qtyAdultos, qtyMenores);
            });

            for (let btn of btnMasAdultos) {
                btn.addEventListener("click", () => {sumarAdultos(event)});
            }

            for (let btn of btnMenosAdultos) {
                btn.addEventListener("click", () => {restarAdultos(event)});
            }

            for (let btn of btnMasMenores) {
                btn.addEventListener("click", () => {sumarMenores(event)});
            }

            for (let btn of btnMenosMenores) {
                btn.addEventListener("click", () => {restarMenores(event)});
            }

            let placesAutocomplete = places({
                container: document.querySelector('#location'),
                type: 'city'
            });
    
            placesAutocomplete.on('change', e => {
                document.getElementById('location').setAttribute("lat", e.suggestion.latlng.lat);
                document.getElementById('location').setAttribute("lng", e.suggestion.latlng.lng);
            });

            iniciarCantidadesFiltro();
        } else {
            let cantidadHabitacionesParagraph       = document.getElementById("cantidadHabitaciones");
            let cantidadAdultosParagraph            = document.getElementById("cantidadAdultos");
            let cantidadMenoresParagraph            = document.getElementById("cantidadMenores");
            let agregar                             = document.getElementById('agregar');
            let contenido                           = document.getElementById('submenu-qty');
            let fechas                              = document.getElementById("datefilter");

            let btnMasAdultos                       = document.getElementsByClassName("plusAdults");
            let btnMenosAdultos                     = document.getElementsByClassName("minusAdults");
            let btnMasMenores                       = document.getElementsByClassName("plusMinors");
            let btnMenosMenores                     = document.getElementsByClassName("minusMinors");
            
            let subMenuQty                          = document.getElementsByClassName("submenu-items");
            let cantidadHabitaciones = 0, cantidadAdultos = 0, cantidadMenores = 0;
            
            /* *************************************** */
            /* ************** FUNCIONES ************** */
            /* *************************************** */
            let iniciarCantidadesFiltro = function() {
                // AJAX para obtener los datos de la sesión
                let xhrSesion = new XMLHttpRequest();
        
                // Procesamiento de la respuesta de la peticion HTTP del formulario
                xhrSesion.onreadystatechange = function() {
                    if(xhrSesion.readyState == 4) {
                        if(xhrSesion.status == 200) {
                            let respuesta = JSON.parse(xhrSesion.responseText);

                            if(respuesta.hasOwnProperty("placesAutocomplete")) {
                                placesAutocomplete.setVal(respuesta.placesAutocomplete);
                                document.getElementById('location').setAttribute("lat", respuesta.geolocation.latitude);
                                document.getElementById('location').setAttribute("lng", respuesta.geolocation.longitude);
                            }
                            
                            if(respuesta.hasOwnProperty("occupancies")){
                                let cantidades = respuesta.occupancies;

                                cantidadHabitaciones = cantidades.length, cantidadAdultos = 0, cantidadMenores = 0;

                                for (let i = 0, l = cantidadHabitaciones; i < l; i++) {
                                    cantidadAdultos += Number(cantidades[i].adults);
                                    cantidadMenores += Number(cantidades[i].children);

                                    if (i > 0) {
                                        agregarHabitacion();
                                    }

                                    // Actualizar la cantidad de adultos en su seccion correspondiente
                                    subMenuQty[i].children[0].children[0].children[0].children[0].children[1].children[0].children[1].value = cantidades[i].adults;

                                    // Actualizar la cantidad de menores en su seccion correspondiente
                                    subMenuQty[i].children[1].children[0].children[0].children[0].children[1].children[0].children[1].value = cantidades[i].children;

                                    if(cantidades[i].children) {
                                        for (let j = 0, m = cantidades[i].children; j < m; j++) {
                                            agregarEdadMenor(subMenuQty[i].children[1].children[0].children[0], j + 1);

                                            let edadMenor =  Number(cantidades[i].paxes[j].age);
                                            subMenuQty[i].children[1].children[0].children[0].lastElementChild.lastElementChild.firstElementChild.selectedIndex = edadMenor;
                                        }
                                    }
                                }

                                actualizarCantidades(cantidadHabitaciones, cantidadAdultos, cantidadMenores);
                            }

                            if(respuesta.hasOwnProperty("stay")) {
                                let checkIn     = respuesta.stay.checkIn;
                                let checkOut    = respuesta.stay.checkOut;

                                checkIn     = checkIn.split("-").reverse().join("/");
                                checkOut    = checkOut.split("-").reverse().join("/");

                                fechas.value = `${checkIn} - ${checkOut}`;
                            }
                        } else {
                            console.log("Ups, algo salió mal, por favor, vuelva a intentar...");
                        }
                    }
                };
        
                xhrSesion.open("GET", `./../admin/privado/api/v1/sesion`, true);
                xhrSesion.send();
            }

            let agregarHabitacion = function() {
                let template = `<ul class="submenu-items">
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
                                </ul>`;

                contenido.insertAdjacentHTML('beforeend', template);
                clon = contenido.lastElementChild;
                
                clon.getElementsByClassName("plusAdults")[0].addEventListener("click", () => {sumarAdultos(event)});
                clon.getElementsByClassName("minusAdults")[0].addEventListener("click", () => {restarAdultos(event)});
                clon.getElementsByClassName("plusMinors")[0].addEventListener("click", () => {sumarMenores(event)});
                clon.getElementsByClassName("minusMinors")[0].addEventListener("click", () => {restarMenores(event)});

                let spanEliminarHabitacion = document.createElement("span");
                spanEliminarHabitacion.className = "badge badge-pill badge-danger text-black puntero";
                spanEliminarHabitacion.textContent = "Eliminar habitación";

                clon.appendChild(spanEliminarHabitacion);
            }

            let actualizarCantidades = function(qtyHabitaciones, qtyAdultos, qtyMenores) {
                cantidadHabitacionesParagraph.textContent = qtyHabitaciones;
                cantidadAdultosParagraph.textContent = qtyAdultos;
                cantidadMenoresParagraph.textContent = qtyMenores;
            }

            let obtenerCantidades = function() {
                let qtyHabitaciones = subMenuQty.length, qtyAdultos = 0, qtyMenores = 0;

                for (let habitacion of subMenuQty) {
                    qtyAdultos += Number(habitacion.children[0].children[0].children[0].children[0].children[1].children[0].children[1].value);
                    qtyMenores += Number(habitacion.children[1].children[0].children[0].children[0].children[1].children[0].children[1].value);
                }

                return [qtyHabitaciones, qtyAdultos, qtyMenores];
            }

            let sumarAdultos = function(event) {
                event.target.parentNode.querySelector('input[type=number]').stepUp();

                let [qtyHabitaciones, qtyAdultos, qtyMenores] = obtenerCantidades();
                actualizarCantidades(qtyHabitaciones, qtyAdultos, qtyMenores);
            }

            let restarAdultos = function(event) {
                event.target.parentNode.querySelector('input[type=number]').stepDown();

                let [qtyHabitaciones, qtyAdultos, qtyMenores] = obtenerCantidades();
                actualizarCantidades(qtyHabitaciones, qtyAdultos, qtyMenores);
            }

            let sumarMenores = function(event) {
                event.target.parentNode.children[1].stepUp();
                let numeroMenor = event.target.parentNode.children[1].value;

                agregarEdadMenor(event.target.parentNode.parentNode.parentNode.parentNode, numeroMenor);
                
                let [qtyHabitaciones, qtyAdultos, qtyMenores] = obtenerCantidades();
                actualizarCantidades(qtyHabitaciones, qtyAdultos, qtyMenores);
            }

            let restarMenores = function(event) {
                event.target.parentNode.children[1].stepDown();

                let contenedor = event.target.parentNode.parentNode.parentNode.parentNode;

                if (contenedor.childElementCount > 1) {
                    contenedor.removeChild(contenedor.lastElementChild);
                }

                let [qtyHabitaciones, qtyAdultos, qtyMenores] = obtenerCantidades();
                actualizarCantidades(qtyHabitaciones, qtyAdultos, qtyMenores);
            }

            let agregarEdadMenor = function(elemento, numeroMenor) {
                let templateEdad = `<div class="row justify-content-between align-items-center mb-2 contenedorSelectMenor">
                                        <div class="col-6 ms-3 p-0">
                                            <p>Edad del menor ${numeroMenor}</p> 
                                        </div>

                                        <div class="col-4 p-0">
                                            <select class="form-select" required>
                                                <option selected disabled>Edad</option>
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                                <option value="6">6</option>
                                                <option value="7">7</option>
                                                <option value="8">8</option>
                                                <option value="9">9</option>
                                                <option value="10">10</option>
                                                <option value="11">11</option>
                                                <option value="12">12</option>
                                                <option value="13">13</option>
                                                <option value="14">14</option>
                                                <option value="15">15</option>
                                                <option value="16">16</option>
                                                <option value="17">17</option>
                                            </select>
                                        </div>
                                    </div>`;

                elemento.insertAdjacentHTML('beforeend', templateEdad);
            }

            /* *************************************** */
            /* *************** EVENTOS *************** */
            /* *************************************** */        
            agregar.addEventListener('click', e => {
                e.preventDefault();

                agregarHabitacion();

                let [qtyHabitaciones, qtyAdultos, qtyMenores] = obtenerCantidades();
                actualizarCantidades(qtyHabitaciones, qtyAdultos, qtyMenores);
            });

            contenido.addEventListener('click', e =>{
                e.preventDefault();
                
                if(e.target.classList.contains('puntero')){
                    let contenedor  = e.target.parentNode;
                
                    contenedor.parentNode.removeChild(contenedor);
                }

                let [qtyHabitaciones, qtyAdultos, qtyMenores] = obtenerCantidades();
                actualizarCantidades(qtyHabitaciones, qtyAdultos, qtyMenores);
            });

            for (let btn of btnMasAdultos) {
                btn.addEventListener("click", () => {sumarAdultos(event)});
            }

            for (let btn of btnMenosAdultos) {
                btn.addEventListener("click", () => {restarAdultos(event)});
            }

            for (let btn of btnMasMenores) {
                btn.addEventListener("click", () => {sumarMenores(event)});
            }

            for (let btn of btnMenosMenores) {
                btn.addEventListener("click", () => {restarMenores(event)});
            }

            // ==================================
            // ======= FUNCTIONALITY CODE =======
            // ==================================
            let placesAutocomplete = places({
                container: document.querySelector('#location'),
                type: 'city'
            });
    
            placesAutocomplete.on('change', e => {
                document.getElementById('location').setAttribute("lat", e.suggestion.latlng.lat);
                document.getElementById('location').setAttribute("lng", e.suggestion.latlng.lng);
            });

            iniciarCantidadesFiltro();
        }
    });
})();