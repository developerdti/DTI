<main class="main--modify">
    <div class="container permissions">
        <div class="permissions--div">
            <div class="nav nav-pills permissions__tabs" id="v-pills-tab">
                <button class="nav-link active" id="SearchUser-tab" data-bs-toggle="pill" data-bs-target="#SearchUser" type="button">Buscar usuario</button>
                <button class="nav-link" id="EnableJobcodes-tab" data-bs-toggle="pill" data-bs-target="#EnableJobcodes" type="button">Habilitar claves</button>
                <!-- <button class="nav-link" id="ChangeUser-tab" data-bs-toggle="pill" data-bs-target="#ChangeUser" type="button">Cambiar Usuario</button> -->
            </div>
            <div class="tab-content permissions__deployment" id="v-pills-tabContent">
                <div class="tab-pane fade show active" id="SearchUser">
                    <div class="SearchUser">
                        <form id="form__searchUser" name="formSearchUser" class="SearchUser__form">
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
                        </form>

                        <div class="SearchUser__search-tabs" id="div__SearchUsers-tab">
                            <h3>No se encuentran resultados</h3>
                            <!-- <ul class="nav nav-pills mb-3 SearchUser__listPills" id="pills-tab">
                                <li>
                                    <button class="nav-link" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button">Home</button>
                                </li>
                                <li>
                                    <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button">Profile</button>
                                </li>
                            </ul> -->
                        </div>

                        <div class="tab-content" id="pills-tabContent">


                            <!-- <div class="tab-pane fade" id="pills-home" role="tabpanel">
                                b
                            </div>
                            <div class="tab-pane fade" id="pills-profile" role="tabpanel">
                                a
                            </div> -->
                        </div>

                    </div>
                </div>
                <div class="tab-pane fade enable permissions__deployment__enableJobcodes" id="EnableJobcodes">

                </div>
                <!-- <div class="tab-pane fade" id="ChangeUser">
                    jejejeje
                </div> -->
            </div>
        </div>
    </div>
    <div class="toast--notification"></div>
    <?php echo $searchRequest; ?>
</main>