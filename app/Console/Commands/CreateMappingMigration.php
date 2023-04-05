<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateMappingMigration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:mapping_migration {path=none}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a mapping migration';

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

        // UKoliko puca zbog utf-8 boom
        // grep -rl $'\xEF\xBB\xBF' .
        //sed -i '1 s/^\xef\xbb\xbf//' *.json

        $file_name = $this->argument('path');
        if($file_name === "none") {
            $files = scandir('mapping_data');
            //var_dump($files) ;
            foreach($files as $file) {
                if (in_array($file, ['.', '..', '.DS_Store']) === false) {
                    $this->handleCategory($file);
                }
            }
        } else {
            $this->handleCategory($file_name);
        }

    }

    public function handleCategory($file_name) {
        $file_path = 'mapping_data/'.$file_name;
        $data_raw  = file_get_contents($file_path);
        $data      = json_decode($data_raw,true);
        $template  = file_get_contents('templates/maping_migration_template.php');
        $name_raw = $data['name'];
        $name     = strtolower($data['name']);
        $name_low = preg_replace('/\s/', '', $name);
        $name_low = preg_replace('/\//', '', $name_low);
        $name_low = preg_replace('/\-/', '', $name_low);
        $name_low = $this->stripAccents($name_low);
        $template = preg_replace('/\|\|category_name_raw\|\|/', $name_raw, $template);
        $template = preg_replace('/\|\|category_name\|\|/', $name_low, $template);
        $template = preg_replace('/\|\|category_name_import\|\|/', $data['name_import'], $template);
        $template = preg_replace('/\|\|category_url\|\|/', $data['url'], $template);

        $attributes = '';
        preg_match("/\|\|foreach_attribute_start\|\|(.*)\|\|foreach_attribute_end\|\|/s",$template, $att_tmp);
        foreach($data['attributes'] as $attribute) {
            $tmpl = $att_tmp[1];
            $tmpl = preg_replace('/\|\|attribute_machine_name\|\|/', $attribute['machine_name'], $tmpl);
            $tmpl = preg_replace('/\|\|attribute_name_import\|\|/', $attribute['name_import'], $tmpl);
            $tmpl = preg_replace('/\|\|attribute_label\|\|/', $attribute['label'], $tmpl);
            $tmpl = preg_replace('/\|\|attribute_type\|\|/', $attribute['type'], $tmpl);
            $tmpl = preg_replace('/\|\|attribute_order_category\|\|/', $attribute['order_category'], $tmpl);
            $tmpl = preg_replace('/\|\|attribute_order_filter\|\|/', $attribute['order_filter'], $tmpl);
            $tmpl = preg_replace('/\|\|attribute_order_product\|\|/', $attribute['order_product'], $tmpl);
            $attributes .= $tmpl;
        }

        $template = preg_replace('/\|\|category_attributes\|\|/', $attributes, $template);
        $template = preg_replace("/\|\|foreach_attribute_start\|\|(.*)\|\|foreach_attribute_end\|\|/s", '',$template);

        $migration_name = shell_exec("php artisan make:migration ".$name_low."_category_mapping");
        $migration_name = preg_replace('/Created Migration: /', '', $migration_name);
        $migration_name = trim($migration_name);

        $migrations_path = 'database/migrations/'.$migration_name.'.php';
        file_put_contents($migrations_path, $template);
        echo  'Napravljena migracija za kategoriju: '.$name_low. PHP_EOL;
    }

    public function stripAccents($name_low) {
        $name_low = preg_replace('/č/', 'c', $name_low);
        $name_low = preg_replace('/Č/', 'c', $name_low);
        $name_low = preg_replace('/ć/', 'c', $name_low);
        $name_low = preg_replace('/Ć/', 'c', $name_low);
        $name_low = preg_replace('/š/', 's', $name_low);
        $name_low = preg_replace('/Š/', 's', $name_low);

        return $name_low;
    }
}