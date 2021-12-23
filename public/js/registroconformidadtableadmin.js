var listOfRegisters = [];
$(document).ready(function () {
    const renderTable = (data) => {
        $("#datatableregistros").DataTable().destroy();
        $("#datatableregistros tbody").empty();
        data.forEach(function (registro) {
            $("#datatableregistros tbody").append(`
    <tr>
          <td style="width:30px"> ${registro.orden_de_transporte}</td>
          <td style="width:50px">${registro.guias_de_remision}</td>
          <td style="width:100px">
              <div class="row">
                  <div class="col-md-12">
                      <strong> ${registro.sap_transportista}</strong>
                  </div>
                  <div class="col-md-12">
                      ${registro.transportista}
                  </div>
              </div>
          </td>
          <td style="max-width:200px;overflow:hidden">
              ${moment(registro.hora_de_ingreso).format(
                  "DD/MM/YYYY HH:mm:ss"
              )}</td>
          <td>${moment(registro.hora_de_salida).format(
              "DD/MM/YYYY HH:mm:ss"
          )}</td>
          <td style="max-width:200px;overflow:hidden">
                    ${
                        moment(registro.hora_de_llegada_cliente).isValid()
                            ? moment(registro.hora_de_llegada_cliente).format(
                                  "DD/MM/YYYY HH:mm:ss"
                              )
                            : "Pendiente"
                    }
          </td>
          <td>${
              moment(registro.hora_de_descarga_cliente).isValid()
                  ? moment(registro.hora_de_descarga_cliente).format(
                        "DD/MM/YYYY HH:mm:ss"
                    )
                  : "Pendiente"
          }  </td>
          <td>${registro.sede} </td>
          <td>${registro.destino}</td>
          <td >${registro.estado_tracking} </td>
          <td style="width:30px;" class="text-center"> &nbsp;&nbsp;
          <i class="fa fa-list"  role="button" data-bs-auto-close="outside" data-bs-toggle="dropdown" aria-expanded="false">
          </i>
            <ul class="dropdown-menu" style="style="font-size:1em !important" data-bs-auto-close="outside" >
                <li><a class="dropdown-item" registro='${
                    registro.id
                }' onclick="showModalDocumentos(this)" href="javascript:void(0)">Documentos</a></li>
                <li><a class="dropdown-item" registro='${
                    registro.id
                }' onclick="showModalObservaciones(this)" href="javascript:void(0)">Observaciones</a></li>
                
               ${
                   registro.estado_tracking == "PENDIENTE"
                       ? `<li><a class="dropdown-item" registro='${registro.id}' onclick="showModalPedirGuias(this)" href="javascript:void(0)" >Pedir Guias</a></li>`
                       : ``
               }
                <li><a class="dropdown-item" registro='${
                    registro.id
                }' onclick="showModalDetalles(this)"  href="javascript:void(0)">Mas detalles..</a></li>
            </ul>
         </td>
    </tr>`);
        });
        $("#datatableregistros")
            .DataTable({
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json",
                },
            })
            .draw();
    };
    const getRegistrosConformidad = async (data) => {
        $("#buscandoarchivos").show();
        const response = await $.ajax({
            url: "/dashboard/registros_de_conformidad",
            type: "GET",
            data,
        });
        listOfRegisters = response.registros ? response.registros : [];
        return response;
    };

    $("#formfilter").on("submit", function (ev) {
        ev.preventDefault();
        const values = $(this).serializeArray();
        var data = values.reduce(function (obj, item) {
            obj[item.name] = item.value;
            return obj;
        }, {});
        (async () => {
            const response = await getRegistrosConformidad(data);
            renderTable(response.registros);
            $("#buscandoarchivos").hide();
        })();
    });
});
function renderPendientes(data, index) {
    $(index).parent().show();
    $(index).empty();
    data.forEach(function (e) {
        $(index).append(`
             <li>${e}</li>
        `);
    });
    if (!data.length) {
        $(index).parent().hide();
    }
}
function showModalDocumentos(ev) {
    var id = $(ev).attr("registro");
    var registro = listOfRegisters.find((e) => e.id == id);
    $("#modaldocumentos").modal("show");
    if (registro.pdf_guia_cobranza) {
        $("#contentdocumentsempty").hide();
        var guias_de_cobranza = registro.pdf_guia_cobranza.split(";");
        var guias_de_transportista = registro.pdf_guia_transportista.split(";");
        $("#contentguiastransportista").empty();
        $("#contentguiascobranza").empty();
        guias_de_transportista.forEach(function (e) {
            let nroguia = e.split("_")[3];
            $("#contentguiastransportista").append(
                ` <div class="col-md-12 my-2">
          <a href="${e}" target="_blank" class="btn btn-danger mx-1"> <small>              
                 Guia ${nroguia} transportista
            </small> 
          </a>
      </div>
        `
            );
        });
        guias_de_cobranza.forEach(function (e) {
            let nroguia = e.split("_")[3];
            $("#contentguiascobranza").append(
                `<div class="col-md-12 my-2"> <a href="${e}" target="_blank"
            class="btn btn-danger mx-1"> <small>Guia ${nroguia} cobranza</small>
        </a></div>`
            );
        });
    } else {
        $("#contentguiastransportista").empty();
        $("#contentguiascobranza").empty();
        $("#contentdocumentsempty").show();
    }

    var guias_entregadas = [];
    var guias_pendientes = [];
    if (registro.pdf_guia_transportista) {
        var linksarchivos = registro.pdf_guia_transportista.split(";");
        var entregados = linksarchivos.reduce((a, b) => {
            var value = b.split("_")[3];
            if (value) {
                a.push(value);
            }
            return a;
        }, []);
        guias_entregadas = entregados;
        var all_guias = registro.guias_de_remision.split("/");
        var pendientes = all_guias.reduce((a, b) => {
            var entregado = guias_entregadas.find((x) => {
                return x == b || x + "*" == b;
            });
            if (!entregado) {
                a.push(b);
            }
            return a;
        }, []);
        guias_pendientes = pendientes;
    } else {
        guias_pendientes = registro.guias_de_remision.split("/");
    }
    renderPendientes(guias_pendientes, "#registrosguiaspendientes");
}
function showModalObservaciones(ev) {
    var id = $(ev).attr("registro");
    var registro = listOfRegisters.find((e) => e.id == id);
    $("#modalobservaciones").modal("show");
    $("#loadingobservaciones").show();
    $("#contentobservaciones").empty();
    $("#contentobservacionesempty").empty();
    $.ajax({
        url: "/dashboard/registro_observaciones",
        type: "GET",
        data: {
            id: id,
        },
        success: function (response) {
            $("#loadingobservaciones").hide();
            if (!response.data.length) {
                $("#contentobservaciones").empty();
                $("#contentobservacionesempty").html(`
                <div class="alert alert-info">
                Este registro no tiene observaciones
             </div>
                `);
            } else {
                $("#contentobservacionesempty").empty();
                $("#contentobservaciones").empty();
                response.data.forEach(function (e) {
                    $("#contentobservaciones").append(`
                    <div class="col-md-12 my-2">
                    ${e.nombre}
                    ${e.cantidad}
                    </div>
                    `);
                });
            }
        },
    });
}
function showModalPedirGuias(ev) {
    var id = $(ev).attr("registro");
    var registro = listOfRegisters.find((e) => e.id == id);
    var guias_entregadas = [];
    var guias_pendientes = [];
    if (registro.pdf_guia_transportista) {
        var linksarchivos = registro.pdf_guia_transportista.split(";");
        var entregados = linksarchivos.reduce((a, b) => {
            var value = b.split("_")[3];
            if (value) {
                a.push(value);
            }
            return a;
        }, []);
        guias_entregadas = entregados;
        var all_guias = registro.guias_de_remision.split("/");
        var pendientes = all_guias.reduce((a, b) => {
            var entregado = guias_entregadas.find((x) => {
                return x == b || x + "*" == b;
            });
            if (!entregado) {
                a.push(b);
            }
            return a;
        }, []);
        guias_pendientes = pendientes;
    } else {
        guias_pendientes = registro.guias_de_remision.split("/");
    }
    $("#modalNotificacion").modal("show");
    $("#footermodalnotification").html(`
    <button type="button" class="btn btn-secondary"
    data-bs-dismiss="modal">Cerrar</button>
    <button type="button" class="btn btn-primary"
    onclick="submitRequestGuia( ${registro.id})">Enviar</button>`);

    renderPendientes(guias_pendientes, `#sendNotificacionRegistro`);
}
function showModalDetalles(ev) {
    var id = $(ev).attr("registro");
    var registro = listOfRegisters.find((r) => r.id == id);
    console.log(registro);
    $("#modaldetalle").modal("show");
    $("#modaldetallecontent").html(
        `
        <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="" class="w-100 fw-bold">Codigo OT</label>
                <p class="w-100">
                   ${registro.orden_de_transporte} 
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="" class="w-100 fw-bold">Pedido(s)</label>
                <p class="w-100">
                   ${registro.pedido}
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for=""
                    class="w-100 fw-bold">Transportista</label>
                <p class="w-100">
                    ${registro.transportista} 
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="" class="w-100 fw-bold">Cliente</label>
                <p class="w-100">
                    ${registro.cliente} 
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="" class="w-100 fw-bold">Chofer</label>
                <p class="w-100">
                    ${registro.chofer}
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="" class="w-100 fw-bold">Placa</label>
                <p class="w-100">
                    ${registro.placa_tracto}
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="" class="w-100 fw-bold">Entrega(s)</label>
                <p class="w-100">
                    ${registro.entrega}
                </p>
            </div>
        </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="" class="w-100 fw-bold">Tipo de
                        material</label>
                    <p class="w-100">
                     ${registro.tipo_material ? registro.tipo_material : "-"}
                    </p>
                </div>
            </div>
         </div>
        `
    );
}
