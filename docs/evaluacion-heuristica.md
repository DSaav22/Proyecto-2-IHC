# Evaluación heurística H1–H10

Funcionalidad de Planazo en `/mis-planes`: crear un plan, editarlo, confirmarlo, cancelarlo, buscar por nombre y filtrar por estado. Entrar o crear cuenta está en `/login` y `/register`.

Sesión: Usuario Uno (`usuario1@planazo.com`). En la lista están **Diego de Fiesta el Sabado** (confirmado) y **Cena del viernes** (cancelado). Otra persona: Usuario Dos (`usuario2@planazo.com`), clave `Planazo123!`.

Las capturas van en `docs/evidencias/`. Ninguna falla está corregida.

| Pantalla y evidencia                 | Heurística                              | Estado | Severidad | Despues del arreglo                  |
| ------------------------------------ | --------------------------------------- | ------ | --------- | ------------------------------------ |
| ![[Pasted image 20261015151038.png]] | H1 · Visibilidad del estado del sistema | Falla  | 3 · Mayor | ![[Pasted image 20261015155529.png]] |
| ![[Pasted image 20261015151125.png]] | H2 · Relación con el mundo real         | Cumple | —         |                                      |
| ![[Pasted image 20261015151151.png]] | H3 · Control y libertad                 | Cumple | —         |                                      |
| ![[Pasted image 20261015151228.png]] | H4 · Consistencia y estándares          | Cumple | —         |                                      |
| ![[Pasted image 20261015151310.png]] | H5 · Prevención de errores              | Falla  | 2 · Menor |                                      |
| ![[Pasted image 20261015151346.png]] | H6 · Reconocer antes que recordar       | Falla  | 2 · Menor |                                      |
| ![[Pasted image 20261015151526.png]] | H7 · Flexibilidad y eficiencia          | Cumple | —         |                                      |
| ![[Pasted image 20261015151548.png]] | H8 · Diseño estético y minimalista      | Cumple | —         |                                      |
| ![[Pasted image 20261015151736.png]] | H9 · Reconocer y recuperarse de errores | Falla  | 2 · Menor |                                      |
| ![[Pasted image 20261015151626.png]] | H10 · Ayuda y documentación             | Cumple | —         |                                      |

## H1 · Visibilidad del estado del sistema

**Falla. Severidad 3.**

Al crear, guardar, confirmar o cancelar, la página solo vuelve a pintar la lista. El resultado queda en la misma frase, por ejemplo "Cena del viernes · 5 de octubre · Cancelado". No hay un mensaje de éxito junto a esa fila.

La persona puede no ver el cambio o creer que no funcionó.

Código: `apps/web/paginas/planes.html`, funciones `cancelar`, `confirmar` y el envío del formulario. Si sale bien, llaman a `cargarPlanes()`.

Captura: `docs/evidencias/h1-despues-de-cancelar.png`. Fila de un plan recién cancelado, con **Cancelado** en la frase y sin mensaje de éxito al lado.

## H2 · Relación con el mundo real

**Cumple.**

Los textos son de uso común: **Borrador**, **Confirmado**, **Cancelado**, **Confirmar plan**, **Cancelar plan**, **Editar**, **Buscar** y **Estado**.

Código: `apps/web/paginas/planes.html`, `mostrarLista()` y el formulario de arriba.

Captura: `docs/evidencias/h2-lenguaje-del-plan.png`. La fila de **Diego de Fiesta el Sabado**, con **Confirmado**, **Editar** y **Cancelar plan**.

## H3 · Control y libertad

**Cumple.**

**Cancelar plan** abre "¿Cancelar este plan?". Si se responde que no, el plan sigue igual. En una edición, **Dejar de editar** sale sin guardar.

Código: `apps/web/paginas/planes.html`, `window.confirm` en `cancelar` y el botón `btn-dejar`.

Captura: `docs/evidencias/h3-cuadro-para-salir.png`. El cuadro abierto, con la pregunta y los dos botones. No lo aceptes.

## H4 · Consistencia y estándares

**Cumple.**

**Cancelar plan** y **Confirmar plan** se escriben igual en borrador y en confirmado. Los estados son siempre **Borrador**, **Confirmado** y **Cancelado**.

