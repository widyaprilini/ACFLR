<?php 
namespace App\Models;
use CodeIgniter\Model;

require('FTS.php');

class NR_Model extends Model{
    protected $table = 'natural_rubber';
    protected $allowedFields = ['year', 'actual_vol'];

    public function latest(){
        $query = $this->db->query("SELECT year FROM natural_rubber ORDER BY id DESC LIMIT 1");
        $result = $query->getRowArray();
        return $result;
    }

    private function raw_data(){
        $result = (Object)[];

        $query = $this->db->query("SELECT year,actual_vol FROM `natural_rubber`");
        $result->raw = $query->getResultArray();
        
        $query2 = $this->db->query("(SELECT actual_vol FROM natural_rubber ORDER BY `actual_vol` ASC LIMIT 1) UNION ALL (SELECT actual_vol FROM natural_rubber ORDER BY `actual_vol` DESC LIMIT 1)");
        $result->bound = $query2->getResultArray();
        return $result;
    }

    public function ascending_data(){

        $query = $this->db->query("SELECT DISTINCT actual_vol FROM `natural_rubber` ORDER BY `actual_vol` ASC");
        $result = $query->getResultArray();
        
        return $result;
    }

    public function fts_forecasting(){
        //incl data
        $data = $this->raw_data();
        $fts_obj = new FTS($data);

        $fts_interval  = $fts_obj->interval_init();

        $fuzzification = $fts_obj->fuzzification($fts_interval);

        $flr_process = $fts_obj->flr($fuzzification);
        
        $flrg_process = $fts_obj->flrg($flr_process, $fts_interval);

        $result_fts = $fts_obj->defuzzification($flrg_process, $fuzzification);
        
        return $result_fts;     
        
    }

    public function acflr_forecasting(){
        //incl data
        $data = $this->raw_data();
        $acflr_obj = new ACFLR($data);
        $result = (Object)[];
        
        $result->first_init = $acflr_obj->first_init();
        $cluster = $acflr_obj->cluster_init();
        $result->cluster_adjust = $acflr_obj->cluster_adjust($cluster);
        $acflr_interval = $acflr_obj->interval_init_acflr($result->cluster_adjust);
        $result->sub_interval = $acflr_obj->sub_interval_init($acflr_interval, 15);
        
        $fuzzification = $acflr_obj->fuzzification($result->sub_interval);

        $result->flr_process = $acflr_obj->flr($fuzzification);
        
        $result->flrg_process = $acflr_obj->flrg($result->flr_process, $result->sub_interval);

        $result->result_acflr = $acflr_obj->defuzzification($result->flrg_process, $fuzzification);
        
        return $result;
        
    }
}