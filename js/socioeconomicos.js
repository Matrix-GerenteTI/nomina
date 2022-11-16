// let trabajadores;
// let listadoPreguntas;
// const trabajadorEvaluado = function ( trabajador) {
//         $.get("/nomina/ajax/controladores/socioeconomico.php", {
//                 opc: 'verificaEvaluacionRealizada', 
//                 trabajador: trabajador },
//             function (data, textStatus, jqXHR) {
//                 $("#busquedaEmpleados").modal("hide");
//                 getDatosTrabajador( trabajador );
//                 if ( data  == -1) {
//                     cargaEvaluacion( '');
//                 }else if( data > 0){
//                     cargaEvaluacion( data );
//                 }
//             },
//             "text"
//         );
//   };

//   const getDatosTrabajador = function( trabajador ){
//       let trabajadorSeleccionado = trabajadores.find(function (itemTrabajador) { 
//            return itemTrabajador.nip == trabajador;
//         });
//         $("#nip").val( trabajador);
//         getDatosPersonales( trabajador );
//   }

//   const getDatosPersonales = function (trabajador) {
//       $.get("/nomina/ajax/controladores/empleados.php", {
//           opc: 'getPersonalData',
//           trabajador: trabajador
//       },
//           function (data, textStatus, jqXHR) {
//               $.each(data, function (i, item) { 
//                   $("#nombreEmpleado").val(item.nombre);
//                   $("#calle_av").val(item.calle);
//                   $("#numInt").val(item.numint);
//                   $("#numExt").val(item.numext);
//                   $("#cp").val(item.cp );
//                   $("#colonia").val(item.colonia);
//                   $("#estado").val();
//                   $("#municipio").val(item.municipio);
//                   $("#nacimiento").val(item.fechanac);
//                   $("#lugarNacimiento").val()
//                   $("#edoCivil").val(item.edocivil);
//               });
//           },
//           "json"
//       );
//     }

//   const  cargaEvaluacion = function ( evaluacion ) {
//       $("#socioeconomico").val(evaluacion);
//       $.get("/nomina/ajax/controladores/socioeconomico.php", {
//           opc: 'getEvaluacion',
//           evaluacion: evaluacion
//       },
//           function (data, textStatus, jqXHR) {

//               if ( data.evaluacion.length > 0) {
//                   $(".oculto").fadeIn();
//                   listadoPreguntas = data.evaluacion;
//                   let seccion = data.evaluacion[0].descripcion
//                   let templatePreguntas = '';
//                   let cantPreguntas = data.evaluacion.length;
//                   $("#tingreso").html("");
//                   $("#tgasto").html("");
//                   $("#tcreditos").html("");

//                   for (let i = 0; i <= cantPreguntas; i++) {
//                       let  pregunta = data.evaluacion[i];
                      
                      
//                         pregunta = pregunta == undefined ? { seccion: '' } : pregunta;
                      
//                         if ( pregunta.seccion != seccion  || pregunta.seccion == ''  ) {
//                             //Agregando el contendio del template al div de la seccion
//                             if ( seccion != "economico") {
//                                 $(`#t${seccion}`).html(templatePreguntas);
//                             }
                            
//                             if ( pregunta.seccion == '' ) { //Ya se terminaron las preguntas por seccion?
//                                 break; //finaliza el ciclo  y termina la renderización
//                             }
//                             templatePreguntas = `
//                                <tr>
//                                         <td><label><h4>${pregunta.descripcion}</h4></label></td>
//                                 </tr>
//                                 <tr>`
                                
                                
//                                     if ( pregunta.opciones.length > 0) {
//                                         templatePreguntas += `<td><div class="checkbox">`    ;
//                                         $.each(pregunta.opciones, function (j, itemOpcion) { 
//                                             if (itemOpcion.tipo == 'checkbox') {
//                                                 templatePreguntas += `<label><input type="${itemOpcion.tipo}" value="${itemOpcion.descripcion}" id="opc-${itemOpcion.id}" ${itemOpcion.checked} class="preg-${pregunta.id}"> ${itemOpcion.descripcion} </label>`;
                                                                                
//                                             } else {
//                                                 templatePreguntas += `
//                                                                                 <label>${itemOpcion.descripcion} </label>
//                                                                                 <input type="text" id="opc-${itemOpcion.id}" class="preg-${pregunta.id}" value="${pregunta.respuesta != null ? pregunta.respuesta : ''}">`
//                                             }
                                             
//                                         });
//                                         templatePreguntas += `</div></td>`
//                                     } else if( pregunta.tipo != 'empty') {
//                                         if (pregunta.tipo == 'ingreso' || pregunta.tipo == 'gasto' || pregunta.tipo == 'creditos') {

