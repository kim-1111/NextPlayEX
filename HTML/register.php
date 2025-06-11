<!DOCTYPE html>
<html lang="en">
 
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Página de Registro</title>
  <link rel="stylesheet" href="../Layout/layout.css">
  <link rel="stylesheet" href="../CSS/auth.css">
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  <link rel="icon" href="../imagenes/logo.png">
</head>
 
<?php session_start(); ?>
 
<body>
  <center>
    <main>
      <section class="loginwrapper" class="auth">
        <section class="register-container">
          <div class="closebutton"><button onclick="cerrarRegister()">X</button></div>
          <h2>Register</h2>
          <form action="../php/controllerpdo.php" method="post">
            <div class="role-selector">
              <div class="role" id="student">
                <img src="../imagenes/user.png" alt="Student">
                <span>USER</span>
              </div>
              <div class="role selected" id="tutor">
                <img src="../imagenes/promoter-icon.png" alt="Tutor">
                <span>PROMOTER</span>
              </div>
            </div>
            <input type="hidden" name="rol" id="rolInput" value="promotor"> <!-- Valor por defecto -->
 
            <script>
              document.querySelectorAll('.role').forEach(role => {
                role.addEventListener('click', () => {
                  document.querySelectorAll('.role').forEach(el => el.classList.remove('selected'));
                  role.classList.add('selected');
                  document.getElementById('rolInput').value =
                    role.id === 'student' ? 'usuario' : 'promotor';
                });
              });
            </script>
            <?php echo $_SESSION['error']; ?>
            <label>
              <div class="input-container">
                <img src="../imagenes/user-icon.png" alt="User Icon">
              </div>
              <input type="text" name="username" required placeholder="Enter your username">
            </label>
            <label>
              <div class="input-container">
                <img src="../imagenes/email-icon.png" alt="Email Icon">
              </div>
              <input type="email" name="email" required placeholder="Enter your email">
 
            </label>
 
            <label>
              <div class="input-container">
                <img src="../imagenes/password-icon.png" alt="Password Icon">
              </div>
              <input type="password" name="password" required placeholder="Enter your password">
            </label>
            <label>
              <div class="input-container">
                <img src="../imagenes/password-icon.png" alt="Password Icon">
              </div>
              <input type="password" name="repeat_password" required placeholder="Repeat password">
            </label>
            <div class="captcha">
              <div class="g-recaptcha" data-sitekey="6LcmtdQqAAAAACkTwbyhF5gil2oJ09cvcvvpPykQ"></div>
            </div>
            <button type="submit" class="register-btn" name="register">Register</button>
          </form>
          <div class="authswitch">
            <p><a onclick="cerrarRegister(), mostrarLogin()">Already registered? Log in!</a>
            </p>
          </div>
        </section>
      </section>
    </main>
  </center>
  <footer>
      <div id="footer"></div>
  </footer>
</body>
<!---->
 
</html>