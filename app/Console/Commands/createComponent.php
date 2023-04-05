<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class createComponent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'new:component {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new component with its blade css and js files';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->argument('name');
     
        try {

            $path = 'public/Components/'.$name;
            
            if (file_exists($path)) {
               $this->error('Komponenta sa tim imenom vec postoji');
            }

            mkdir($path);
            
            mkdir($path.'/js');
            mkdir($path.'/css');
            mkdir($path.'/templates');
            $create_js      = fopen($path.'/js/'.$name.'.js', 'w');
            $create_css     = fopen($path.'/css/'.$name.'.css', 'w');
            $create_blade   = fopen($path.'/templates/'.$name.'.blade.php', 'w');

            $basic_path     = 'app/Components/BasicComponent.php';
            $component_path = 'app/Components/'.$name.'.php';

            $create_component = fopen($component_path, 'w');
            //copy($basic_path, $component_path);
            
            $str = file_get_contents($basic_path);
            $str = str_replace("BasicComponent", $name, $str);
            
            file_put_contents($component_path, $str);
        } catch(\Exception $e) {
            return $e->getMessage();
        }
    }
}