//                                             $(`#t${pregunta.tipo}`).append(`<tr>
//                                                                                                                     <td><label><h4>${pregunta.descripcion}</h4></label></td>
//                                                                                                                      <td><input type="text" id="preg-${pregunta.id}" value="${pregunta.respuesta != null ? pregunta.respuesta : ''}"> </td>   
//                                                                                                             </tr>`);
//                                         }else{
//                                             templatePreguntas += `<td><input type="${pregunta.tipo}" id="preg-${pregunta.id}" value="${pregunta.respuesta != null ? pregunta.respuesta : ''}"></td>`;
//                                         }
                                       
//                                     }
                                    
//                                 templatePreguntas += `</tr>`;
//                                 seccion = pregunta.seccion;
//                         }else{
//                                 let templateChecks = '';

//                                             if ( pregunta.tipo == 'check') {
//                                                 templateChecks = `<tr>
//                                                                 <td>
//                                                                     <label><h4>${pregunta.descripcion}</h4></label>
//                                                                 </td>
//                                                                 </tr>
//                                                                 <tr>
//                                                                     <td><div class='checkbox'>`;
//                                                 $.each(pregunta.opciones, function (j, respuestasCheck) {
                                                    
//                                                     if (respuestasCheck.tipo == 'checkbox') {
//                                                         templateChecks += `
//                                                                                 <label><input type="${respuestasCheck.tipo}" ${respuestasCheck.checked} value="${respuestasCheck.descripcion}" id="opc-${respuestasCheck.id}" class="preg-${pregunta.id}">${respuestasCheck.descripcion} </label>`
//                                                     } else {
//                                                         templateChecks += `
//                                                                                 <label>${respuestasCheck.descripcion} </label>
//                                                                                 <input type="text" class="preg-${pregunta.id}" opc="opc-${respuestasCheck.id}" value="${pregunta.respuesta != null ? pregunta.respuesta : ''}">`
//                                                     }

//                                                 })
//                                                 templateChecks += "</div>";
//                                                 templatePreguntas += templateChecks;
//                                             }else{
//                                                 if ( pregunta.tipo == 'ingreso' || pregunta.tipo == 'gasto' || pregunta.tipo == 'creditos' ) {
//                                                     $(`#t${pregunta.tipo}`).append(`<tr>
//                                                                                                                     <td><label><h4>${pregunta.descripcion}</h4></label></td>
//                                                                                                                      <td><input type="text" id="preg-${pregunta.id}" value="${pregunta.respuesta != null ? pregunta.respuesta : ''}"> </td>   
//                                                                                                             </tr>`);
//                                                 } else if( pregunta.tipo != 'empty' ) {
//                                                     pregunta.tipo = pregunta.tipo != 'text' ? 'text' : pregunta.tipo;
//                                                     templatePreguntas += `<tr>
//                                                         <td>
//                                                             <label><h4>${pregunta.descripcion}</h4></label>
//                                                         </td>
//                                                         </tr>
//                                                         <tr>
//                                                          <td> <input type="${pregunta.tipo}" id="preg-${pregunta.id}" value="${pregunta.respuesta != null ? pregunta.respuesta : ''}">`
                                                    
//                                                 }else{
//                                                     templatePreguntas += `<tr>
//                                                         <td colspan='2'>
//                                                             <label><h3>${pregunta.descripcion}</h3></label>
//                                                         </td>
//                                                         </tr>`;
//                                                 }
    
//                                             }
                                        
//                                     templatePreguntas += `</td></tr></div>
//                                 `;
//                         }
//                   }
// //Metodo cuando cambia el foco del input numero de personas que habitan en la casa
//                   $("#preg-40").focusout(function (e) {
//                       e.preventDefault();
//                       let numeroHabitantes = $(this).val();
//                       let teplateRowInputs = ''
//                       //creando los inputs de acuerdo a la cantiad de personas que habitan en la casa
//                       for (let i = 1; i <=  numeroHabitantes ; i++){
//                           teplateRowInputs += `<tr class="contenNFamiliares">
//                                                                         <td style="width:30%"><input  type="text" style="width:95%" id="famNombre-${i}"  placeholder="Nombre completo"></td>
//                                                                         <td><input  type="text" style="width:95%" id="famParen-${i}" placeholder="Parentesco"></td>
//                                                                         <td><input  type="text"  style="width:95%" id="famEdad-${i}" placeholder="Años"></td>
//                                                                         <td><input  type="text" style="width:95%" id="famEscolar-${i}" placeholder="Grado de estudios alcanzado"></td>
//                                                                         <td> <input type="text" style = "width:95%" id ="famOcupa-${i}" placeholder = "Ocupación"></td>
//                                                                     </tr>`;
                         
