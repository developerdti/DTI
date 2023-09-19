<main class="main--modify">
    <div class="container permissions">
        <div class="permissions--div">
            <div class="nav nav-pills permissions__tabs" id="v-pills-tab">
                <button class="nav-link active" id="SearchUser-tab" data-bs-toggle="pill" data-bs-target="#SearchUser" type="button">Buscar usuario</button>
                <button class="nav-link" id="EnableJobcodes-tab" data-bs-toggle="pill" data-bs-target="#EnableJobcodes" type="button">Habilitar claves</button>
                <button class="nav-link" id="ChangeUser-tab" data-bs-toggle="pill" data-bs-target="#ChangeUser" type="button">Cambiar Usuario</button>
            </div>
            <div class="tab-content permissions__deployment" id="v-pills-tabContent">
                <div class="tab-pane fade show active" id="SearchUser">
                    <div class="SearchUser">
                        <!-- <form id="form__searchUser" name="formSearchUser" class="form-searchUser">
                            <fieldset class="form--fieldset">
                                <div class="form--div">
                                    <label></label>
                                    <div>
                                        <span>
                                            <i class="bi bi-search"></i>
                                        </span>
                                        <input id="input--SearchUser" type="text" name="searchUsers" placeholder="Buscar usuario">
                                    </div>
                                </div>
                            </fieldset>
                        </form> -->
                        <ul class="nav nav-pills mb-3" id="pills-tab">
                            <li class="">
                                <button class="nav-link" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button">Home</button>
                            </li>
                            <li class="">
                                <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button">Profile</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <!-- <div class="tab-pane fade show active" id="pills-home" role="tabpanel">aa
                                <form id="form__UserPermissions" name="form--UserPermissions" class="form--UserPermissions">
                                    <fieldset class="form--UserPermissions__fieldset">
                                        <div class="div__UserPermissions--username">
                                            <label for="usernameID">USUARIO</label>
                                            <div class="div__UserPermissions--username__child">
                                                <span>
                                                    <i class="h2 bi bi-person"></i>
                                                </span>
                                                <input id="usernameID" type="text" name="username" maxlength="5" placeholder="Ingresar usuario" require>
                                            </div>
                                        </div>
                                        <div class="div__UserPermissions--password">
                                            <label for="passwordID">CONTRASEÑA</label>
                                            <div class="div__UserPermissions--password__child">
                                                <span>
                                                    <i class="h2 bi bi-key-fill"></i>
                                                </span>
                                                <input id="passwordID" type="password" name="password" placeholder="Ingresar contraseña" require>
                                            </div>
                                        </div>
                                        <button type="button" class="button--UserPermissions" id="button__submit--UserPermissions">UserPermissions</button>
                                    </fieldset>
                                </form>
                            </div> -->
                            <div class="tab-pane fade" id="pills-home" role="tabpanel">
                                b
                            </div>
                            <div class="tab-pane fade" id="pills-profile" role="tabpanel">
                                a
                            </div>
                        </div>

                    </div>
                </div>
                <div class="tab-pane fade enable permissions__deployment__enableJobcodes" id="EnableJobcodes">

                </div>
                <div class="tab-pane fade" id="ChangeUser">
                    jejejeje
                </div>
            </div>
        </div>
    </div>
    <div class="toast--notification"></div>
    <?php echo $searchRequest; ?>
</main>