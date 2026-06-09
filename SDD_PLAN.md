# Plan de Desarrollo Guiado por Especificaciones (Spec Driven Development): Mejora del Historial de Producto

Este documento detalla el plan de desarrollo para mejorar el componente de Historial de Producto (`frmHistorialProducto.vue`) mostrando el nombre del cliente en la pestaña "Movimiento Venta Producto" y el nombre del cliente o proveedor en la pestaña "Movimiento General Producto" según motivos de ajuste específicos.

---

## 1. Objetivos

- **Pestaña 1: Movimiento Venta Producto**
  - Mostrar el nombre del cliente (`cliente`) asociado a cada venta de producto.
  - Modificar el endpoint backend (`/cantidadProductoFecha` manejado por `VentaController1@cantidadProductoFecha`) para unir la tabla `cliente`, seleccionar el nombre del cliente y realizar la agrupación correspondiente de forma correcta.
  - Actualizar la tabla frontend para incluir la columna **Cliente** y ajustar las claves del bucle (`:key`) para prevenir elementos duplicados.

- **Pestaña 2: Movimiento General Producto**
  - Mostrar el nombre del cliente cuando el motivo de ajuste (`id_motivo_ajuste`) sea `7` (Ajuste por venta / salida cliente). Se debe igualar `id_transaccion` del ajuste con `venta.id` y de ahí obtener el cliente desde la tabla `cliente`.
  - Mostrar el nombre del proveedor cuando el motivo de ajuste (`id_motivo_ajuste`) sea `6` (Ajuste por compra / entrada proveedor). Se debe igualar `id_transaccion` del ajuste con `compra.id` y de ahí obtener el proveedor desde la tabla `proveedor`.
  - Modificar el endpoint backend (`/ajuste/producto` manejado por `AjusteController@indexProducto`) para realizar `leftJoin` condicionales y usar una sentencia `CASE` para mapear el nombre correspondiente.
  - Actualizar la tabla frontend para incluir la columna **Cliente/Proveedor** y mostrar el nombre obtenido.

---

## 2. Archivos Afectados

1. **Controladores Backend:**
   - [VentaController1.php](file:///C:/laragon/www/farmacia-suarez/app/Http/Controllers/tiendaPrimera/VentaController1.php)
   - [AjusteController.php](file:///C:/laragon/www/farmacia-suarez/app/Http/Controllers/AjusteController.php)
2. **Componente Frontend:**
   - [frmHistorialProducto.vue](file:///C:/laragon/www/farmacia-suarez/resources/js/components/frmHistorialProducto.vue)

---

## 3. Tareas Técnicas Detalladas

### Tarea 3.1: Modificar `VentaController1.php`
- Localizar el método `cantidadProductoFecha(Request $request)`.
- Agregar `leftJoin('cliente', 'venta.id_cliente', '=', 'cliente.id')` al constructor de la consulta (`$query`).
- Seleccionar `'cliente.nombre as cliente'` en los campos del select.
- Cambiar la agrupación `groupBy('detalle_venta.id_lote')` por `groupBy('detalle_venta.id_lote', 'detalle_venta.id_venta')` (o `venta.id`) para asegurar que las ventas se listen por separado por cliente/venta y que los nombres de los clientes no se colapsen.

### Tarea 3.2: Modificar `AjusteController.php`
- Localizar el método `indexProducto(Request $request)`.
- Existen múltiples rutas de ejecución basadas en `$id_proveedor` y `$buscar`. Actualizar todas las consultas SQL de estas rutas:
  - Agregar `leftJoin('venta', function($join) { $join->on('ajuste.id_transaccion', '=', 'venta.id')->where('ajuste.id_motivo_ajuste', '=', 7); })`
  - Agregar `leftJoin('cliente', 'venta.id_cliente', '=', 'cliente.id')`
  - Agregar `leftJoin('compra', function($join) { $join->on('ajuste.id_transaccion', '=', 'compra.id')->where('ajuste.id_motivo_ajuste', '=', 6); })`
  - Agregar `leftJoin('proveedor as prov_compra', 'compra.id_proveedor', '=', 'prov_compra.id')`
  - En la selección de campos (select), añadir:
    `DB::raw("CASE WHEN ajuste.id_motivo_ajuste = 7 THEN cliente.nombre WHEN ajuste.id_motivo_ajuste = 6 THEN prov_compra.nombre ELSE NULL END as cliente_proveedor")`

### Tarea 3.3: Modificar `frmHistorialProducto.vue`
- **Primera Pestaña (Movimiento Venta Producto):**
  - Añadir `<th class="text-white">Cliente</th>` en la cabecera de la tabla.
  - Añadir `<td>{{ venta.cliente }}</td>` en la fila de la tabla.
  - Cambiar la clave de la fila `:key="venta.id_lote"` a `:key="venta.id_lote + '-' + venta.id_venta"` para garantizar que sea única.
- **Segunda Pestaña (Movimiento General Producto):**
  - Añadir `<th scope="col" class="text-white">Cliente/Proveedor</th>` después de `Cod. Transacción` en la cabecera de la tabla.
  - Añadir `<td v-text="producto.cliente_proveedor"></td>` después de la celda de `id_transaccion`.

---

## 4. Verificación y Compilación
- Compilar los archivos del frontend usando `npm run dev` para asegurar que los cambios en el componente Vue se apliquen.
- Probar las pestañas en la interfaz de usuario de la aplicación para validar que la información del cliente y del proveedor se despliegue correctamente en ambas listas.
