let paso = 1;
const pasoInicial = 1;
const pasoFinal = 3;

const cita = {
  id: "",
  nombre: "",
  fecha: "",
  hora: "",
  servicios: [],
};

document.addEventListener("DOMContentLoaded", function () {
  iniciarApp();
});

function iniciarApp() {
  mostrarSeccion(); // Muestra y oculta las secciones
  tabs(); // Cambia la sección cuando se presionan los tabs
  botonesPaginador(); // Agrega o quita los botones del paginador
  paginaAnterior();
  paginaSiguiente();

  consultarAPI(); // Consulta la API en el backend de PHP

  idCliente(); // Añade el nombre del cliente al objeto de cita
  nombreCliente(); // Añade el nombre del cliente al objeto de cita
  seleccionarFecha(); // Añade la fecha de la cita en el objeto
  seleccionarHora(); // Añade la hora de la cita en el objeto

  mostrarResumen(); // Muestra el resumen de la cita
}
function mostrarSeccion() {
  // Ocultar la sección que tenga la clase de mostrar
  const seccionAnterior = document.querySelector(".mostrar");
  if (seccionAnterior) {
    seccionAnterior.classList.remove("mostrar");
  }

  // Seleccionar la sección con el paso...
  const pasoSelector = `#paso-${paso}`;
  const seccion = document.querySelector(pasoSelector);
  seccion.classList.add("mostrar");

  // Quita la clase de actual al tab anterior
  const tabAnterior = document.querySelector(".actual");
  if (tabAnterior) {
    tabAnterior.classList.remove("actual");
  }

  // Resalta el tab actual
  const tab = document.querySelector(`[data-paso="${paso}"]`);
  tab.classList.add("actual");
}
function tabs() {
  const botones = document.querySelectorAll(".tabs button");

  botones.forEach((boton) => {
    boton.addEventListener("click", function (e) {
      paso = parseInt(e.target.dataset.paso);

      mostrarSeccion();
      botonesPaginador();

      //   if (paso === 3) {
      //     mostrarResumen();
      //   }
    });
  });
}

function botonesPaginador() {
  const paginaAnterior = document.querySelector("#anterior");
  const paginaSiguiente = document.querySelector("#siguiente");

  if (paso === 1) {
    paginaAnterior.classList.add("ocultar");
    paginaSiguiente.classList.remove("ocultar");
  } else if (paso === 3) {
    paginaAnterior.classList.remove("ocultar");
    paginaSiguiente.classList.add("ocultar");

    mostrarResumen();
  } else {
    paginaAnterior.classList.remove("ocultar");
    paginaSiguiente.classList.remove("ocultar");
  }

  mostrarSeccion();
}

function paginaAnterior() {
  const paginaAnterior = document.querySelector("#anterior");
  paginaAnterior.addEventListener("click", function () {
    if (paso <= pasoInicial) return;
    paso--;

    botonesPaginador();
  });
}

function paginaSiguiente() {
  const paginaSiguiente = document.querySelector("#siguiente");
  paginaSiguiente.addEventListener("click", function () {
    if (paso >= pasoFinal) return;
    paso++;

    botonesPaginador();
  });
}

async function consultarAPI() {
  try {
    const url = "http://localhost:3000/api/servicios";
    const resultado = await fetch(url);
    const servicios = await resultado.json();
    mostrarServicios(servicios);
  } catch (error) {
    console.log(error);
  }
}

function mostrarServicios(servicios) {
  servicios.forEach((servicio) => {
    const { id, nombre, precio } = servicio;

    const nombreServicio = document.createElement("P");
    nombreServicio.classList.add("nombre-servicio");
    nombreServicio.textContent = nombre; // CORRECCIÓN: quitar el "$"

    const precioServicio = document.createElement("P");
    precioServicio.classList.add("precio-servicio");
    precioServicio.textContent = `$${precio}`;

    const servicioDiv = document.createElement("DIV");
    servicioDiv.classList.add("servicio");
    servicioDiv.dataset.idServicio = id;

    // ASIGNAR LA FUNCIÓN DE SELECCIÓN
    servicioDiv.onclick = function () {
      seleccionarServicio(servicio);
    };

    servicioDiv.appendChild(nombreServicio);
    servicioDiv.appendChild(precioServicio);

    document.querySelector("#servicios").appendChild(servicioDiv);
  });
}

