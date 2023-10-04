<main>

    <div class="container">
        <div class="tracking">
            <div class="tracking__formContainer">
                <form id="form--tracking">
                    <fieldset>
                        <div>
                            <div class="input-group">
                                <span class="input-group-text" id=""><i class="bi bi-file-text"></i></span>
                                <input type="text" name="folio" class="form-control fs-4" placeholder="Folio">
                            </div>
                        </div>

                        <div>
                            <div>
                                <select class="form-select fs-4" name="petition" aria-label="Default select example">
                                    <option selected value="">Seleccionar</option>
                                    <option>Promesa realizada</option>
                                    <option>Autorizar Ventana PP</option>
                                    <option>CallBack Programado</option>
                                    <option>Carta Convenio</option>
                                    <option>Cancelacion de Agreements</option>
                                    <option>Carta Propuesta</option>
                                    <option>CONVENIO NO RESPETADO SERTEC</option>
                                    <option>Carta finiquito</option>
                                    <option>Correo de Prueba</option>
                                    <option>Entrega de Auto GMF</option>
                                    <option>Comentario OX</option>
                                    <option>Documentación para OX</option>
                                    <option>LMT</option>
                                    <option>Pago Efectuado</option>
                                    <option>Formas de Pago</option>
                                    <option>Ofree Rees</option>
                                    <option>Grabacion Rees</option>
                                    <option>Validacion Pago Para Rees</option>
                                    <option>Pago Para Grabar REE</option>
                                    <option>Plazo negociaciones WO</option>
                                    <option>Desligue</option>
                                    <option>Desbloqueo Cyber</option>
                                    <option>Eliminar Promesa</option>
                                    <option>Eliminación de Saldo</option>
                                    <option>Enviar Visita</option>
                                    <option>Posible Queja</option>
                                    <option>Propuesta por Whats App</option>
                                    <option>Recordatorio por Whats App</option>
                                    <option>Recordatorio de Pago por Mail</option>
                                    <option>Recordatorio para Barredor</option>
                                    <option>Saldo y Movimientos OnLine</option>
                                    <option>Seguimiento a Cuenta</option>
                                    <option>Subir Agreement en Linea</option>
                                    <option>Mandar Skip</option>
                                    <option>Mandar Visita</option>
                                    <option>Simulador de Reestructura</option>
                                    <option>Validar Adicionales</option>
                                    <option>Validar Promesa</option>
                                    <option>Propuesta de Pago</option>
                                    <option>Excepción</option>
                                    <option>Calendario de Pagos</option>
                                    <option>Revisión Supervisor</option>
                                    <option>Negativa de pago</option>
                                    <option>Convenio doble fuego</option>
                                    <option>Convenio vacantes</option>
                                    <option>Solicita apoyo</option>
                                    <option>Convenio vigente</option>
                                    <option>Cambio de domicilio</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <div class="input-group">
                                <span class="input-group-text">Descripcion</span>
                                <textarea class="form-control fs-4" name="description" aria-label="With textarea"></textarea>
                            </div>
                        </div>

                        <div class="tracking__buttons">
                            <button type="button" class="tracking__buttons--send button-success">Enviar folio</button>
                        </div>
                    </fieldset>
                </form>
            </div>
            <div class="tracking__tableTrack">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Folio</th>
                            <th scope="col">Peticion</th>
                            <th scope="col">Description</th>
                            <th scope="col">Comentario Supervisor</th>
                            <th scope="col">Status</th>
                            <th scope="col">Fecha</th>
                        </tr>
                    </thead>
                    <tbody id="tableTrack--Body">
                        <?php echo $folioTemplate?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="tracking__ManualTrack">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Folio</th>
                        <th scope="col">Cliente</th>
                        <th scope="col">Claves</th>
                        <th scope="col">Fecha</th>
                        <th scope="col">Estrategia</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row">1</th>
                        <td>Mark</td>
                        <td>Otto</td>
                        <td>@mdo</td>
                        <td>@mdo</td>
                        <td>@mdo</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>

    <div class="toast--notification"></div>

</main>