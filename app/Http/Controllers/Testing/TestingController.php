<?php

namespace App\Http\Controllers\Testing;

use App\Http\Controllers\Controller;
use Carbon\Carbon;

/**
 * Class TestingController
 * @package App\Http\Controllers\Testing
 * @author  Ignacio Salcedo - ignacio@isalcedo.com
 */
class TestingController extends Controller
{
    private $dbHost;
    private $dbUser;
    private $dbPass;
    private $dbName;
    private $db2Host;
    private $db2User;
    private $db2Pass;
    private $db2Name;
    private $initialTime;
    private $backupFilePath;
    private $backupExchangeFilePath;
    private const FILENAME = 'backup_db_%s.sql';
    private const EXCHANGE_FILENAME = 'backup_exchange_db_%s.sql';
    private $hk_host = 'umabrisfx8afs3ja.cbetxkdyhwsb.us-east-1.rds.amazonaws.com';
    private $hk_username = 'zpbpciziuhq12nc8';
    private $hk_password = 'iuot0b61swhqcgwh';
    private $hk_database = 'cn146y2metbmmw00';

    /**
     * BkScript constructor.
     */
    public function __construct()
    {
        $this->dbHost = env('DB_HOST');
        $this->dbUser = env('DB_USERNAME');
        $this->dbPass = env('DB_PASSWORD');
        $this->dbName = env('DB_DATABASE');
        $this->db2Host = env('DB_HOST_SECOND');
        $this->db2User = env('DB_USERNAME_SECOND');
        $this->db2Pass = env('DB_PASSWORD_SECOND');
        $this->db2Name = env('DB_DATABASE_SECOND');
        $this->initialTime = Carbon::now();
    }

    /**
     * This method create, initially a backup from the current Database and synchronize this DB with the one in
     * heroku testing.
     */
    public function synchronizeTestingEnvironment()
    {
        $this->removePreviousBk();

        if ($this->createNewBk() === false) {
            return response()->json([
                'Error' => 'Backup AKB base database file wasn\'t created'
            ]);
        }

        if ($this->createNewRatesBk() === false) {
            return response()->json([
                'Error' => 'Backup AKB base database file wasn\'t created'
            ]);
        }

        if ($this->restoreInHeroku() === false) {
            return response()->json([
                'Error' => 'Restoration of AKB base in Heroku failed'
            ]);
        }

        return response()->json([
            'Success' => 'Sync is done'
        ]);
    }

    /**
     * Removes previous DB backup.
     */
    private function removePreviousBk(): void
    {
        $files = glob(app_path() . '/../database/testing-sync/*');

        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }

    /**
     * Create Database backup file
     * @return string|FALSE.
     */
    private function createNewBk()
    {
        $timeToSave = $this->initialTime->toIso8601String();
        $backupFilePath = app_path() . '/../database/testing-sync/' . sprintf(self::FILENAME, $timeToSave);
        $this->backupFilePath = $backupFilePath;
        $command = "mysqldump -h $this->dbHost -u $this->dbUser -p$this->dbPass " . "$this->dbName > \"$backupFilePath\"";

        return system($command);
    }

    /**
     * Create Database backup file
     * @return string|FALSE.
     */
    private function createNewRatesBk()
    {
        $timeToSave = $this->initialTime->toIso8601String();
        $backupFilePath = app_path() . '/../database/testing-sync/' . sprintf(self::EXCHANGE_FILENAME, $timeToSave);
        $this->backupExchangeFilePath = $backupFilePath;
        $tables = [
            'eur_prt_usd_prices',
            'clp_chl_usd_prices',
            'brl_bra_usd_prices',
            'anchor_exchange_rates',
            'eur_ita_usd_prices',
            'eur_esp_usd_prices',
            'mxn_mex_usd_prices',
            'ves_ven_usd_prices',
            'pen_per_usd_prices',
            'eur_fra_usd_prices',
            'pab_pan_usd_prices',
            'eur_deu_usd_prices',
            'countries',
            'ars_arg_usd_prices',
            'gbp_gbr_usd_prices',
            'cop_col_usd_prices'
        ];
        $tablesString = implode(' ', $tables);
        $command = "mysqldump -h $this->db2Host -u $this->db2User -p$this->db2Pass --opt --where=\"1 ORDER BY id DESC limit 300\" $this->db2Name $tablesString > $backupFilePath";

        return system($command);
    }

    /**
     * Restore previous backup on Heroku
     * @return string|FALSE
     */
    private function restoreInHeroku()
    {
        $restoreBaseDB = "mysql -h $this->hk_host -u $this->hk_username -p$this->hk_password $this->hk_database < $this->backupFilePath";

        if (system($restoreBaseDB) === FALSE) {
            return FALSE;
        }

        $restoreExchangeDB = "mysql -h $this->hk_host -u $this->hk_username -p$this->hk_password $this->hk_database < $this->backupExchangeFilePath";

        return system($restoreExchangeDB);
    }
}