function seleccionarServicio(servicio) {
  const { id } = servicio;
  const { servicios } = cita;

  // Identificar el elemento al que se le da click
  const divServicio = document.querySelector(`[data-id-servicio="${id}"]`);

  // Comprobar si un servicio ya fue agregado
  if (servicios.some((agregado) => agregado.id === id)) {
    // Eliminarlo
    cita.servicios = servicios.filter((agregado) => agregado.id !== id);
    divServicio.classList.remove("seleccionado");
  } else {
    // Agregarlo
    cita.servicios = [...servicios, servicio];
    divServicio.classList.add("seleccionado");
  }

  // Actualizar resumen si estamos en el paso 3
  if (paso === 3) {
    mostrarResumen();
  }
}

function idCliente() {
  cita.id = document.querySelector("#id").value;
}

function nombreCliente() {
  cita.nombre = document.querySelector("#nombre").value;

  // Si está vacío, usar el valor del atributo value
  if (!cita.nombre) {
    cita.nombre = document.querySelector("#nombre").getAttribute("value");
  }
}

function seleccionarFecha() {
  const inputFecha = document.querySelector("#fecha");
  inputFecha.addEventListener("input", function (e) {
    const dia = new Date(e.target.value).getUTCDay();
    // cita.fecha = inputFecha.value;

    if ([6, 0].includes(dia)) {
      e.target.value = "";
      mostrarAlerta("Fines de semana no permitidos", "error", ".formulario");
    } else {
      cita.fecha = e.target.value;
    }
  });
}

function mostrarAlerta(mensaje, tipo, elemento, desaparece = true) {
  // Previene que se generen más de 1 alerta
  const alertaPrevia = document.querySelector(".alerta");
  if (alertaPrevia) {
    alertaPrevia.remove();
  }

  // Scripting para crear la alerta
  const alerta = document.createElement("DIV");
  alerta.textContent = mensaje;
  alerta.classList.add("alerta");
  alerta.classList.add(tipo);

  const referencia = document.querySelector(elemento);
  referencia.appendChild(alerta);

  if (desaparece) {
    // Eliminar la alerta
    setTimeout(() => {
      alerta.remove();
    }, 3000);
  }
}

function seleccionarHora() {
  const inputHora = document.querySelector("#hora");
  inputHora.addEventListener("input", function (e) {
    const horaCita = e.target.value;
    const hora = horaCita.split(":")[0];
    if (hora < 10 || hora > 18) {
      e.target.value = "";
      mostrarAlerta("Hora no válida", "error", ".formulario");
    } else {
      cita.hora = e.target.value;
    }
  });
}

// function mostrarResumen() {
//   const resumen = document.querySelector(".contenido-resumen");

//   // Limpiar el contenido de Resumen
//   while (resumen.firstChild) {
//     resumen.removeChild(resumen.firstChild);
//   }

//   if (Object.values(cita).includes("") || cita.servicios.length === 0) {
//     mostrarAlerta(
//       "Faltan datos de Servicios, Fecha u Hora",
//       "error",
//       ".contenido-resumen",
//       false
//     );

//     return;
//   }

//   // Heading para Servicios en Resumen
//   const headingServicios = document.createElement("H3");
//   headingServicios.textContent = "Resumen de Servicios";
//   resumen.appendChild(headingServicios);

//   // Iterando y mostrando los servicios
//   servicios.forEach((servicio) => {
//     const { id, precio, nombre } = servicio;
//     const contenedorServicio = document.createElement("DIV");
//     contenedorServicio.classList.add("contenedor-servicio");

//     const textoServicio = document.createElement("P");
//     textoServicio.textContent = nombre;

//     const precioServicio = document.createElement("P");
//     precioServicio.innerHTML = `<span>Precio:</span> $${precio}`;
//     contenedorServicio.appendChild(textoServicio);
//     contenedorServicio.appendChild(precioServicio);

//     resumen.appendChild(contenedorServicio);
//   });

//   // Heading para Cita en Resumen
//   const headingCita = document.createElement("H3");
//   headingCita.textContent = "Resumen de Cita";
//   resumen.appendChild(headingCita);

//   // Formatear el div de resumen
//   const { nombre, fecha, hora, servicios } = cita;
//   const nombreCliente = document.createElement("P");
//   nombreCliente.innerHTML = `<span>Nombre:</span> ${nombre}`;

//   // Formatear la fecha en español
//   const fechaObj = new Date(fecha);
//   const mes = fechaObj.getMonth();
//   const dia = fechaObj.getDate() + 2;
//   const year = fechaObj.getFullYear();

