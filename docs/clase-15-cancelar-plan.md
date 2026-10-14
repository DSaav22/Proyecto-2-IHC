# Clase 15: cancelar un plan

Acción de Planazo: encontrar un plan y cancelarlo. El recorrido sale de **Tus planes** en `/mis-planes` y termina en el estado del plan.

Sesión revisada: Usuario Uno. En la lista había dos planes.

- Caso permitido: **Diego de Fiesta el Sabado · 17 de octubre · Confirmado**, con el botón **Cancelar plan**.
- Caso bloqueado: **Cena del viernes · 5 de octubre · Cancelado**. El botón ya no está. Debajo dice: "Ya está cancelado. No se puede confirmar ni cancelar otra vez."

## Tres hallazgos

| #   | Pantalla y evidencia                     | Problema y consecuencia                                                                                                                                                                    | Heurística                                                                                                          | Severidad |
| --- | ---------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------- | --------- |
| 1   | ![[Pasted image 20261013214823.png]]<br> | El estado cambia dentro de la misma frase. No aparece un mensaje de éxito junto a la acción. La persona puede no ver el cambio, pulsar de nuevo o creer que no funcionó.                   | H1 · Visibilidad del estado del sistema. El resultado de cancelar no se informa en el momento ni al lado del botón. | 3 · Mayor |
| 2   | ![[Pasted image 20261013214823.png]]     | El cuadro no muestra el nombre, la fecha ni el estado. Con más de un plan, la persona confirma de memoria y puede cancelar otro. Un plan cancelado no se puede recuperar.                  | H6 · Reconocer antes que recordar. Los datos para decidir no están en el cuadro de confirmación.                    | 2 · Menor |
| 3   | ![[Pasted image 20261013214823.png]]<br> | El botón no se bloquea con un motivo. El texto no dice que solo quien organiza puede cancelarlo, ni qué hacer después. La persona no distingue si el plan no existe o si no tiene permiso. | H5 · Prevención de errores. En una acción importante, el bloqueo tiene que verse y explicarse antes del error.      | 2 · Menor |

## Cuál se mejora

Se elige el **1**. Ocurre en cada cancelación que sí se permite, y la tarea no termina hasta que la persona ve el resultado. El 2 y el 3 dificultan, y aun así se puede seguir.

## Mejora

Junto al plan recién cancelado, mostrar el estado **Cancelado**, un mensaje de éxito con el nombre del plan y qué queda disponible. El mensaje va al lado de ese plan, no en el formulario **Nuevo plan**.

Comprobación: otra persona cancela un plan confirmado y cuenta qué pasó sin recibir ayuda. Si no puede decir el nombre del plan y que quedó cancelado, el problema sigue.