//                       }
//                       $("#elementFamiliar").html(teplateRowInputs );

//                   });

//                   //Agregando los comentarios,fecha y evaluador
//                   $("#nombreEvaluador").val(data.generalesEvaluacion.evaluador);
//                   $("#fechaEvaluacion").val( data.generalesEvaluacion.fechaRealizacion);
//                   $("#comentarios").val( data.generalesEvaluacion.comentarios );
//               }

//           },
//           "json"
//       );
        
// };

// $("#btnBuscarTrabajador").click(function (e) { 
//     $("#busquedaEmpleados").modal('toggle');
//     cargaDepartamento("selDepartamento");
    
// });

// const cargaDepartamento = function  (DOMelement) {
//     $.get("/nomina/ajax/controladores/administracion.php", { opc: 'getDepartamentos' },
//         function (data, textStatus, jqXHR) {
//             fillCombosKeyValue(DOMelement, data, "TODOS");
//             cargaTrabajadores(1);
//         },
//         "json"
//     );
//  };

// $("#filtraTrabajador").click(function (e) {
//     e.preventDefault();
//     cargaTrabajadores(1);
// });

//  const cargaTrabajadores = function ( indexProcesaData ) { 
//      const departamento = $("#selDepartamento").val();
//      const empleado = $("#nombreTrabajador").val();

//      $.get("/nomina/ajax/controladores/empleados.php", {
//          opc: "getEmpleadoDepartamento",
//          trabajador: empleado,
//          departamento: departamento
//      },
//          function (data, textStatus, jqXHR) {
//              template =``;
//              if ( indexProcesaData === 1) {
//                  trabajadores = data;
//                 $.each(data, function (i, item) { 
//                     template += `<tr onclick="trabajadorEvaluado('${item.nip}')" class="puntero">
//                                                 <td>${item.nombre}</td>
//                                                 <td>${item.departamento}</td>
//                                         </tr>`
//                 });
//                  $("#bustbody").html(template);
//              }
//          },
//          "json"
//      );
//   };

//  const fillCombosKeyValue = function (DOMelement, data, defaultItem = "Selecciona una opción") {
//         let templateOption = '';
//         $.each(data, function (i, item) { 
//              templateOption += `<option value="${item.id}" >
//                                                             ${item.descripcion}
//                                                 </option>`;
//         });
//         $(`#${DOMelement}`).html(`<option value="">${ defaultItem}</option> ${templateOption}`);
//    }





//    //Metodos para guardar  la informacion registrada por secciones

//    function guardaSeccion( seccion ) { 
//        let socioeconomico = $("#socioeconomico").val();
//       let preguntasEconomico =  listadoPreguntas.filter( function (element) {
//             return element.seccion == seccion;
//          });
//        $.each(preguntasEconomico, function (i, pregunta) { 
//            let respuesta = $(`#preg-${pregunta.id}`).val();
           
//            if ( respuesta == undefined) { //No tiene input text la pregunta, tiene checkbox
//                let checksDeRespuesta = $(`.preg-${pregunta.id}`);

//                for (let l = 0; l < checksDeRespuesta.length; l++) {
//                    if( checksDeRespuesta[l].checked ){
//                        console.log(preguntasEconomico[i] );
                       
//                        if (preguntasEconomico[i].respuesta != undefined ) {
//                            preguntasEconomico[i].respuesta += "#opc-" + checksDeRespuesta[l].value
//                        }else{
//                            preguntasEconomico[i].respuesta ="#opc-" + checksDeRespuesta[l].value
//                        }
//                    }
//                }
//            } else {
//                preguntasEconomico[i].respuesta = respuesta ;
//            }
            
//        });
    
//        $.post("/nomina/ajax/controladores/socioeconomico.php", {
//            opc: 'guardaSeccion',
//            preguntas: JSON.stringify( preguntasEconomico ),
//            socioeconomico: socioeconomico
//        },
//            function (data, textStatus, jqXHR) {
//              if ( data > 0) {
//                  alert("Guardado!");
//              }  
//            },
//            "text"
//        );
       
//         if ( seccion == "familia") {
//             let cantFamiliares = $("#preg-40").val();
//             if( cantFamiliares != "") {
//                 let camposFamiliar = [];
//                 for (let l = 1; l <=  cantFamiliares ; l++) {
                                    
