<main>
    <div class="container">

    </div>
    <div class="container productivity__container">
        <div class="productivity">
            <form class="form__productivity" id="form--productivity">
                <fieldset class="productivity__fieldset">
                    <div class="productivity__markingInputDiv">
                        <div class="input-group mb-3">
                            <label class="input-group-text" for="layout--File"><i class="bi bi-filetype-csv"></i></label>
                            <input type="file" name="manualMarkingFile" class="form-control fs-2" id="layout--File">
                        </div>
                    </div>

                    <div class="productivity__buttons">
                        <button type="button" class="button-success" id="productivity-addFile">
                            <i class="bi bi-file-earmark-plus-fill"></i> Subir Archivo
                        </button>
                        <button type="button" class="button-danger" id="productivity-delFile">
                            <i class="bi bi-file-minus-fill"></i> Eliminar Archivo
                        </button>
                    </div>
                </fieldset>
            </form>
        </div>

    </div>

    <div class="toast--notification"></div>
</main>