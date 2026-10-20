# Decisiones de interacción del historial

El historial de Planazo responde qué cambios tuvo el plan y cuándo. Se abre en `/mis-planes`.

## Fitts

![[historial-fitts.png]]

**Historial** está arriba, al lado de **Cerrar sesión**. Tiene el mismo tamaño y no hay que bajar hasta la lista para tocarlo.

Código: `apps/web/paginas/planes.html`, el botón `ver-historial` en la barra.

## Hick

![[historial-hick.png]]

No hay un botón por cada tipo de cambio. Creado, editado, confirmado y cancelado salen en la misma lista, el más reciente primero.

Código: `apps/web/paginas/planes.html`, `cargarCambios()`.

## Carga cognitiva

![[historial-carga.png]]

La fila dice el plan y qué pasó. Debajo están el resultado y cuándo. Con eso se entiende el cambio sin volver a otra pantalla.

Código: `apps/web/paginas/planes.html`, el texto de cada cambio en `cargarCambios()`.