//                     camposFamiliar.push({
//                             nombre: $(`#famNombre-${l}`).val(),
//                             edad: $(`#famEdad-${l}`).val(),
//                             parentesco: $(`#famParen-${l}`).val(),
//                             gradoEscolar: $(`#famEscolar-${l}`).val(),
//                             ocupacion: $(`#famOcupa-${l}`).val()
//                             });
                            
//                         }
//                     let nip = $("#nip").val();
//                         $.post("/nomina/ajax/controladores/empleados.php", {
//                             opc: "guardaFamiliares",
//                             empleado: nip,
//                             familiares: JSON.stringify( camposFamiliar )
//                         },
//                             function (data, textStatus, jqXHR) {
//                                 if ( data > 0) {
//                                     alert("Datos de familiares guardados");
//                                 }
//                             },
//                             "text"
//                         );
//                     }
//                 }
        
//            }
        
//            //Metodo para guardar los datos adicionales y culminar la evaluacion
// $("#guardaFinales").click(function (e) { 
//     const evaluador = $("#nombreEvaluador").val();
//     const fecha = $("#fechaEvaluacion").val();
//     const comentarios = $("#comentarios").val();
//     const evaluacion = $("#socioeconomico").val();
       
//     $.post("/nomina/ajax/controladores/socioeconomico.php", {
//         opc: "guardaFinal",
//         evaluador: evaluador,
//         fecha: fecha,
//         comentarios: comentarios,
//         evaluacion: evaluacion
//     },
//         function (data, textStatus, jqXHR) {
//             if ( data > 0 ) {
//                 alert("Socioeconomico guardado correctamente");
//             }
//         },
//         "text"
//     );
//    });

// //    Metodo para actualizar los datos personales del evaluado

// $("#guardaPersonal").click(function (e) {
//     e.preventDefault();
//     let empleado = $("#nombreEmpleado").val();
//     let calle = $("#calle_av").val();
//     let numInt = $("#numInt").val();
//     let numExt = $("#numExt").val();
//     let cp = $("#cp").val();
//     let colonia = $("#colonia").val();
//     let estado = $("#estado").val();
//     let municipio =$("#municipio").val();
//     let fechaNac = $("#nacimiento").val();
//     let lugarNac = $("#lugarNacimiento").val()
//     let edoCivil = $("#edoCivil").val();
//     let nip = $("#nip").val();
//     let socioeconomico = $("#socioeconomico").val()

//     $.post("/nomina/ajax/controladores/socioeconomico.php", {
//         opc: 'guardaPersonalData',
//         nip: nip,
//         empleado: empleado,
//         calle: calle,
//         numInt: numInt,
//         numExt: numExt,
//         cp: cp,
//         colonia: colonia,
//         estado: estado,
//         municipio: municipio,
//         fechaNac: fechaNac,
//         lugarNac: lugarNac,
//         edoCivil: edoCivil,
//         socioeconomico: socioeconomico
//     },
//         function (data, textStatus, jqXHR) {
//             if (data > 0) {
//                 alert("Datdos Guardados");
//             }
//         },
//         "text"
//     );
// });


// $("#fotoSocioeconomico").change(function (e) {
//     e.preventDefault();
    
//      var formData = new FormData();
//     // let lectorImagen = new FileReader();

//         let nombre = prompt("Por favor igresa un nombre a la fotografía: ",'');
//         formData.append('opc','getImagenesSocioeconomico');
//         formData.append('imagen', $(this).prop('files')[0]);
//         formData.append("nombreFoto", nombre);
//         formData.append("empleado", $("#nip").val() );

//         $.ajax({
//             type: "post",
//             url: "/nomina/ajax/controladores/socioeconomico.php",
//             data:  formData,
//             enctype: 'multipart/form-data',
//             processData: false,
//             contentType: false,
//             success: function (response) {
//                 if ( response == 1) {
//                     alert("Imagen almacenada correctamente");
//                 } else {
//                     alert( "Ocurrió un error al alamacenar el archivo, por favor intentalo nuevamente");
//                 }
//             }
//         });
// });


// //Metodo para limpiar las secciones al dar clic sobre el boton  nuevo

// function limpiaSeccion( seccion ) {
    
//     let preguntasEconomico = listadoPreguntas.filter(function (element) {
//         return element.seccion == seccion;
//     });
//     $.each(preguntasEconomico, function (i, pregunta) {
//         let inputPregunta = $(`#preg-${pregunta.id}`).val();

//         if (inputPregunta == undefined) { //No tiene input text la pregunta, tiene checkbox
//             let checksDeRespuesta = $(`.preg-${pregunta.id}`);

//             for (let l = 0; l < checksDeRespuesta.length; l++) {
//                 if (checksDeRespuesta[l].checked) {
                    
