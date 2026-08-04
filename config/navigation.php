<?php

return [
    ['label' => 'Datos gráficos', 'route' => 'dashboard', 'permission' => 'dashboard.view', 'icon' => 'icons/speedometer.svg'],
    ['label' => 'Arqueo de caja', 'route' => 'arqueo-caja', 'permission' => 'cash.manage', 'icon' => 'icons/calculator.svg'],
    ['label' => 'Compras', 'icon' => 'icons/truck.svg', 'children' => [
        ['label' => 'Nueva compra', 'route' => 'compra-nueva', 'permission' => 'purchases.create'],
        ['label' => 'Historial de compras', 'route' => 'compras-historial', 'permission' => 'purchases.view'],
        ['label' => 'Pagos de compras', 'route' => 'compras-pagos', 'permission' => 'purchases.payments'],
    ]],
    ['label' => 'Ventas', 'icon' => 'icons/cart.svg', 'children' => [
        ['label' => 'Nueva venta', 'route' => 'ventas-nueva', 'permission' => 'sales.create'],
        ['label' => 'Historial de ventas', 'route' => 'ventas-historial', 'permission' => 'sales.view'],
        ['label' => 'Pagos de ventas', 'route' => 'ventas-pagos', 'permission' => 'sales.payments'],
        ['label' => 'Historial de arqueos', 'route' => 'historial-arqueos', 'permission' => 'cash.manage'],
        ['label' => 'Ventas por cliente', 'route' => 'ventas-clientes', 'permission' => 'sales.view'],
        ['label' => 'Ventas por producto', 'route' => 'ventas-productos', 'permission' => 'sales.view'],
    ]],
    ['label' => 'Almacén', 'icon' => 'icons/basket.svg', 'children' => [
        ['label' => 'Inventario', 'route' => 'inventario', 'permission' => 'inventory.view'],
        ['label' => 'Productos', 'route' => 'productos', 'permission' => 'inventory.products.manage'],
        ['label' => 'Categorías', 'route' => 'categorias', 'permission' => 'inventory.categories.manage'],
        ['label' => 'Ajustes', 'route' => 'ajustes', 'permission' => 'inventory.adjustments.manage'],
        ['label' => 'Presentaciones', 'route' => 'presentaciones', 'permission' => 'inventory.presentations.manage'],
        ['label' => 'Líneas', 'route' => 'lineas', 'permission' => 'inventory.lines.manage'],
        ['label' => 'Lotes', 'route' => 'lotes', 'permission' => 'inventory.lots.manage'],
    ]],
    ['label' => 'Gastos', 'icon' => 'icons/money.svg', 'children' => [
        ['label' => 'Motivos de gasto', 'route' => 'motivos-gasto', 'permission' => 'expenses.reasons.manage'],
        ['label' => 'Registro de gastos', 'route' => 'gastos', 'permission' => 'expenses.manage'],
    ]],
    ['label' => 'Datos maestros', 'icon' => 'icons/storage.svg', 'children' => [
        ['label' => 'Clientes', 'route' => 'clientes', 'permission' => 'data.clients.manage'],
        ['label' => 'Laboratorios', 'route' => 'laboratorios', 'permission' => 'data.laboratories.manage'],
        ['label' => 'Personal', 'route' => 'personal', 'permission' => 'data.personal.manage'],
        ['label' => 'Cargos', 'route' => 'cargos', 'permission' => 'data.positions.manage'],
        ['label' => 'Mi empresa', 'route' => 'mi-empresa', 'permission' => 'data.company.manage'],
    ]],
    ['label' => 'Usuarios', 'icon' => 'icons/people.svg', 'children' => [
        ['label' => 'Grupos de usuarios', 'route' => 'grupos-usuarios', 'permission' => 'users.roles.manage'],
        ['label' => 'Usuarios', 'route' => 'usuarios', 'permission' => 'users.manage'],
        ['label' => 'Permisos', 'route' => 'permisos', 'permission' => 'users.permissions.manage'],
    ]],
    ['label' => 'Reportes', 'route' => 'reportes', 'permission' => 'reports.view', 'icon' => 'icons/chart-line.svg'],
];
