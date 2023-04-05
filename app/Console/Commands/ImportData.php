<?php

namespace App\Console\Commands;

use App\Components\DataImport;
use App\Providers\AdminService;
use App\Providers\BaseService;
use Illuminate\Console\Command;
use Doctrine\ORM\EntityManagerInterface;

class ImportData extends Command
{
    private $entityManager;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:data {type?} {param?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports data';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;

        BaseService::setEntityManager($entityManager);
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $type     = $this->argument('type');
        $param    = $this->argument('param');
        $username = env('IMPORT_USERNAME');//$this->ask('username');
        $password =  env('IMPORT_PASSWORD');//$this->secret('Password');

        if ($type === 'artid') {
            $update_mode = 'importProduct';
        } else {
            $update_mode = 'importProducts';
        }

        try {
            AdminService::logIn($username, $password);
            $dic = new DataImport($update_mode);
            $dic->import($param, [], [], true);
        } finally {
            AdminService::logOut();
        }
    }
}