//                     $(checksDeRespuesta[l]).prop('checked', false);
                    
//                 }
//                 if ($(checksDeRespuesta[l]).attr('type') == 'text' ) {
//                     $(checksDeRespuesta[l]).val("");
//                 }
//             }
//         } else {
//             $(`#preg-${pregunta.id}`).val('');
//         }

//     });
// }




"use strict";

var trabajadores;
var listadoPreguntas;

var trabajadorEvaluado = function trabajadorEvaluado(trabajador) {
  $.get("/nomina/ajax/controladores/socioeconomico.php", {
    opc: 'verificaEvaluacionRealizada',
    trabajador: trabajador
  }, function (data, textStatus, jqXHR) {
    $("#busquedaEmpleados").modal("hide");
    getDatosTrabajador(trabajador);

    if (data == -1) {
      cargaEvaluacion('');
    } else if (data > 0) {
      cargaEvaluacion(data);
    }
  }, "text");
};

var getDatosTrabajador = function getDatosTrabajador(trabajador) {
  var trabajadorSeleccionado = trabajadores.find(function (itemTrabajador) {
    return itemTrabajador.nip == trabajador;
  });
  $("#nip").val(trabajador);
  getDatosPersonales(trabajador);
};

var getDatosPersonales = function getDatosPersonales(trabajador) {
  $.get("/nomina/ajax/controladores/empleados.php", {
    opc: 'getPersonalData',
    trabajador: trabajador
  }, function (data, textStatus, jqXHR) {
    $.each(data, function (i, item) {
      $("#nombreEmpleado").val(item.nombre);
      $("#calle_av").val(item.calle);
      $("#numInt").val(item.numint);
      $("#numExt").val(item.numext);
      $("#cp").val(item.cp);
      $("#colonia").val(item.colonia);
      $("#estado").val();
      $("#municipio").val(item.municipio);
      $("#nacimiento").val(item.fechanac);
      $("#lugarNacimiento").val();
      $("#edoCivil").val(item.edocivil);
    });
  }, "json");
};

