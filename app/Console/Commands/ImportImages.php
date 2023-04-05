<?php

namespace App\Console\Commands;

use App\Providers\AdminService;
use App\Providers\BaseService;
use App\Providers\ImportService;
use Illuminate\Console\Command;
use Doctrine\ORM\EntityManagerInterface;

class ImportImages extends Command {
    private $entityManager;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:images  {artid?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports images for product by artid';

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
        $artid    = $this->argument('artid');
        $username = env('IMPORT_USERNAME');//$this->ask('username');
        $password =  env('IMPORT_PASSWORD');//$this->secret('Password');

        try {
            AdminService::logIn($username, $password);
            ImportService::importImageForArtid($artid);
        } finally {
            AdminService::logOut();
        }
    }
}
