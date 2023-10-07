(function () {
    document.addEventListener("DOMContentLoaded", function () {
        let cantidades              = document.getElementById("submenu-qty");
        let checkInInput            = document.getElementById("checkIn");
        let checkOutInput           = document.getElementById("checkOut");
        let locationInput           = document.getElementById('location');
        
        let bntBuscar               = document.getElementById("btn-buscar");
        let btnDetallesHotel        = document.getElementsByClassName("btnDetallesHotel");

        let flagPosition            = false;
        
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
            let checkIn = checkInInput.value;

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

            let checkOut = checkOutInput.value;

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
            title: 'Buscando Hoteles...',
            html: ` <div class ="d-flex flex-column align-items-center justify-content-center">
                        <p class="fs-6 mb-2 text-center">Destino: <span class="fw-bold">${locationInput.value}</span></p>
                        ${txtSweet}
                        <p class="fs-6 text-center">Fechas: <span class="fw-bold">${checkIn}</span> >>> <span class="fw-bold">${checkOut}</span></p>
                    </div>`,
                didOpen: () => {
                    Swal.showLoading()
                    
                },
                allowOutsideClick: () => !Swal.isLoading()
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
            
            // AJAX para guardar la seleccion en la sesion    
            xhrSesion.open("POST", `./../admin/privado/api/v1/sesion`, false);
            xhrSesion.setRequestHeader('Csrf', document.querySelector("meta[name=Csrf]").content);
            xhrSesion.send(JSON.stringify(jsonFormulario));
        }

        /* *************************************** */
        /* *************** EVENTOS *************** */
        /* *************************************** */
        bntBuscar.addEventListener("click", () => {enviarFormulario(event)});

        for (const botonDetalle of btnDetallesHotel) {
            botonDetalle.addEventListener("click", function() {
                let timerInterval
                Swal.fire({
                title: 'Obteniendo Información',
                html: `Esto puede tardar unos segundos...`,
                    didOpen: () => {
                        Swal.showLoading()
                    },
                })
            })
        }

        // Detectar scrollY de la pagina para cambiar estilos del filtro de busqueda y el buscador de hoteles
        window.addEventListener("scroll", function() {
            if (window.scrollY > 1520) {
                if (!flagPosition) {
                    flagPosition = !flagPosition
                    document.getElementById("contenedor-busqueda_filtro").style.position = 'sticky';
                    document.getElementById("contenedor-busqueda_filtro").style.top = '10rem';
                }
            } else {
                if (flagPosition) {
                    flagPosition = !flagPosition
                    document.getElementById("contenedor-busqueda_filtro").style.position = 'relative';
                    document.getElementById("contenedor-busqueda_filtro").style.top = '0';
                }
            }
        })

        $.fn.modal.Constructor.prototype.enforceFocus = function() {}; // give $().bootstrapBtn the Bootstrap functionality

        $('.selectTipPlanes').select2();
        $('.selectTipCamas').select2();

        $('.selectTipPlanes-movil').select2({
            placeholder: 'Preferencia de camas',
            maximumSelectionLength: 1
        });

        $('.selectTipCategorias-movil').select2({
            placeholder: 'Categoría de hotel',
            maximumSelectionLength: 1
        });

        $('.selectTipCamas-movil').select2({
            placeholder: 'Tipos de planes',
            maximumSelectionLength: 1
        });

        // Deshabilitar la salida del teclado en moviles

        $('.selectTipCategorias-movil').on('select2:opening', function( event ) {
            var $searchfield = $(this).parent().find('.select2-search__field');
            $searchfield.prop('disabled', true);
        });

        $('.selectTipPlanes-movil').on('select2:opening', function( event ) {
            var $searchfield = $(this).parent().find('.select2-search__field');
            $searchfield.prop('disabled', true);
        });

        $('.selectTipCamas-movil').on('select2:opening', function( event ) {
            var $searchfield = $(this).parent().find('.select2-search__field');
            $searchfield.prop('disabled', true);
        });
    
        $('.venoboxframe').venobox(); 
    });
})();