Código: `apps/web/paginas/planes.html`, los botones dentro de `mostrarLista()`.

Captura: `docs/evidencias/h4-mismo-texto-cancelar.png`. La fila confirmada, con **Editar** y **Cancelar plan**. Si hay un borrador, mejor esa fila: salen **Confirmar plan**, **Editar** y **Cancelar plan**.

## H5 · Prevención de errores

**Falla. Severidad 2.**

Un plan ya cancelado no muestra **Cancelar plan** y dice "Ya está cancelado. No se puede confirmar ni cancelar otra vez."

Quien no organiza el plan no ve ese bloqueo. Usuario Dos no ve los planes de Usuario Uno ni un motivo. Si la petición llega, la respuesta es "No encontré ese plan." o "No puedes cancelar ese plan."

Código: `apps/backend/app/Http/Controllers/PlanController.php`, método `cancelar`. La lista solo trae los planes de la sesión.

Captura: `docs/evidencias/h5-otra-persona.png`. Entra con `usuario2@planazo.com` y toma **Tus planes**. No están los planes de Usuario Uno ni un texto del bloqueo.

## H6 · Reconocer antes que recordar

**Falla. Severidad 2.**

El cuadro solo dice "¿Cancelar este plan?". No muestra el nombre, la fecha ni el estado. Con más de un plan, la persona confirma de memoria.

**Buscar** y **Estado** sí dejan el dato a la vista. La falla es el cuadro.

Código: `apps/web/paginas/planes.html`, el texto de `window.confirm` en `cancelar`.

Captura: `docs/evidencias/h6-cuadro-sin-nombre.png`. El mismo cuadro de H3. Tiene que leerse la pregunta y verse que no nombra el plan.

## H7 · Flexibilidad y eficiencia

**Cumple.**

El plan, **Editar** y **Cancelar plan** están en la misma fila. **Buscar** filtra por nombre al escribir. **Estado** deja solo borrador, confirmado o cancelado, sin otra pantalla.

Código: `apps/web/paginas/planes.html`, `mostrarLista()` y los eventos de `busca` y `filtro`.

Captura: `docs/evidencias/h7-boton-en-la-lista.png`. **Tus planes** con algo escrito en **Buscar** (por ejemplo "diego") y una sola fila debajo.

## H8 · Diseño estético y minimalista

**Cumple.**

En la fila están el nombre, la fecha, el estado y las acciones de ese plan. **Buscar** y **Estado** sirven para encontrar, no son adorno.

Código: `apps/web/paginas/planes.html`, el bloque de cada plan en `mostrarLista()`.

Captura: `docs/evidencias/h8-fila-del-plan.png`. La fila de **Diego de Fiesta el Sabado**, del texto hasta **Cancelar plan**.

## H9 · Reconocer y recuperarse de errores

**Falla. Severidad 2.**

Si cancelar, confirmar o guardar falla, el texto rojo sale en el formulario **Nuevo plan** (`err-plan`), lejos de la fila. "No encontré ese plan." y "No puedes cancelar ese plan." no dicen qué hacer después.

Código: `apps/web/paginas/planes.html`, `mostrar("err-plan", ...)` en `cancelar`, `confirmar` y el envío del formulario. Los textos están en `PlanController.php`.

Captura: `docs/evidencias/h9-error-lejos-del-plan.png`. Deja un plan confirmado en una pestaña, sin recargar. En otra pestaña cancélalo. Vuelve a la primera, pulsa **Cancelar plan** y acepta. El rojo queda arriba, junto a **Crear plan**. Toma el formulario y la lista en la misma captura.

## H10 · Ayuda y documentación

**Cumple.**

Junto a un plan cancelado hay una frase corta: "Ya está cancelado. No se puede confirmar ni cancelar otra vez."

Código: `apps/web/paginas/planes.html`, la nota cuando `plan.estado` es `cancelado`.

Captura: `docs/evidencias/h10-nota-del-cancelado.png`. La fila de **Cena del viernes**, con esa nota.

## Cierre

H2, H3, H4, H7, H8 y H10 quedan en **cumple** al guardar la captura.

H1, H5, H6 y H9 siguen en **falla**. Para entregar, cada una pasa a **corregida** con un sub-issue, el cambio en el código y la captura de antes y después.
