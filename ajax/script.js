var buscar = document.getElementById("buscar");
buscar.addEventListener("keyup", ()=>{
    var valor = buscar.value;
    if(valor == ""){
        listaamigo('');
    }else{
        listaamigo(valor);
    }
})

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
                str += "<td><button type='button' class='btn btn-danger' onclick=" + "Eliminar(" + item.id + ")>Eliminar</button></td>";
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

/* BOTON SOLICITUDES DE AMISTAD */
function mostrarSolicitud(params) {
    
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
            console.log(json);
            var mensajesHTML = '';
            json.forEach(function (item) {
                document.getElementById("personachat").innerText = "Chat con " + item.username;
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
                var mensaje = document.getElementById("mensaje").value;
                var userIdValue = userId;
                console.log(userIdValue);
                console.log(mensaje);
            });
        }
    }   
    ajax.send(formdata);
}

/* ENVIAR CHAT */
