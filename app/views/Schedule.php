<main class="main--schedule">
    <div class="schedule container">
        <div class="schedule--formcontainer">
            <form class="schedule__form" name="schedule--form" id="scheduleForm">
                <fieldset class="schedule__fieldset">
                    <div>
                        <label for="form--folio">Folio</label>
                        <div>
                            <span>icon</span>
                            <input id="form--folio" type="text" name="folio" placeholder="Insertar folio">
                        </div>
                    </div>
                    <div>
                        <label for="form--comment">Nota</label>
                        <textarea id="form--comment" class="form-control" aria-label="With textarea" name="comment" maxlength="255"></textarea>
                    </div>
                    <div class="schedule--div__buttons">
                        <button type="button" id="sendForm--comment" class="schedule__button">Guardar nota</button>
                        <button type="button" data-bs-toggle="modal" data-bs-target="#staticBackdrop" 
                            id="buttonModal--notes" class="schedule__button">
                            Ver notas
                        </button>
                    </div>
                </fieldset>
                <div class="schedule__follow-Up">
                    <button data-bs-toggle="modal" data-bs-target="#staticBackdrop" 
                    id="buttonModal--follow-up" type="button" class="schedule__button">
                        Seguimiento
                    </button>
                </div>
            </form>
        </div>
        <div class="schedule__graphics">
            <canvas class="graphics--canvas" id="Promises"></canvas>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" 
     aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-3" id="title--modal"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body schedule__modalBody" id="tableBody">
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary fs-3" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <?php echo $graphic ?>
    <div class="toast--notification"></div>
    
</main>