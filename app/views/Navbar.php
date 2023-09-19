<div class="navbar">
    <div class="sidebar">
        <button class="btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar" aria-controls="sidebar">
            <i class="bi bi-arrow-bar-right"></i>
        </button>
    </div>

    <div class="navbar__userMenu">
        <p><?php echo $name?></p>
        <div class="dropdown navbar__userMenu__user">
            <button class="" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle"></i>
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="User">
                    <i class="bi bi-gear-wide-connected"></i>
                    Configuraciones</a></li>
            </ul>
        </div>
        <div id="div__icon--logout" class="navbar__userMenu__logout">
            <a href="Session/logOut">
                <i class="bi bi-door-open-fill"></i>
            </a>
        </div>
    </div>
</div>

