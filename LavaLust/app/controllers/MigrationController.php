<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * MigrationController
 *
 * Exposes the LavaLust migration library through HTTP routes and the
 * custom CLI command (php lava migration ...).
 *
 * SECURITY: these routes can drop tables. Over HTTP they only work when
 * MIGRATION_ENABLED=true. Leave it unset on Render (production).
 */
class MigrationController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $enabled = filter_var(getenv('MIGRATION_ENABLED') ?: 'false', FILTER_VALIDATE_BOOLEAN);
        if (!(defined('IS_CLI') && IS_CLI) && !$enabled) {
            http_response_code(403);
            header('Content-Type: text/plain; charset=UTF-8');
            exit("Migrations are disabled on this server.\n");
        }

        $this->call->library('migration');
    }

    public function create_migration($migration_class)
    {
        $this->migration->create_migration($migration_class);
    }

    public function migrate()
    {
        $this->migration->migrate();
    }

    public function rollback()
    {
        $this->migration->rollback();
    }

    public function rollback_all()
    {
        $this->migration->rollback_all();
    }

    public function refresh()
    {
        $this->migration->refresh();
    }

    public function status()
    {
        $this->migration->status();
    }
}
