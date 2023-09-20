let paso = 1;
const pasoInicial = 1;
const pasoFinal = 3;

const cita = {
    id: '',
    nombre: '', 
    fecha: '', 
    hora:'', 
    servicios: []

}

document.addEventListener('DOMContentLoaded', function(){
    iniciarApp();
});

function iniciarApp(){
    mostrarSeccion(); //muestra y oculta las secicones.
    tabs(); //cambia la sección cuando se presionen los botones.
    botonesPaginador(); //le da sentido a los botones del paginador.
    paginaSiguiente();
    paginaAnterior();
    consultarAPI(); //consulta API en el backend de PHP. 
    idCliente();
    nombreCliente(); //añade el nombre del cliente al objeto de cita.
    seleccionarFecha(); //añade la fecha de la cita en el objeto.
    seleccionarHora(); //añade la hora de la cita al objeto. 
    mostrarResumen(); //muestra el resumen de la cita al final
}

function mostrarSeccion(){

    //ocultar la sección que tenga la clase de mostrar:
    const seccionAnterior = document.querySelector('.mostrar');
    if(seccionAnterior){
    seccionAnterior.classList.remove('mostrar');
    }
    //seleccionar la sección con el paso
    const pasoSelector = `#paso-${paso}`;
    const seccion = document.querySelector (pasoSelector);
    seccion.classList.add('mostrar');

    //quitar la clase de actual al anterior:
    const tabAnterior = document.querySelector('.actual');
    if(tabAnterior){
        tabAnterior.classList.remove('actual');
    }


   //resalta el botón en que se esté:
    const tab = document.querySelector(`[data-paso="${paso}"]`);
    tab.classList.add('actual');
}

function tabs(){
    const botones = document.querySelectorAll('.tabs button');
    botones.forEach(boton =>{
        boton.addEventListener('click', function(e){
            paso = parseInt (e.target.dataset.paso);

            mostrarSeccion();
            botonesPaginador();
        });
    });
}

function botonesPaginador(){
    const paginaAnterior = document.querySelector('#anterior');
    const paginaSiguiente = document.querySelector('#siguiente');

    if(paso === 1){
        paginaAnterior.classList.add('ocultar');
        paginaSiguiente.classList.remove('ocultar');
    } else if (paso===3){
        paginaAnterior.classList.remove('ocultar');
        paginaSiguiente.classList.add('ocultar');
        mostrarResumen();
    } else {
        paginaAnterior.classList.remove('ocultar');
        paginaSiguiente.classList.remove('ocultar');
    }
    mostrarSeccion();
}
function paginaAnterior(){
    const paginaAnterior = document.querySelector('#anterior');
    paginaAnterior.addEventListener('click', function(){
        if(paso <= pasoInicial) return;
        paso--;
        botonesPaginador();
    })
}

function paginaSiguiente(){
    const paginaSiguiente = document.querySelector('#siguiente');
    paginaSiguiente.addEventListener('click', function(){
        if(paso >= pasoFinal) return;
        paso++;
        botonesPaginador();
    })
}

async function consultarAPI() {
    try {
        const url = `${location.origin}/api/servicios`;
        const resultado = await fetch(url);
        const servicios = await resultado.json();
        mostrarServicios(servicios);
        
    } catch (error) {
        console.log(error);
    }
}

function mostrarServicios(servicios){
    servicios.forEach(servicio => {
        const {id, nombre, precio} = servicio;
        
        const nombreServicio = document.createElement('P');
        nombreServicio.classList.add('nombre-servicio');
        nombreServicio.textContent = nombre;

        const precioServicio = document.createElement('P');
        precioServicio.classList.add('precio-servicio');
        precioServicio.textContent = `$${precio}`;

        const servicioDiv = document.createElement('DIV');
        servicioDiv.classList.add('servicio');
        servicioDiv.dataset.idServicio = id;
        servicioDiv.onclick = function(){
            seleccionarServicio(servicio);
        }

        servicioDiv.appendChild(nombreServicio);
        servicioDiv.appendChild(precioServicio);

        document.querySelector('#servicios').appendChild(servicioDiv);


         
     });
}

function seleccionarServicio(servicio){
    const {id} = servicio;
    const {servicios} = cita; //extrae el arreglo de servicios

    //identificando el elemento clickeado
    const divServicio = document.querySelector(`[data-id-servicio = "${id}"]`);

    //despintar los servicios seleccionados:
    if(servicios.some(agregado => agregado.id === id) ){
        //si ya está agregado, eliminarlo:
        cita.servicios = servicios.filter(agregado => agregado.id !== id);
        divServicio.classList.remove('seleccionado');  


    } else {
        //agregarlo: 
        cita.servicios = [...servicios, servicio]; //hace una copia y lo agrega al nuevo servicio
        divServicio.classList.add('seleccionado');  

    }
}

function idCliente(){
    cita.id = document.querySelector('#id').value;

}

function nombreCliente(){
    const nombre = document.querySelector('#nombre').value;
    cita.nombre= nombre;

}

