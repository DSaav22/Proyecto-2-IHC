function correoValido(correo) {
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(String(correo).trim());
}

async function pedir(url, datos) {
  try {
    var opciones = { credentials: "include" };
    if (datos) {
      opciones.method = "POST";
      opciones.headers = { "Content-Type": "application/json" };
      opciones.body = JSON.stringify(datos);
    }
    var respuesta = await fetch(url, opciones);
    var json = {};
    try {
      json = await respuesta.json();
    } catch (e) {}
    return { ok: respuesta.ok, json: json };
  } catch (e) {
    return { ok: false, json: { message: "No se pudo conectar con el servidor. Inténtalo de nuevo." } };
  }
}

function mostrar(id, texto) {
  var el = document.getElementById(id);
  el.textContent = texto || "";
  el.hidden = !texto;
}
