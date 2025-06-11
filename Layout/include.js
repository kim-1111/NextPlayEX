/*

Script que permite insertar codigos HTML solo poniendo un div. 
Para usarlo habrás de poner las siguientes lineas en el header de cada HTML para poder incluir el layout.

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="include.js"></script>
    <link rel="stylesheet" href="layout.css">
    
Cuando se haya importado, lo unico que faltará será crear un div con una id específica.#footer

<div id="navbar"></div> Para insertar el navbar.
<div id="footer"></div> Para insertar el footer.    
<div id="login"></div> Para insertar la ventana flotante del login.    
<div id="register"></div> Para insertar la ventana flotante del register. 

Este script detectará automáticamente divs con estas ids específicas e insertará codigo HTML dentro de ellas.

*/

$(document).ready(function () {
  function loadComponent(id, url, componentName) {
    $(`#${id}`).load(url, function (response, status, xhr) {
      if (status === "error") {
        console.error(`Error al cargar ${componentName}:`, xhr.status, xhr.statusText);
        $(`#${id}`).html(`<p class="error-message">Failed to load ${componentName}. Please try again.</p>`);
      } else {
        console.log(`${componentName} cargado correctamente.`);
      }
    });
  }

  loadComponent("navbar", "../Layout/navbar.php", "navbar");
  loadComponent("footer", "../Layout/footer.html", "footer");
  loadComponent("loginwindow", "../Layout/authwindows/login.html", "login");
  loadComponent("registerwindow", "../Layout/authwindows/register.html", "register");
});