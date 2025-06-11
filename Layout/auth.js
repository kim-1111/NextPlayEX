function mostrarLogin() {
  document.getElementById("loginwindow").style.display = "flex";
  document.getElementById("main-auth").classList.add("blur-effect");
}

function cerrarLogin() {
  document.getElementById("loginwindow").style.display = "none";
  document.getElementById("main-auth").classList.remove("blur-effect");
}

function mostrarRegister() {
  document.getElementById("registerwindow").style.display = "flex";
  document.getElementById("main-auth").classList.add("blur-effect");
}

function cerrarRegister() {
  document.getElementById("registerwindow").style.display = "none";
  document.getElementById("main-auth").classList.remove("blur-effect");
}