var cargaEvaluacion = function cargaEvaluacion(evaluacion) {
  $("#socioeconomico").val(evaluacion);
  $.get("/nomina/ajax/controladores/socioeconomico.php", {
    opc: 'getEvaluacion',
    evaluacion: evaluacion
  }, function (data, textStatus, jqXHR) {
    if (data.evaluacion.length > 0) {
      $(".oculto").fadeIn();
      listadoPreguntas = data.evaluacion;
      var seccion = data.evaluacion[0].descripcion;
      var templatePreguntas = '';
      var cantPreguntas = data.evaluacion.length;
      $("#tingreso").html("");
      $("#tgasto").html("");
      $("#tcreditos").html("");

      var _loop = function _loop(i) {
        var pregunta = data.evaluacion[i];
        pregunta = pregunta == undefined ? {
          seccion: ''
        } : pregunta;

        if (pregunta.seccion != seccion || pregunta.seccion == '') {
          //Agregando el contendio del template al div de la seccion
          if (seccion != "economico") {
            $("#t".concat(seccion)).html(templatePreguntas);
          }

          if (pregunta.seccion == '') {
            //Ya se terminaron las preguntas por seccion?
            return "break"; //finaliza el ciclo  y termina la renderización
          }

          templatePreguntas = "\n                               <tr>\n                                        <td><label><h4>".concat(pregunta.descripcion, "</h4></label></td>\n                                </tr>\n                                <tr>");

          if (pregunta.opciones.length > 0) {
            templatePreguntas += "<td><div class=\"checkbox\">";
            $.each(pregunta.opciones, function (j, itemOpcion) {
              if (itemOpcion.tipo == 'checkbox') {
                templatePreguntas += "<label><input type=\"".concat(itemOpcion.tipo, "\" value=\"").concat(itemOpcion.descripcion, "\" id=\"opc-").concat(itemOpcion.id, "\" ").concat(itemOpcion.checked, " class=\"preg-").concat(pregunta.id, "\"> ").concat(itemOpcion.descripcion, " </label>");
              } else {
                templatePreguntas += "\n                                                                                <label>".concat(itemOpcion.descripcion, " </label>\n                                                                                <input type=\"text\" id=\"opc-").concat(itemOpcion.id, "\" class=\"preg-").concat(pregunta.id, "\" value=\"").concat(pregunta.respuesta != null ? pregunta.respuesta : '', "\">");
              }
            });
            templatePreguntas += "</div></td>";
          } else if (pregunta.tipo != 'empty') {
            if (pregunta.tipo == 'ingreso' || pregunta.tipo == 'gasto' || pregunta.tipo == 'creditos') {
              $("#t".concat(pregunta.tipo)).append("<tr>\n                                                                                                                    <td><label><h4>".concat(pregunta.descripcion, "</h4></label></td>\n                                                                                                                     <td><input type=\"text\" id=\"preg-").concat(pregunta.id, "\" value=\"").concat(pregunta.respuesta != null ? pregunta.respuesta : '', "\"> </td>   \n                                                                                                            </tr>"));
            } else {
              templatePreguntas += "<td><input type=\"".concat(pregunta.tipo, "\" id=\"preg-").concat(pregunta.id, "\" value=\"").concat(pregunta.respuesta != null ? pregunta.respuesta : '', "\"></td>");
            }
          }

          templatePreguntas += "</tr>";
          seccion = pregunta.seccion;
        } else {
          var templateChecks = '';

          if (pregunta.tipo == 'check') {
            templateChecks = "<tr>\n                                                                <td>\n                                                                    <label><h4>".concat(pregunta.descripcion, "</h4></label>\n                                                                </td>\n                                                                </tr>\n                                                                <tr>\n                                                                    <td><div class='checkbox'>");
            $.each(pregunta.opciones, function (j, respuestasCheck) {
              if (respuestasCheck.tipo == 'checkbox') {
                templateChecks += "\n                                                                                <label><input type=\"".concat(respuestasCheck.tipo, "\" ").concat(respuestasCheck.checked, " value=\"").concat(respuestasCheck.descripcion, "\" id=\"opc-").concat(respuestasCheck.id, "\" class=\"preg-").concat(pregunta.id, "\">").concat(respuestasCheck.descripcion, " </label>");
              } else {
                templateChecks += "\n                                                                                <label>".concat(respuestasCheck.descripcion, " </label>\n                                                                                <input type=\"text\" class=\"preg-").concat(pregunta.id, "\" opc=\"opc-").concat(respuestasCheck.id, "\" value=\"").concat(pregunta.respuesta != null ? pregunta.respuesta : '', "\">");
              }
            });
            templateChecks += "</div>";
            templatePreguntas += templateChecks;
          } else {
            if (pregunta.tipo == 'ingreso' || pregunta.tipo == 'gasto' || pregunta.tipo == 'creditos') {
              $("#t".concat(pregunta.tipo)).append("<tr>\n                                                                                                                    <td><label><h4>".concat(pregunta.descripcion, "</h4></label></td>\n                                                                                                                     <td><input type=\"text\" id=\"preg-").concat(pregunta.id, "\" value=\"").concat(pregunta.respuesta != null ? pregunta.respuesta : '', "\"> </td>   \n                                                                                                            </tr>"));
            } else if (pregunta.tipo != 'empty') {
              pregunta.tipo = pregunta.tipo != 'text' ? 'text' : pregunta.tipo;
              templatePreguntas += "<tr>\n                                                        <td>\n                                                            <label><h4>".concat(pregunta.descripcion, "</h4></label>\n                                                        </td>\n                                                        </tr>\n                                                        <tr>\n                                                         <td> <input type=\"").concat(pregunta.tipo, "\" id=\"preg-").concat(pregunta.id, "\" value=\"").concat(pregunta.respuesta != null ? pregunta.respuesta : '', "\">");
            } else {
              templatePreguntas += "<tr>\n                                                        <td colspan='2'>\n                                                            <label><h3>".concat(pregunta.descripcion, "</h3></label>\n                                                        </td>\n                                                        </tr>");
            }
          }

          templatePreguntas += "</td></tr></div>\n                                ";
        }
      };

      for (var i = 0; i <= cantPreguntas; i++) {
        var _ret = _loop(i);

        if (_ret === "break") break;
      } //Metodo cuando cambia el foco del input numero de personas que habitan en la casa


      $("#preg-40").focusout(function (e) {
        e.preventDefault();
        var numeroHabitantes = $(this).val();
        var teplateRowInputs = ''; //creando los inputs de acuerdo a la cantiad de personas que habitan en la casa

        for (var _i = 1; _i <= numeroHabitantes; _i++) {
          teplateRowInputs += "<tr class=\"contenNFamiliares\">\n                                                                        <td style=\"width:30%\"><input  type=\"text\" style=\"width:95%\" id=\"famNombre-".concat(_i, "\"  placeholder=\"Nombre completo\"></td>\n                                                                        <td><input  type=\"text\" style=\"width:95%\" id=\"famParen-").concat(_i, "\" placeholder=\"Parentesco\"></td>\n                                                                        <td><input  type=\"text\"  style=\"width:95%\" id=\"famEdad-").concat(_i, "\" placeholder=\"A\xF1os\"></td>\n                                                                        <td><input  type=\"text\" style=\"width:95%\" id=\"famEscolar-").concat(_i, "\" placeholder=\"Grado de estudios alcanzado\"></td>\n                                                                        <td> <input type=\"text\" style = \"width:95%\" id =\"famOcupa-").concat(_i, "\" placeholder = \"Ocupaci\xF3n\"></td>\n                                                                    </tr>");
        }

        $("#elementFamiliar").html(teplateRowInputs);
      }); //Agregando los comentarios,fecha y evaluador

      $("#nombreEvaluador").val(data.generalesEvaluacion.evaluador);
      $("#fechaEvaluacion").val(data.generalesEvaluacion.fechaRealizacion);
      $("#comentarios").val(data.generalesEvaluacion.comentarios);
    }
  }, "json");
};

