-- Script de prueba: registra un lote con stock=100 para todos los productos
-- que aun no tengan ningun lote, con fecha de vencimiento y codigo de lote
-- aleatorios. Es idempotente: si lo corres de nuevo, solo afecta a los
-- productos que sigan sin lote (no duplica).

INSERT INTO lote (id_producto, cantidad, fecha_vecimiento, lote, estado)
SELECT
    ta.id,
    100,
    DATE_ADD(CURDATE(), INTERVAL FLOOR(RAND() * 730) DAY),
    CONCAT('LOTE-', LPAD(FLOOR(RAND() * 99999), 5, '0')),
    1
FROM tienda_articulo ta
LEFT JOIN lote l ON l.id_producto = ta.id
WHERE l.id IS NULL;

-- Recalcula el stock por producto sumando sus lotes activos
-- (mismo criterio que usa el procedimiento almacenado `stock`).
UPDATE tienda_articulo ta
SET ta.stock = (
    SELECT COALESCE(SUM(l.cantidad), 0)
    FROM lote l
    WHERE l.id_producto = ta.id AND l.estado != 0
);
