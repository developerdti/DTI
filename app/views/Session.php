        <main>
            <div class="div--login">
                <div class="div--login__child">
                    <div class="login--container login--container__left">
                        <img class="div--logo__sertec" src="<?php echo IMAGE_PATH ?>/LOGO_SERTEC.png" alt="Logo sertec">
                        <img class="logo--sertec" src="<?php echo IMAGE_PATH ?>/benjamin_franklin_w.svg" alt="Logo sertec">
                    </div>
                    <div class="login--container login--container__right">
                        <div class="div--change__opctions">
                            <div class="div--change__opctions__child div--buttons">
                                <button id="button--login" type="button" class="div--buttons__login ">
                                    <i class="bi bi-arrow-bar-left"></i>LogIn
                                </button>
                                <button id="button--signUp" type="button" class="div--buttons__signin ">
                                    <i class="bi bi-arrow-bar-right"></i>SignUp
                                </button>
                            </div>
                        </div>
                        <form id="form__login" name="form--login" class="form--login">
                            <fieldset class="form--login__fieldset">
                                <div class="div__login--username">
                                    <label for="usernameID">USUARIO</label>
                                    <div class="div__login--username__child">
                                        <span>
                                            <i class="h2 bi bi-person"></i>
                                        </span>
                                        <input id="usernameID" type="text" name="username" maxlength="5" placeholder="Ingresar usuario" require>
                                    </div>
                                </div>
                                <div class="div__login--password">
                                    <label for="passwordID">CONTRASEÑA</label>
                                    <div class="div__login--password__child">
                                        <span>
                                            <i class="h2 bi bi-key-fill"></i>
                                        </span>
                                        <input id="passwordID" type="password" name="password" placeholder="Ingresar contraseña" require>
                                    </div>
                                </div>
                                <button type="button" class="button--login" id="button__submit--login">LOGIN</button>
                            </fieldset>
                        </form>
                        <form id="form__signUp" name="form--SignUp" class="form--signUp visibility">
                            <fieldset class="signUp--fieldset">
                                <div class="signUp--fieldset__firstsection">
                                    <div class="form--signUp__fieldset__firstname">
                                        <label for="firstname">Primer Nombre</label>
                                        <div>
                                            <span>

                                            </span>
                                            <input id="firstname" type="text" name="firstName" maxlength="25" placeholder="Primer nombre" require>
                                        </div>
                                    </div>
                                    <div class="form--signUp__fieldset__secondname">
                                        <label for="secondname">Segundo Nombre</label>
                                        <div>
                                            <span>

                                            </span>
                                            <input id="secondname" type="text" name="secondName" placeholder="Segundo nombre" maxlength="25">
                                        </div>
                                    </div>
                                </div>
                                <div class="form--signUp__fieldset__lastName">
                                    <label for="lastName">Apellidos</label>
                                    <div>
                                        <span>

                                        </span>
                                        <input id="lastName" type="text" name="lastName" placeholder="Insertar apellidos" maxlength="50" require>
                                    </div>
                                </div>
                                <div class="form--signUp__fieldset__signUpPassWord">
                                    <label for="signUpPassWord">Contraseña</label>
                                    <div>
                                        <span>

                                        </span>
                                        <input id="signUpPassWord" type="password" name="signUpPassWord" placeholder="Insertar contraseña" require>
                                    </div>
                                </div>
                                <div class="form--signUp__fieldset__jobcode">
                                    <label for="jobcode">Claves de trabajador</label>
                                    <div>
                                        <span>

                                        </span>
                                        <input id="jobcode" type="text" name="jobCode" placeholder="Insertar claves" maxlength="5" require>
                                    </div>
                                </div>
                                <button type="button" class="button--signUp" id="button__submit--signup">Enviar solicitud</button>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
            <div id="toast--message" class="toast--notification"></div>
        </main>