function seleccionarFecha(){
    const inputFecha = document.querySelector('#fecha');
    inputFecha.addEventListener('input', function(e) {
        const dia = new Date (e.target.value).getUTCDay();
        if  ([6,0].includes(dia)){
            e.target.value = '';
            mostrarAlerta('No ofrecemos servicios los fines de semana.' , 'error', '.formulario');
        } else {
            cita.fecha = e.target.value;
        }
    })
}


function seleccionarHora(){
    const inputHora = document.querySelector('#hora');
    inputHora.addEventListener('input' , function(e){
        const horaCita = e.target.value;
        const hora = horaCita.split(":")[0];
        if(hora < 10 || hora > 18) {
            e.target.value = '';
            mostrarAlerta('Elegí una hora entre las 10:00 y 18:00.', 'error', '.formulario');
        } else {
            cita.hora = e.target.value;
        }

    });
}


function mostrarAlerta(mensaje, tipo, elemento, desaparece=true){
    //previene que se generen muchas alertas:
    const alertaPrevia = document.querySelector('.alerta');
    if(alertaPrevia) {
        alertaPrevia.remove();
    };

    //configurando la alerta
    const alerta = document.createElement('DIV');
    alerta.textContent = mensaje;
    alerta.classList.add('alerta');
    alerta.classList.add(tipo);
    
    const referencia = document.querySelector(elemento);
    referencia.appendChild(alerta);

    if(desaparece){
        //borrando la alerta después de mostrarla 3 segundos:
        setTimeout(() => {
            alerta.remove();
        }, 3000);
        }
    }

   

function mostrarResumen(){
    const resumen= document.querySelector('.contenido-resumen');

    //limpiar contenido de resumen:
    while(resumen.firstChild) {
        resumen.removeChild(resumen.firstChild);
    }

    if(Object.values(cita).includes('') || cita.servicios.length === 0){
        mostrarAlerta('Es necesario completar el formulario' , 'error', '.contenido-resumen', false);
        
        return;
     } 

     //mostrar el resumen:
     const {nombre, fecha, hora, servicios} = cita;

     //heading para servicios en el resumen:
     const headingServicios = document.createElement('H3');
     headingServicios.textContent = 'Resumen de servicio';
     resumen.appendChild(headingServicios);
     //iterando los servicios: 
     servicios.forEach(servicio => {
        const {id, precio, nombre} = servicio;
        const contenedorServicio=document.createElement('DIV');
        contenedorServicio.classList.add('contenedor-servicio');

        const textoServicio = document.createElement('P');
        textoServicio.textContent = nombre;

        const precioServcio = document.createElement('P');
        precioServcio.innerHTML = `<span>Precio:</span> $${precio}`;

        contenedorServicio.appendChild(textoServicio);
        contenedorServicio.appendChild(precioServcio);

        resumen.appendChild(contenedorServicio);
     });
        //heading para citas en el resumen:
        const headingCita = document.createElement('H3');
        headingCita.textContent = 'Resumen de la cita';
        resumen.appendChild(headingCita);

        
        const nombreCliente = document.createElement('P');
        nombreCliente.innerHTML = `<span>Nombre:</span> ${nombre}`;

        //Formatear la fecha en español:
        const fechaObj = new Date(fecha);
        const mes = fechaObj.getMonth();
        const dia = fechaObj.getDate() +2;
        const year = fechaObj.getFullYear();

        const fechaUTC = new Date(Date.UTC(year, mes, dia));

        const opciones = {weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'}
        const  fechaFormateada = fechaUTC.toLocaleDateString('es-AR', opciones);


        const fechaCita = document.createElement('P');
        fechaCita.innerHTML = `<span>Fecha:</span> ${fechaFormateada}`;

        const horaCita = document.createElement('P');
        horaCita.innerHTML = `<span>Hora:</span> ${hora}`;

        //boton para crear una cita:
        const botonReservar = document.createElement('BUTTON');
        botonReservar.classList.add('boton');
        botonReservar.textContent = 'Reservar cita';
        botonReservar.onclick = reservarCita;

        resumen.appendChild(nombreCliente);
        resumen.appendChild(fechaCita);
        resumen.appendChild(horaCita);

        resumen.appendChild(botonReservar);
    }

    async function reservarCita(){
        const {nombre, fecha, hora, servicios, id} = cita;

        const idServicios = servicios.map(servicio => servicio.id);


        const datos = new FormData();
        datos.append('fecha', fecha);
        datos.append('hora', hora);
        datos.append('usuarioId', id);
        datos.append('servicios', idServicios);

        try {
            //Petición hacia la API
            const url = `${location.origin}/api/citas`;

            const respuesta = await fetch(url, {
                method: 'POST', 
                body: datos
            });

            const resultado = await respuesta.json();
            console.log(resultado.resultado);
            if(resultado.resultado){
                Swal.fire({
                    icon: 'success',
                    title: 'Cita creada con éxito',
                    text: 'Tu cita ha sido creada correctamente!',
                    button: 'OK'
                }).then( () => {
                    
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                })
            }
            
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Hubo un error con el guardado de tu cita',
              })
        }

        

        //console.log([...datos]); esto nos permite mostrar los datos del formdata en la consola para verificar que están bien.

    }