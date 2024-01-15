var buscar = document.getElementById("buscar");
buscar.addEventListener("keyup", ()=>{
    var valor = buscar.value;
    if(valor == ""){
        listaamigo('');
    }else{
        listaamigo(valor);
    }
})

// Agrega un evento al campo de búsqueda para interceptar cambios
document.getElementById("soli_buscar").addEventListener("click", function (event) {
    event.preventDefault();
    var searchTerm = document.getElementById("search_user").value;
    buscarUsuariosAJAX(searchTerm);
});

// Función para realizar la búsqueda AJAX
function buscarUsuariosAJAX(searchTerm) {

    var formdata = new FormData();
    formdata.append('buscar', searchTerm);

    var ajax = new XMLHttpRequest();
    ajax.open('POST', '../php/mostrar/enviarsoli.php');
    
    ajax.onload = function () {
        if (ajax.status == 200) {
            var json = JSON.parse(ajax.responseText);
            console.log(json);
            var formulario = "";
            json.forEach(function (item) {
                formulario += "<br> - Username: " + item.username + " - Nombre Real: " + item.nom_real + "<br>";
                formulario += "<input type='hidden' name='id_user_destino' value='" + item.id_user + "'id=" + item.id_user + ">";
                formulario += "<input type='submit' class='enviarSoli' value='Enviar Solicitud de Amistad'>";
            });
            document.getElementById("usuariosbuscados").innerHTML = formulario;

            var btnEnviarSoli = document.querySelectorAll('.enviarSoli');
            btnEnviarSoli.forEach(function (input) {
                input.addEventListener('click', function (event) {
                    event.preventDefault();
                    var solicitudId = this.previousElementSibling.value;
                    enviarSolicitud(solicitudId);
                    buscarUsuariosAJAX(searchTerm);
                });
            });
        }
    }
    ajax.send(formdata);
}

listaamigo('')

// Listar productos
function listaamigo(valor) {
    var resultado = document.getElementById('resultado');
    var formdata = new FormData();
    formdata.append('busqueda', valor);

    var ajax = new XMLHttpRequest();
    ajax.open('POST', '../php/mostrar/listaamigo.php');

    ajax.onload = function () {
        var str = "";
        if (ajax.status == 200) {
            var json = JSON.parse(ajax.responseText);
            var tabla = "";
            json.forEach(function (item) {
                str = "<tr><td><button class='chat-button' data-id='" + item.id_user + "'>" + item.username + "</button></td>";
                str += "</tr>";
                tabla += str;
            });

            resultado.innerHTML = tabla;

            // Agregar eventos de clic a los botones de chat
            var chatButtons = document.querySelectorAll('.chat-button');
            chatButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    var userId = this.getAttribute('data-id');
                    chat(userId);
                });
            });
        }
    }
    ajax.send(formdata);
}


/* BOTON ENVIAR SOLICITUD */
function enviarSolicitud(solicitudId) {
    
    var formdata = new FormData();
    formdata.append('id_user_destino', solicitudId);

    var ajax = new XMLHttpRequest();
    ajax.open('POST', '../php/mostrar/proc_enviarSoli.php');

    ajax.onload = function() {
        if (ajax.status == 200) {
            Swal.fire({
                title: "Enviado",
                text: "Solicitud enviada",
                icon: "success"
            });
        }
    }
    ajax.send(formdata);
}

    
/* BOTON SOLICITUDES DE AMISTAD */

var listasolicitud = document.getElementById("listasolicitud")
listasolicitud.addEventListener("click", function (event) {
    event.preventDefault();
    mostrarSolicitud();
});

/* LISTA DE MOSTRAR SOLICITUDES */

function mostrarSolicitud() {
    var ajax = new XMLHttpRequest();
    ajax.open('POST', '../php/mostrar/listasoli.php');

    ajax.onload = function() {
        if (ajax.status == 200) {
            var json = JSON.parse(ajax.responseText);
            document.getElementById('listasolicitudes').innerHTML = json;
            // Agrega eventos de clic a los botones de aceptar
            var btnAceptar = document.querySelectorAll('button[name="aceptar"]');
            btnAceptar.forEach(function(btn) {
                btn.addEventListener('click', function(event) {
                    event.preventDefault();
                    var solicitudId = this.getAttribute('id');
                    procesarSolicitud(solicitudId, 'aceptar');
                });
            });

            // Agrega eventos de clic a los botones de rechazar
            var btnRechazar = document.querySelectorAll('button[name="rechazar"]');
            btnRechazar.forEach(function(btn) {
                btn.addEventListener('click', function(event) {
                    event.preventDefault();
                    var solicitudId = this.getAttribute('id');
                    procesarSolicitud(solicitudId, 'rechazar');
                });
            });
        }
    };
    ajax.send();
}

/* PROCESSAR SOLICITUDES */
function procesarSolicitud(solicitudId, accion) {
    var formdata = new FormData();
    formdata.append('id', solicitudId);
    formdata.append('accion', accion);

    var ajax = new XMLHttpRequest();
    ajax.open('POST', '../php/mostrar/proc_soli.php');

    ajax.onload = function() {
        if (ajax.status == 200) {
            if (accion === "aceptar") {
                Swal.fire({
                    title: "Aceptar",
                    text: "Ya sois amigos",
                    icon: "success"
                });
            } else {
                Swal.fire({
                    title: "Rechazar",
                    text: "No sois amigos",
                    icon: "error"
                });
            }
            mostrarSolicitud();
            listaamigo("");
        } else {
            console.error('Error en la solicitud AJAX para ' + accion);
        }
    };
    
    ajax.send(formdata);
}

/* MOSTRAR CHAT */

function chat(userId) {

    var formdata = new FormData();
    formdata.append('id_user', userId);
    
    var ajax = new XMLHttpRequest();
    ajax.open('POST', '../php/mostrar/chat.php');
        
    ajax.onload = function() {
        if (ajax.status == 200) {
            var json = JSON.parse(ajax.responseText);
            var mensajesHTML = '';
            json.forEach(function (item) {
                if (userId !== item.id_receptor) {
                    document.getElementById("personachat").innerText = "Chat con " + item.username;
                }
                var timestamp = item.timestamp || '';
                var textoMensaje = item.texto_mensaje || '';
                mensajesHTML += timestamp + "<br>" + textoMensaje + "<br>";
            });
            document.getElementById("mensajesuser").innerHTML = mensajesHTML;
            var formulario = document.getElementById("formulario-mensaje")
            formulario.innerHTML = '';
            formulario.innerHTML 
            = "<input type='hidden' name='id_user' id=" + userId + 
            "><input type='text' id='mensaje' name='mensaje' placeholder='Escribe tu mensaje' required> <input type='submit' id='enviarmensaje'>"
            document.getElementById("enviarmensaje").addEventListener("click", (event) => {
                event.preventDefault();
                var mensaje = document.getElementById("mensaje");
                enviarmensaje(userId, mensaje.value);
                mensaje.value = '';
            });
        }
    }
    ajax.send(formdata);

}

/* ENVIAR CHAT */

function enviarmensaje(userId, mensaje) {
    var formdata = new FormData();
    formdata.append('id_user', userId);
    formdata.append('mensaje', mensaje);

    var ajax = new XMLHttpRequest();
    ajax.open('POST', '../php/mostrar/proc_chat.php');

    ajax.onload = function() {
        if (ajax.status == 200) {
            chat(userId)
        }
    }   
    ajax.send(formdata);
}