# Changelog
Todos los cambios relevantes serán documentados en este archivo.

## Sin aplicar
### Agregados
- Control Panel: Listado de anuncios creados.
- Control Panel: Listado de histórico de operaciones.
- Control Panel: Editar un anucio creado.
- Control Panel: Crear un nuevo anuncio.
- API: Endpoint para la edición en caliente del anuncio.

## 24-09-2018
### Agregados
- Advertisements: Se ha aplicado el diseño creado a la vista principal.

## 22-09-2018
### Agregados
- Search: Por limitaciones de LocalBitcoins, los resultados de búsquedas de anuncios
por: País, Banco, Monto y tipo de operación, no pueden actualizarce en tiempo real, por lo tanto
la busqueda de anuncios carga los resultados en una vista independiente, distinta a la vista principal.
- Advertisements: El listado de los anuncios se actualiza automáticamente con intérvalos de 10seg, agilizando
el análisis del posicionamiento de los anuncios. Esto estará relacionado con la edición en caliente
de los anuncios publicados. (Siguiente Versión).
- Advertisements: Se pueden visualizar dos listados de anuncios: para Comprar y para Vender, con botones para
intercambiar entre ambas ubicados en la cabecera de los listados.
- Advertisements: Inicialmente muestran los anuncios para Venezuela pero se puede cambiar para mostrar rápidamente 
anuncios de otros países. Estos anuncios se siguen actualizando automáticamente cada 10seg.
- Advertisements: Se culminó el diseño de la vista principal del sitio inluyendo:
	- Notificaciones.
	- Barra lateral de edición rápida de anuncio.
	- Diseño general de todo lo visible actualmente en la vista principal.
	- URL: https://app.avocode.com/view/9a6186c4473641718f834a8b32c583d8/_/preview

### Cambios
- Se actualizó la función encargada de calcular los volúmenes: se corrigieron casos donde el volúmen asumía valores 
errados y mostraba información falsa.
- El cálculo del volumen ahora no se basa en el máximo de BTC a vender de un anuncio sino en el máximo de BTC disponible
por anuncio.
- Aunque los anuncios en Local no muestran directamente la cantidad de BTC en venta, esta se calcula dividiendo el valor
máximo de compra entre el precio del BTC del anuncio. Ejemplo: Si el precio es 10VES y lo máximo para vender es 50VES,
50/10 = 5, se asume entonces que están en venta 5BTC para ese anuncio y se acumula en el cálculo del volumen.
- Se agregaron indicadores de Loading en algunas solicitudes del sitio web.