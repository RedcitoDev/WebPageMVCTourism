(function () {
  document.addEventListener("DOMContentLoaded", function () {
    if (window.location.href.split("?")[0].split("/").pop() === 'datosdecompra' || window.location.href.split("?")[0].split("/").pop() === 'datosdecompra.php') {
      function getIp(callback) {
        fetch('https://ipinfo.io/json?token=4cb132c705dbd4', { headers: { 'Accept': 'application/json' } })
          .then((resp) => resp.json())
          .catch(() => {
            return {
              country: 'MX',
            };
          })
          .then((resp) => callback(resp.country));
      }
  
      const phoneInputField = document.querySelector("#telefono");
      const phoneInput = window.intlTelInput(phoneInputField, {
        preferredCountries: ["mx", "co", "us", "de"],
        initialCountry: "auto",
        geoIpLookup: getIp,
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
        separateDialCode: true,
        customPlaceholder: function () {
          return "Introduzca su número telefonico...";
        },
      });
  
      const info = document.querySelector(".alert-info");
  
      function process(event) {
        event.preventDefault();
        const phoneNumber = phoneInput.getNumber();
        info.style.display = "";
        info.innerHTML = `Phone number in E.164 format: <strong>${phoneNumber}</strong>`;
      }
  
      function getDatosReservacion(event) {
        event.preventDefault();
  
        // Validacion y limpieza de datos
        let nombreTitular     = document.getElementById('nombreTitular').value;
        let apellidoTitular   = document.getElementById('apellidoTitular').value;
        let correoTitular     = document.getElementById('correoTitular').value;
        let confirmCorreo     = document.getElementById('correoTitularConfirm').value;
  
        let regexTelefono     = /^\+?[-0-9]+$/;
        let telefonoTitular   = regexTelefono.test(phoneInput.getNumber()) ? phoneInput.getNumber() : '';
        let viajeTrabajo      = document.getElementById("formDatosReservacion").elements["viajeTrabajo"].value;
  
        let jsonDatosTitular  = {
          nombreTitular,
          apellidoTitular,
          correoTitular,
          confirmCorreo,
          telefonoTitular,
          viajeTrabajo
        };
  
        if (confirmCorreo == correoTitular) {
          if (telefonoTitular == '') {
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'El numero de telefono es incorrecto',
            });
          }
  
          if (nombreTitular.length > 0 && apellidoTitular.length > 0 && correoTitular.length > 0 && telefonoTitular.length > 0) {
            let ofertasCorreo         = document.getElementById("ofertasCorreo").checked;
            let cuponDescuento        = document.getElementById("cuponDescuento").value;
            let pedidoEspecial        = document.getElementById("pedidoEspecial").value;
            let titularesHabitacion   = document.getElementById("divHabitaciones").querySelectorAll(".habitaciones");
  
            jsonDatosTitular.ofertasCorreo    = ofertasCorreo;
            jsonDatosTitular.cuponDescuento   = cuponDescuento;
            jsonDatosTitular.pedidoEspecial   = pedidoEspecial;
  
            let jsonHuespedes = [];
  
            for (let i = 0, l = titularesHabitacion.length; i < l; i++) {
              let nombreHuespedTitular          = titularesHabitacion[i].querySelector(".nombreHuespedTitular").value;
              let correoHuespedTitular          = titularesHabitacion[i].querySelector(".correoHuespedTitular").value;
              let inputHiddenInfantes           = titularesHabitacion[i].querySelectorAll(".edadInfante");
  
              jsonHuespedes.push({
                nombreHuespedTitular,
                correoHuespedTitular,
                edadInfantes: []
              });
              
              for (let j = 0, m = inputHiddenInfantes.length; j < m; j++) {
                let  edadInfante = inputHiddenInfantes[j].value;
  
                jsonHuespedes[i].edadInfantes.push(edadInfante);
              }
            }
            
            $.ajax({
              type: "POST",
              url: "./../ajax-data/datos-reserva",
              headers: {
                  'Csrf': $('meta[name="Csrf"]').attr('content')
              },
              dataType: "json",
              data: JSON.stringify({
                datosTitular: jsonDatosTitular,
                datosHuespedes: jsonHuespedes
              }),
              success: function (response) {     
                var nombreReserva     = response.holder.name;
                var apellidoReserva   = response.holder.surname;
                var email             = correoTitular;
                var telefono          = telefonoTitular;
                var titularHabitacion = document.getElementById("divHabitaciones").querySelectorAll(".habitaciones");
                var reserva = 'Reserva: ' + nombreReserva + " " + apellidoReserva + '<br>' + 'Correo: ' + email + '<br>' + 'Telefono: ' + telefono + '<br>';
                var titulares = "";
  
                for (var i = 0; i < titularHabitacion.length; i++) {
                  var c = i + 1;
                  var titular = titularHabitacion[i].querySelector(".nombreHuespedTitular").value
                  var titulares = titulares + 'Titular Habitacion ' + c + ': ' + titular + '<br>';
                }
  
                let text = reserva + titulares;
  
                Swal.fire({
                  title: '¿Tu informacio es la correcta?',
                  icon: 'question',
                  html: text,
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  cancelButtonText: 'No, haré correciones',
                  confirmButtonText: 'Si, es correcto'
                }).then((result) => {
                  if (result.isConfirmed) {
                    Swal.fire(
                      'Excelente!',
                      'Ya estamos por terminar.',
                      'success'
                    );
                    setTimeout(
                      () => { window.location.href = "./../datosdepago"; },
                      1250)
                    
                  }
                });
  
              }
            });
          }
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Los correos no coinciden :c',
          });
        }
      }

      document.getElementById("formDatosReservacion").addEventListener("submit", () => { getDatosReservacion(event) });
    } else {
      function DatosTarjeta() {
        $.ajax({
          type: "post",
          url: "./../ajax-data/registro-paymetData",
          headers: {
              'Csrf': $('meta[name="Csrf"]').attr('content')
          },
          dataType: "json",
          // data: $("#formCard").serialize(),
          // TODO: Trabajar con codigos de respuesta para redireccionar a compra exitosa o a error
          success: function (response) {
            // console.log(response);
  
            if (Number.isInteger(response)) {
              // console.log("Exitoso");
              window.location.href = "./../compra-exitosa";
            } else {
              return;
              window.location.href = "./../pago-fallido";
            }
          }
        });
      }
  
      // document.getElementById("btnPagar").addEventListener("click", DatosTarjeta);
    }
  });
})();


