<?php session_start(); 

if(!isset($_SESSION['logged'])){
  $_SESSION['logged'] = false;
}


?>
<nav>
  <a href="../HTML/principal.php"><img src="../imagenes/logo.png" alt="logo-NextPlay" class="logonextplay"
      aria-label="NextPlay Logo"></a>
  <div id="search" class="search-container">
    <form action="search.php" role="search" aria-label="Search events and games">
      <button aria-label="Search" type="submit" class="btn-search">
        <i class="fas fa-search"></i>
      </button>
      <input type="search" list="search-options" name="search-input" id="search-input"
        placeholder="Search events, games..." aria-describedby="search-error" required>
      <datalist id="search-options">
        <option value="Events">
        <option value="Games">
        <option value="News">
        <option value="Forums">
      </datalist>
    </form>
    <span id="search-error" class="error-message" role="alert" style="display: none;">Please enter a valid search
      term.</span>
  </div>
  <div class="nav-links" role="navigation">
    <a href="../HTML/principal.php" class="nav-icon desktop-only" data-tooltip1="Home" aria-label="Home">
      <i class="fas fa-home"></i>
    </a>
    <a href="../HTML/events.php" class="nav-icon desktop-only" data-tooltip1="Events" aria-label="Events">
      <i class="fas fa-calendar-alt"></i>
    </a>
    <a href="../HTML/news.html" class="nav-icon desktop-only" data-tooltip1="News" aria-label="News">
      <i class="fas fa-newspaper"></i>
    </a>
    <a href="../HTML/forums.html" class="nav-icon desktop-only" data-tooltip1="Forums" aria-label="Forums">
      <i class="fas fa-users"></i>
    </a>
    <a href="../HTML/about.html" class="nav-icon desktop-only" data-tooltip1="About Us" aria-label="About Us">
      <i class="fas fa-info-circle"></i>
    </a>
    <a href="../HTML/faq.html" class="nav-icon desktop-only" data-tooltip1="FAQ" aria-label="FAQ">
      <i class="fas fa-question-circle"></i>
    </a>
    <?php if ($_SESSION['logged'] === true):
      $username = $_SESSION['user']['nombre'];
      $imagePath = "../users/profileimg/" . $username . ".jpg";
      if (file_exists($imagePath)) {
        $avatarSrc = $imagePath;
      } else {
        $avatarSrc = "http://ssl.gstatic.com/accounts/ui/avatar_2x.png";
      }

      // Definir la URL del perfil según si es promotor o no
      $profileUrl = ($_SESSION['user']['promotor'] === true) ? "../HTML/profilepromotor.php" : "../HTML/profile.php";
      ?>
      <div id="user-profile" class="desktop-only">
        <a href="<?= $profileUrl ?>" aria-label="User Profile">
          <img src="<?= $avatarSrc ?>" alt="Profile Picture" class="profile-pic">
        </a>
      </div>
    <?php else: ?>
      <button id="login" class="nav-icon desktop-only" onclick="mostrarLogin()" data-tooltip1="Login" aria-label="Login">
        <i class="fas fa-sign-in-alt"></i>
      </button>
    <?php endif; ?>
  </div>
  <button id="menu" aria-label="Toggle Menu" aria-expanded="false">
    <i class="fas fa-bars"></i>
  </button>
  <div id="menu-dropdown" role="menu" aria-hidden="true">
    <a href="../HTML/principal.php" class="nav-icon mobile-only" role="menuitem" aria-label="Home">
      <i class="fas fa-home"></i> Home
    </a>
    <a href="../HTML/events.php" class="nav-icon mobile-only" role="menuitem" aria-label="Events">
      <i class="fas fa-calendar-alt"></i> Events
    </a>
    <a href="../HTML/news.html" class="nav-icon mobile-only" role="menuitem" aria-label="News">
      <i class="fas fa-newspaper"></i> News
    </a>
    <a href="../HTML/forums.html" class="nav-icon mobile-only" role="menuitem" aria-label="Forums">
      <i class="fas fa-users"></i> Forums
    </a>
    <a href="../HTML/about.html" class="nav-icon mobile-only" role="menuitem" aria-label="About Us">
      <i class="fas fa-info-circle"></i> About Us
    </a>
    <a href="../HTML/faq.html" class="nav-icon mobile-only" role="menuitem" aria-label="FAQ">
      <i class="fas fa-question-circle"></i> FAQ
    </a>
    <?php if ($_SESSION['logged'] === true && isset($_SESSION['user'])):
      // Definir URL perfil según promotor
      $profileUrl = (isset($_SESSION['user']['promotor']) && $_SESSION['user']['promotor'] === true) ? "../HTML/profilepromotor.php" : "../HTML/profile.php";
      ?>
      <a href="<?= $profileUrl ?>" class="nav-icon mobile-only" role="menuitem" aria-label="Profile">
        <i class="fas fa-user"></i> Profile
      </a>
    <?php else: ?>
      <button class="nav-icon mobile-only" onclick="mostrarLogin()" role="menuitem" aria-label="Login">
        <i class="fas fa-sign-in-alt"></i> Login
      </button>
    <?php endif; ?>
  </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  document.getElementById('menu').addEventListener('click', function (e) {
    e.stopPropagation();
    const menuDropdown = document.getElementById('menu-dropdown');
    const isActive = menuDropdown.classList.toggle('active');
    this.setAttribute('aria-expanded', isActive);
    menuDropdown.setAttribute('aria-hidden', !isActive);
  });

  document.addEventListener('click', function (e) {
    const menuDropdown = document.getElementById('menu-dropdown');
    const menuButton = document.getElementById('menu');
    if (!menuDropdown.contains(e.target) && !menuButton.contains(e.target)) {
      menuDropdown.classList.remove('active');
      menuButton.setAttribute('aria-expanded', 'false');
      menuDropdown.setAttribute('aria-hidden', 'true');
    }
  });

  function gotoprincipal() {
    window.location.href = "../HTML/principal.php";
  }

  document.querySelector('#search form').addEventListener('submit', function (e) {
    const input = document.getElementById('search-input');
    const error = document.getElementById('search-error');
    if (!input.value.trim()) {
      e.preventDefault();
      error.style.display = 'block';
      input.focus();
    } else {
      error.style.display = 'none';
    }
  });
</script>