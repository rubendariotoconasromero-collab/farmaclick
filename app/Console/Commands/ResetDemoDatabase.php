<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class ResetDemoDatabase extends Command
{
    /**
     * El nombre y las opciones del comando.
     *
     * @var string
     */
    protected $signature = 'demo:reset
        {--force : Ejecuta sin pedir confirmación interactiva (úsalo con cuidado)}
        {--skip-backup : No genera el respaldo automático antes de borrar (no recomendado)}
        {--admin-name=administrador : Nombre de la cuenta administradora que queda activa}
        {--admin-email=admin@click.com : Correo de la cuenta administradora}
        {--admin-password=adm : Contraseña de la cuenta administradora}';

    /**
     * La descripción del comando.
     *
     * @var string
     */
    protected $description = 'Vacía los datos operativos de la base de datos y la deja lista para un negocio nuevo, conservando el catálogo de productos y los catálogos del sistema.';

    /**
     * Tablas que se vacían por completo: datos operativos/históricos del negocio anterior.
     */
    private const TRUNCATE_TABLES = [
        'ajuste', 'animal', 'antiparasitario', 'arqueo_caja', 'auxiliar', 'bitacora',
        'c_x_cobrar', 'c_x_pagar', 'cliente', 'compra', 'control', 'control_vacuna',
        'detalle_antiparasitario', 'detalle_compra', 'detalle_control_vacuna',
        'detalle_historia', 'detalle_orden_servicio', 'detalle_paquete', 'detalle_traspasos',
        'detalle_vacuna', 'detalle_venta', 'detalle_venta_paquete', 'gasto',
        'historial_clinico', 'lote', 'motivo_gasto', 'orden_servicio', 'paciente', 'pago',
        'pago_compra', 'paquetes', 'traspasos', 'venta',
        'cotizacion', 'detalle_cotizacion',
        // Sistema de permisos legacy (tablas huérfanas de antes de la migración a RBAC nativo).
        // Se listan con hasTable() para no fallar si ya fueron eliminadas por una migración.
        'permiso', 'usuario_permisos', 'grupo_permiso',
    ];

    public function handle()
    {
        $this->warn('Esto va a BORRAR todos los datos operativos de la base de datos actual');
        $this->warn('(ventas, compras, clientes, personal, cajas, lotes, etc.) y no se puede deshacer.');
        $this->line('Se conservan: catálogo de productos (articulo/categoria/marca/proveedor/unidad_medida),');
        $this->line('catálogos del sistema (tipo_pago/forma_pago/motivo_ajuste/grupo/cargo/permisos/roles), y');
        $this->line('la cuenta administradora (se recrean sus credenciales).');
        $this->newLine();

        if (!$this->option('force')) {
            $confirmed = $this->ask('Escribe RESET en mayúsculas para continuar') === 'RESET';
            if (!$confirmed) {
                $this->error('Cancelado. No se modificó nada.');
                return 1;
            }
        }

        if (!$this->option('skip-backup')) {
            if (!$this->backupDatabase()) {
                $this->error('No se pudo generar el respaldo. Abortando por seguridad (usa --skip-backup para forzar sin respaldo).');
                return 1;
            }
        }

        // Nota: TRUNCATE hace COMMIT implícito en MySQL/InnoDB, por lo que esta operación
        // no puede envolverse en una transacción real. La red de seguridad es el respaldo
        // previo (mysqldump), no un rollback — si algo falla a mitad de camino, restaura
        // desde storage/backups/ en vez de reintentar a ciegas.
        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            $this->info('Vaciando tablas operativas…');
            $bar = $this->output->createProgressBar(count(self::TRUNCATE_TABLES));
            foreach (self::TRUNCATE_TABLES as $table) {
                if (Schema::hasTable($table)) {
                    DB::table($table)->truncate();
                }
                $bar->advance();
            }
            $bar->finish();
            $this->newLine(2);

            $this->info('Ajustando personal…');
            DB::table('personal')->where('id', '!=', 1)->delete();
            DB::table('personal')->where('id', 1)->update([
                'nombre' => 'Administrador',
                'telefono' => null,
                'direccion' => null,
                'descripcion' => null,
            ]);

            $this->info('Ajustando usuarios y recreando la cuenta administradora…');
            DB::table('users')->where('id', '!=', 1)->delete();
            DB::table('users')->where('id', 1)->update([
                'name' => $this->option('admin-name'),
                'email' => $this->option('admin-email'),
                'password' => Hash::make($this->option('admin-password')),
                'matricula' => null,
            ]);

            $this->info('Reiniciando stock del catálogo a 0…');
            DB::table('tienda_articulo')->update(['stock' => 0]);

            $this->info('Blanqueando identidad de tienda y mi_empresa…');
            DB::table('tienda')->where('id', 1)->update([
                'nombre' => 'Mi Negocio',
                'direccion' => null,
                'telefono' => null,
                'cod_almacen' => null,
                'foto' => null,
            ]);
            DB::table('mi_empresa')->where('id', 1)->update([
                'nombre' => null, 'nit' => null, 'representante' => null, 'direccion' => null,
                'telefono' => null, 'descripcion' => null, 'localidad' => null, 'Correo' => null,
                'sitio_web' => null, 'foto' => null, 'logo_login' => null, 'logo_sistema' => null,
                'logo_usuario' => null, 'fondo_login' => null,
            ]);

            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        } catch (\Throwable $e) {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            $this->error('Error durante el reinicio: ' . $e->getMessage());
            $this->error('La operación quedó a medias (TRUNCATE no se puede revertir). Restaura desde el respaldo generado si es necesario.');
            return 1;
        }

        $this->newLine();
        $this->info('Base de datos reiniciada correctamente.');
        $this->table(['Cuenta administradora', 'Valor'], [
            ['Usuario', $this->option('admin-name')],
            ['Correo', $this->option('admin-email')],
            ['Contraseña', $this->option('admin-password')],
        ]);

        return 0;
    }

    private function backupDatabase(): bool
    {
        $binary = $this->locateMysqldump();
        if (!$binary) {
            $this->error('No se encontró el ejecutable mysqldump. Define MYSQLDUMP_PATH en tu .env con la ruta completa.');
            return false;
        }

        $database = config('database.connections.mysql.database');
        $host = config('database.connections.mysql.host');
        $port = config('database.connections.mysql.port');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');

        $backupDir = storage_path('backups');
        if (!is_dir($backupDir)) {
            mkdir($backupDir, 0755, true);
        }
        $file = $backupDir . DIRECTORY_SEPARATOR . $database . '_backup_' . now()->format('Y-m-d_His') . '.sql';

        $this->info("Generando respaldo en storage/backups/" . basename($file) . '…');

        $passwordArg = $password !== '' ? ('-p' . $password) : '';
        $command = sprintf(
            '%s -h%s -P%s -u%s %s %s > %s',
            escapeshellarg($binary),
            escapeshellarg($host),
            escapeshellarg((string) $port),
            escapeshellarg($username),
            $passwordArg,
            escapeshellarg($database),
            escapeshellarg($file)
        );

        exec($command, $outputLines, $exitCode);

        if ($exitCode !== 0 || !file_exists($file) || filesize($file) === 0) {
            @unlink($file);
            return false;
        }

        $this->info('Respaldo generado (' . round(filesize($file) / 1048576, 1) . ' MB).');
        return true;
    }

    private function locateMysqldump(): ?string
    {
        if ($configured = env('MYSQLDUMP_PATH')) {
            return file_exists($configured) ? $configured : null;
        }

        // Intenta encontrarlo en el PATH del sistema.
        $which = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? 'where' : 'which';
        exec("{$which} mysqldump 2>NUL", $lines, $code);
        if ($code === 0 && !empty($lines[0])) {
            return trim($lines[0]);
        }

        // Rutas típicas de instalaciones Laragon en Windows.
        foreach (glob('C:/laragon/bin/mysql/*/bin/mysqldump.exe') ?: [] as $candidate) {
            return $candidate;
        }

        return null;
    }
}