$("#btnBuscarTrabajador").click(function (e) {
  $("#busquedaEmpleados").modal('toggle');
  cargaDepartamento("selDepartamento");
});

var cargaDepartamento = function cargaDepartamento(DOMelement) {
  $.get("/nomina/ajax/controladores/administracion.php", {
    opc: 'getDepartamentos'
  }, function (data, textStatus, jqXHR) {
    fillCombosKeyValue(DOMelement, data, "TODOS");
    cargaTrabajadores(1);
  }, "json");
};

$("#filtraTrabajador").click(function (e) {
  e.preventDefault();
  cargaTrabajadores(1);
});

var cargaTrabajadores = function cargaTrabajadores(indexProcesaData) {
  var departamento = $("#selDepartamento").val();
  var empleado = $("#nombreTrabajador").val();
  $.get("/nomina/ajax/controladores/empleados.php", {
    opc: "getEmpleadoDepartamento",
    trabajador: empleado,
    departamento: departamento
  }, function (data, textStatus, jqXHR) {
    var template = "";

    if (indexProcesaData === 1) {
      trabajadores = data;
      $.each(data, function (i, item) {
        template += "<tr onclick=\"trabajadorEvaluado('".concat(item.nip, "')\" class=\"puntero\">\n                                                <td>").concat(item.nombre, "</td>\n                                                <td>").concat(item.departamento, "</td>\n                                        </tr>");
      });
      $("#bustbody").html(template);
    }
  }, "json");
};

var fillCombosKeyValue = function fillCombosKeyValue(DOMelement, data) {
  var defaultItem = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : "Selecciona una opción";
  var templateOption = '';
  $.each(data, function (i, item) {
    templateOption += "<option value=\"".concat(item.id, "\" >\n                                                            ").concat(item.descripcion, "\n                                                </option>");
  });
  $("#".concat(DOMelement)).html("<option value=\"\">".concat(defaultItem, "</option> ").concat(templateOption));
}; //Metodos para guardar  la informacion registrada por secciones


function guardaSeccion(seccion) {
  var socioeconomico = $("#socioeconomico").val();
  var preguntasEconomico = listadoPreguntas.filter(function (element) {
    return element.seccion == seccion;
  });
  $.each(preguntasEconomico, function (i, pregunta) {
    var respuesta = $("#preg-".concat(pregunta.id)).val();

    if (respuesta == undefined) {
      //No tiene input text la pregunta, tiene checkbox
      var checksDeRespuesta = $(".preg-".concat(pregunta.id));
        
      for (var l = 0; l < checksDeRespuesta.length; l++) {
        if (checksDeRespuesta[l].checked) {
          console.log(preguntasEconomico[i]);

          if (preguntasEconomico[i].respuesta != undefined) {
            preguntasEconomico[i].respuesta += "#opc-" + checksDeRespuesta[l].value;
          } else {
            preguntasEconomico[i].respuesta = "#opc-" + checksDeRespuesta[l].value;
          }
        }
      }
    } else {
      preguntasEconomico[i].respuesta = respuesta;
    }
  });
//   alert( JSON.stringify(preguntasEconomico) )
  $.post("/nomina/ajax/controladores/socioeconomico.php", {
    opc: 'guardaSeccion',
    preguntas: JSON.stringify(preguntasEconomico),
    socioeconomico: socioeconomico
  }, function (data, textStatus, jqXHR) {
    if (data > 0) {
      alert("Guardado!");
    }
  }, "text");

  if (seccion == "familia") {
    var cantFamiliares = $("#preg-40").val();

    if (cantFamiliares != "") {
      var camposFamiliar = [];

      for (var l = 1; l <= cantFamiliares; l++) {
        camposFamiliar.push({
          nombre: $("#famNombre-".concat(l)).val(),
          edad: $("#famEdad-".concat(l)).val(),
          parentesco: $("#famParen-".concat(l)).val(),
          gradoEscolar: $("#famEscolar-".concat(l)).val(),
          ocupacion: $("#famOcupa-".concat(l)).val()
        });
      }

      var nip = $("#nip").val();
      $.post("/nomina/ajax/controladores/empleados.php", {
        opc: "guardaFamiliares",
        empleado: nip,
        familiares: JSON.stringify(camposFamiliar)
      }, function (data, textStatus, jqXHR) {
        if (data > 0) {
          alert("Datos de familiares guardados");
        }
      }, "text");
    }
  }
} //Metodo para guardar los datos adicionales y culminar la evaluacion