//   const fechaUTC = new Date(Date.UTC(year, mes, dia));

//   const opciones = {weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'};
//   const fechaFormateada = fechaUTC.toLocaleDateString('es-ARG', opciones);

//   const fechaCita = document.createElement("P");
//   fechaCita.innerHTML = `<span>Fecha:</span> ${fechaFormateada}`;

//   const horaCita = document.createElement("P");
//   horaCita.innerHTML = `<span>Hora:</span> ${hora}`;

//   // Botón para Crear una cita
//   const botonReservar = document.createElement('BUTTON');
//   botonReservar.classList.add('boton');
//   botonReservar.textContent = 'Reservar Cita';
//   botonReservar.onclick = reservarCita;

//   resumen.appendChild(nombreCliente);
//   resumen.appendChild(fechaCita);
//   resumen.appendChild(horaCita);

//   resumen.appendChild(botonReservar);
// }

function mostrarResumen() {
  // CORRECCIÓN: usar el ID correcto (#resumen)
  const resumen = document.querySelector("#resumen");

  // Limpiar el contenido
  resumen.innerHTML = "";

  if (Object.values(cita).includes("") || cita.servicios.length === 0) {
    mostrarAlerta(
      "Faltan datos de Servicios, Fecha u Hora",
      "error",
      "#resumen",
      false
    );
    return;
  }

  // Heading para Servicios
  const headingServicios = document.createElement("H3");
  headingServicios.textContent = "Resumen de Servicios";
  resumen.appendChild(headingServicios);

  // CORRECCIÓN: usar cita.servicios
  cita.servicios.forEach((servicio) => {
    const { nombre, precio } = servicio;

    const contenedorServicio = document.createElement("DIV");
    contenedorServicio.classList.add("contenedor-servicio");

    const textoServicio = document.createElement("P");
    textoServicio.textContent = nombre;

    const precioServicio = document.createElement("P");
    precioServicio.innerHTML = `<span>Precio:</span> $${precio}`;

    contenedorServicio.appendChild(textoServicio);
    contenedorServicio.appendChild(precioServicio);
    resumen.appendChild(contenedorServicio);
  });

  // Heading para Cita
  const headingCita = document.createElement("H3");
  headingCita.textContent = "Resumen de Cita";
  resumen.appendChild(headingCita);

  // Datos de la cita
  const { nombre, fecha, hora } = cita;

  const nombreCliente = document.createElement("P");
  nombreCliente.innerHTML = `<span>Nombre:</span> ${nombre}`;

  // CORRECCIÓN: Formateo correcto de fecha
  const fechaObj = new Date(fecha);
  const opciones = {
    weekday: "long",
    year: "numeric",
    month: "long",
    day: "numeric",
  };
  const fechaFormateada = fechaObj.toLocaleDateString("es-MX", opciones);

  const fechaCita = document.createElement("P");
  fechaCita.innerHTML = `<span>Fecha:</span> ${fechaFormateada}`;

  const horaCita = document.createElement("P");
  horaCita.innerHTML = `<span>Hora:</span> ${hora} horas`;

  // Botón para Crear una cita
  const botonReservar = document.createElement("BUTTON");
  botonReservar.classList.add("boton");
  botonReservar.textContent = "Reservar Cita";
  botonReservar.onclick = reservarCita;

  resumen.appendChild(nombreCliente);
  resumen.appendChild(fechaCita);
  resumen.appendChild(horaCita);
  resumen.appendChild(botonReservar);
}

async function reservarCita() {
  const { nombre, fecha, hora, servicios, id } = cita;
  const idServicios = servicios.map((servicio) => servicio.id);

  const datos = new FormData();
  datos.append("fecha", fecha);
  datos.append("hora", hora);
  datos.append("usuarioId", id);
  datos.append("servicios", idServicios);

  // console.log(datos);

  try {
    // Petición hacia la api
    const url = "http://localhost:3000/api/citas";

    const respuesta = await fetch(url, {
      method: "POST",
      body: datos,
    });

    const resultado = await respuesta.json();

    if (resultado.resultado) {
      Swal.fire({
        icon: "success",
        title: "Cita Creada",
        text: "Tu cita fue creada correctamente!",
        button: "OK",
      }).then(() => {
        setTimeout(() => {
          window.location.reload();
        }, 3000);
      });
    }
  } catch (error) {
    Swal.fire({
      icon: "error",
      title: "Error",
      text: "Hubo un error al guardar la cita",
    });
  }
}
