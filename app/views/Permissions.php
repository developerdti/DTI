<main class="main--modify">
    <div class="container permissions">
        <div class="permissions--div">
            <div class="nav nav-pills permissions__tabs" id="v-pills-tab">
                <button class="nav-link active" id="SearchUser-tab" data-bs-toggle="pill" data-bs-target="#SearchUser" 
                type="button">Buscar usuario</button>
                <button class="nav-link" id="EnableJobcodes-tab" data-bs-toggle="pill" data-bs-target="#EnableJobcodes" 
                type="button">Habilitar claves</button>
            </div>
            <div class="tab-content permissions__deployment" id="v-pills-tabContent">

                <div class="tab-pane fade show active" id="SearchUser">
                    <div class="SearchUser">

                        <form id="form__searchUser" name="formSearchUser" class="SearchUser__form">
                            <fieldset>
                                <div class="SearchUser__form-div">
                                    <label for="input--SearchUser">Buscar usuarios</label>
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
                        </div>
                    </div>
                    <div class="tab-content SearchUser__userResult" id="SearchUser__userResult">

                    </div>
                </div>

                <div class="tab-pane fade enable permissions__deployment__enableJobcodes" id="EnableJobcodes">
                    <span>Solicitudes de usuarios</span>
                    <div class="enableJobcodes">
                        <div class="enableJobcodes__scrollTabs">
                            <ul class="nav nav-pills enableJobcodes__tab-list" id="enableJobcodes-tabList">
                            </ul>
                        </div>
                    </div>

                    <div id="enableJobcodes-pill">

                    </div>

                </div>
            </div>
        </div>
        <div class="toast--notification"></div>

        <div class="modal fade" id="modifyPermissions" tabindex="-1" aria-labelledby="modifyPermissionsLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content modifyPermissions--modal">
                    <div class="modal-header modifyPermissions--modalHeader" id="modifyPermissions__modalHeader">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body modifyPermissions__modalBody" id="modifyPermissions__modalBody">
                        <form id="form__modifyPermissions" name="form--modifyPermissions" class="modifyPermissions__form">
                            <fieldset class="modifyPermissions__fieldset">
                                <div class="modifyPermissions__selectProfile">
                                    <label for="modifyPermissions-selectProfile">Perfil</label>
                                    <select class="form-select modifyPermissions__selectProfile" 
                                    function="templateSelectClient" id="modifyPermissions-selectProfile" name="profile">

                                    </select>
                                </div>
                                <div class="modifyPermissions__selectClient" id="modifyPermissions--client">

                                </div>
                                <div class="modifyPermissions__selectManager" id="modifyPermissions--manager">

                                </div>
                                <div class="modifyPermissions__buttonsDiv" id="fieldsetmodifyPermissions--buttonsDiv">
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php echo $searchRequest; ?>
</main>