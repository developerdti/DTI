<main class="main--modify">
    <div class="user container">
        <div class="accordion accordion-flush" id="accordionFlushExample">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                    data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                        CAMBIAR CONTRASEÑA
                    </button>
                </h2>
                <div id="flush-collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                    <form id="user__changepassword" name="form--changepassword" class="form__changepassword">
                        <fieldset class="fieldset">
                            <div class="fieldset__oldpassword fieldset__child">
                                <label for="oldPassword">Contraseña Actual</label>
                                <div class="fieldset__oldpassword__child">
                                    <span>
                                        <i class="h2 bi bi-key-fill"></i>
                                    </span>
                                    <input id="oldPassword" type="password" name="oldpassword" 
                                    placeholder="Ingresar contraseña vieja" require>
                                </div>
                            </div>
                            <div class="fieldset__newpassword fieldset__child">
                                <label for="newPassword">Contraseña Nueva</label>
                                <div class="fieldset__newpassword__child">
                                    <span>
                                        <i class="h2 bi bi-key-fill"></i>
                                    </span>
                                    <input id="newPassword" type="password" name="newpassword" 
                                    placeholder="Ingresar contraseña" require>
                                </div>
                            </div>
                            <div class="fieldset__repeatnewpassword fieldset__child">
                                <label for="repeatnewPassword">Repetir Contraseña Nueva</label>
                                <div class="fieldset__repeatnewpassword__child">
                                    <span>
                                        <i class="h2 bi bi-key-fill"></i>
                                    </span>
                                    <input id="repeatnewPassword" type="password" name="repeatnewpassword" 
                                    placeholder="Ingresar contraseña" require>
                                </div>
                            </div>
                            <button type="button" class="button--changepassword" 
                            id="button__submit--changepassword">Guardar Contraseña</button>
                        </fieldset>
                    </form>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                    data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                        INFORMACION
                    </button>
                </h2>
                <div id="flush-collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                    <div class="accordion-body">
                    </div>
                </div>
            </div>
            <!-- <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                    data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                        JAJA salu2
                    </button>
                </h2>
                <div id="flush-collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                    <div class="accordion-body">
                    </div>
                </div>
            </div> -->
        </div>
        <div id="toast--message" class="toast--notification"></div>
    </div>
</main>