<?php
namespace App\Models;

class FTS{

    function __construct($data)
    {
        $this->data = $data;        
    }

    private function first_init(){
        $first_init = (Object) [];

        //Langkah 1. Menentukan semesta pembicaraan
        $first_init->universe['lBound'] = $this->data->bound[0]['actual_vol']-0;
        $first_init->universe['uBound'] = $this->data->bound[1]['actual_vol']+0;

        //Langkah 2. Menentukan jumlah kelas (k) dan panjangnya (i)
        $first_init->class['k'] = round(1 + (3.322*log10(count($this->data->raw))));
        $first_init->class['i'] = ($first_init->universe['uBound'] - $first_init->universe['lBound'])/$first_init->class['k'];

        return $first_init;
    }

    public function interval_init(){
        //Langkah 3. Pembentukan Interval
        $value = $this->first_init();
        $iteration = $value->class['k'];

        //inisiasi interval pertama
        $interval[1]['lBound'] = $value->universe['lBound'];
        $interval[1]['uBound'] = $value->universe['lBound']+$value->class['i'];
        $interval[1]['midPoint'] =  ($interval[1]['lBound']+ $interval[1]['uBound'] )/2;
        
        for($i=2;$i<=$iteration;$i++){
            $interval[$i]['lBound'] = $interval[$i-1]['uBound'];
            $interval[$i]['uBound'] = $interval[$i]['lBound']+$value->class['i'];
            $interval[$i]['midPoint'] =  ($interval[$i]['lBound']+ $interval[$i]['uBound'])/2;
        }
        return $interval;
    }
    
    public function fuzzification($interval){
        $raw = $this->data->raw;
        $count_data = count($raw);
        $fuzz = null;
        
        for($i=0;$i<$count_data;$i++){
            for($j=1;$j<=count($interval);$j++){
                if($raw[$i]['actual_vol']>=$interval[$j]['lBound'] && $raw[$i]['actual_vol']<=$interval[$j]['uBound']){
                    $fuzz[$i]['year']=$raw[$i]['year'];
                    $fuzz[$i]['vol']=$raw[$i]['actual_vol'];
                    $fuzz[$i]['lh']=$j;
                }
            }

        }

        return $fuzz;
    }

    public function flr($fuzz){
        $j=0;
        for($i=0; $i<count($fuzz)-1; $i++){
            $fuzz[$i]['rh'] = $fuzz[$i+1]['lh'];
            $j++;
        }
        $fuzz[$j]['rh'] = '#'; 
        $flr_ed = $fuzz;
        return $flr_ed;
    }

    public function flrg($flr_ed, $interval){
        $count_raw_data = count($flr_ed);
        $count_intr = count($interval);
        
        $flrg_ed = [];
        $iter = 1;
        for($i=1;$i<=$count_intr;$i++){
            for($j=1;$j<=$count_intr;$j++){
                for($k=0;$k<$count_raw_data-1;$k++){
                    if($flr_ed[$k]['lh']==$i && $flr_ed[$k]['rh']==$j){
                        $flrg_ed[$i][$j]['rh_fuzzy']=$j;
                        $flrg_ed[$i][$j]['count']=$iter;
                        $flrg_ed[$i][$j]['total']=$iter*$interval[$j]['midPoint'];
                        $iter++;
                    }
                    
                }
                $iter = 1;
            }
        }
        //handling last FLR
        $last_flr = $flr_ed[$count_raw_data-1]['lh'];
        
        if(!isset($flrg_ed[$last_flr])){
            $flrg_ed[$last_flr][0]['rh_fuzzy']='#';
            $flrg_ed[$last_flr][0]['count']=1;
            $flrg_ed[$last_flr][0]['total']=$interval[$last_flr]['midPoint'];
        }
                 

        foreach($flrg_ed as $i=>$val){
            $flrg_ed[$i]=array_values($flrg_ed[$i]);
        }
        return $flrg_ed;   
    }

    public function defuzzification($flrg_ed, $fuzzified){
        $total = [];
        $count = [];

        foreach($flrg_ed as $index=>$value){
            $sum[$index] = array_sum(array_column($value,'total'));
            $count[$index] = array_sum(array_column($value,'count'));
            $total[$index] = $sum[$index]/$count[$index];
        }

        // return dd($sum, $count, $total);

        $result = (Object)[];

        $result->defuzz[0]['actual']=$fuzzified[0]['vol'];
        $result->defuzz[0]['result']=0;
        $result->defuzz[0]['mape']=0;

        for($i=1;$i<count($fuzzified);$i++){
            $result->defuzz[$i]['actual']=$fuzzified[$i]['vol'];
            $result->defuzz[$i]['result']=round($total[$fuzzified[$i-1]['lh']],3);
            $result->defuzz[$i]['mape']=round((abs($result->defuzz[$i]['actual']-$result->defuzz[$i]['result'])/$result->defuzz[$i]['actual'])*100, 3);
        }

        $result->upcoming=round($total[$fuzzified[$i-1]['lh']],3);
           
        $result->sum_error=(array_sum(array_column($result->defuzz,'mape'))/(count($fuzzified)-1));
        
        return $result;
    }

}