$("#guardaFinales").click(function (e) {
  var evaluador = $("#nombreEvaluador").val();
  var fecha = $("#fechaEvaluacion").val();
  var comentarios = $("#comentarios").val();
  var evaluacion = $("#socioeconomico").val();
  $.post("/nomina/ajax/controladores/socioeconomico.php", {
    opc: "guardaFinal",
    evaluador: evaluador,
    fecha: fecha,
    comentarios: comentarios,
    evaluacion: evaluacion
  }, function (data, textStatus, jqXHR) {
    if (data > 0) {
      alert("Socioeconomico guardado correctamente");
    }
  }, "text");
}); //    Metodo para actualizar los datos personales del evaluado

$("#guardaPersonal").click(function (e) {
  e.preventDefault();
  var empleado = $("#nombreEmpleado").val();
  var calle = $("#calle_av").val();
  var numInt = $("#numInt").val();
  var numExt = $("#numExt").val();
  var cp = $("#cp").val();
  var colonia = $("#colonia").val();
  var estado = $("#estado").val();
  var municipio = $("#municipio").val();
  var fechaNac = $("#nacimiento").val();
  var lugarNac = $("#lugarNacimiento").val();
  var edoCivil = $("#edoCivil").val();
  var nip = $("#nip").val();
  var socioeconomico = $("#socioeconomico").val();
  $.post("/nomina/ajax/controladores/socioeconomico.php", {
    opc: 'guardaPersonalData',
    nip: nip,
    empleado: empleado,
    calle: calle,
    numInt: numInt,
    numExt: numExt,
    cp: cp,
    colonia: colonia,
    estado: estado,
    municipio: municipio,
    fechaNac: fechaNac,
    lugarNac: lugarNac,
    edoCivil: edoCivil,
    socioeconomico: socioeconomico
  }, function (data, textStatus, jqXHR) {
    if (data > 0) {
      alert("Datdos Guardados");
    }
  }, "text");
});
$("#fotoSocioeconomico").change(function (e) {
  e.preventDefault();
  var formData = new FormData(); // let lectorImagen = new FileReader();

  var nombre = prompt("Por favor igresa un nombre a la fotografía: ", '');
  formData.append('opc', 'getImagenesSocioeconomico');
  formData.append('imagen', $(this).prop('files')[0]);
  formData.append("nombreFoto", nombre);
  formData.append("empleado", $("#nip").val());
  $.ajax({
    type: "post",
    url: "/nomina/ajax/controladores/socioeconomico.php",
    data: formData,
    enctype: 'multipart/form-data',
    processData: false,
    contentType: false,
    success: function success(response) {
      if (response == 1) {
        alert("Imagen almacenada correctamente");
      } else {
        alert("Ocurrió un error al alamacenar el archivo, por favor intentalo nuevamente");
      }
    }
  });
}); //Metodo para limpiar las secciones al dar clic sobre el boton  nuevo

function limpiaSeccion(seccion) {
  var preguntasEconomico = listadoPreguntas.filter(function (element) {
    return element.seccion == seccion;
  });
  $.each(preguntasEconomico, function (i, pregunta) {
    var inputPregunta = $("#preg-".concat(pregunta.id)).val();

    if (inputPregunta == undefined) {
      //No tiene input text la pregunta, tiene checkbox
      var checksDeRespuesta = $(".preg-".concat(pregunta.id));

      for (var l = 0; l < checksDeRespuesta.length; l++) {
        if (checksDeRespuesta[l].checked) {
          $(checksDeRespuesta[l]).prop('checked', false);
        }

        if ($(checksDeRespuesta[l]).attr('type') == 'text') {
          $(checksDeRespuesta[l]).val("");
        }
      }
    } else {
      $("#preg-".concat(pregunta.id)).val('');
    }
  });
}