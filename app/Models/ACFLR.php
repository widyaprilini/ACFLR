<?php
namespace App\Models;

class ACFLR extends FTS{

    // function BreakLoop($MaxRepetitions=500,$LoopSite="unspecified")
    // {
    // static $Sites=[];
    // if (!@$Sites[$LoopSite] || !$MaxRepetitions)
    //     $Sites[$LoopSite]=['n'=>0, 'if'=>0];
    // if (!$MaxRepetitions)
    //     return;
    // if (++$Sites[$LoopSite]['n'] >= $MaxRepetitions)
    //     {
    //     $S=debug_backtrace(); // array_reverse
    //     $info=$S[0];
    //     $File=$info['file'];
    //     $Line=$info['line'];
    //     exit("*** Loop for site $LoopSite was interrupted after $MaxRepetitions repetitions. In file $File at line $Line.");
    //     }
    // } // BreakLoop

    function __construct($data)
    {
        $this->data = $data;  
        $this->raw_asc = $this->data->raw;

        function build_sorter($key) {
            return function ($a, $b) use ($key) {
                return strnatcmp($a[$key], $b[$key]);
            };
        }
        usort($this->raw_asc,build_sorter('actual_vol'));     
        $this->raw_asc = array_values(array_unique(array_column($this->raw_asc, 'actual_vol')));
    }

    public function first_init(){

        $raw_asc = $this->raw_asc;
        // return dd($raw_asc);

        $first_init = (Object)[];
        //Langkah 1. Mencari average diff
        $first_init->avg[0]=0;
        for($i=0;$i<count($raw_asc)-1;$i++){
            $first_init->avg[$i+1] = $raw_asc[$i+1]-$raw_asc[$i];
        }
        
        $first_init->average_diff = array_sum($first_init->avg)/count($raw_asc)-1;
        
        return $first_init;        
        
    }

    public function cluster_init(){
        $raw_asc = $this->raw_asc;
        // return dd($raw_asc);
        $avg_dif = $this->first_init()->average_diff;
        
        $cluster = [];
        //Membuat datum pertama menjadi anggota cluster pertama (index 0)
        $cluster[0][0]= $raw_asc[0];
        
        //Prinsip 1
        if($raw_asc[1]-$raw_asc[0]<=$avg_dif){
            $cluster[0][1] = $raw_asc[1];
        }else{
            $cluster[1][0]=$raw_asc[1];
        }       
        
        for($i=2;$i<count($raw_asc);$i++){
            $curr_cluster = end($cluster);
            $count_curr = count($curr_cluster);
            $last_datum_bef = end($curr_cluster);
            $curr_datum = $raw_asc[$i];

            //prinsip 3 langkah 2
            if($count_curr>1){
                $cluster_diff = 0;
                $cluster_avg = [];
                
                for($j=0;$j<$count_curr-1;$j++){
                    $cluster_avg[$j+1] = $curr_cluster[$j+1]-$curr_cluster[$j];
                }
                $cluster_diff = array_sum($cluster_avg)/count($cluster_avg);

                if($curr_datum-$last_datum_bef<=$avg_dif && $curr_datum-$last_datum_bef<=$cluster_diff){
                    $curr_cluster[$count_curr+1] = $curr_datum;
                    $cluster[array_key_last($cluster)][$count_curr]= $curr_datum;
                }else{
                    $cluster[array_key_last($cluster)+1][0]= $curr_datum;
                }
            }else{//prinsip 2 langkah 2
                $cluster_bef = $cluster[array_key_last($cluster)-1];
                if($curr_datum-$last_datum_bef<=$avg_dif && $curr_datum-$last_datum_bef<end($cluster_bef)){
                    $curr_cluster[$count_curr+1] = $curr_datum;
                    $cluster[array_key_last($cluster)][$count_curr]= $curr_datum;
                }else{
                    $cluster[array_key_last($cluster)+1][0]= $curr_datum;
                }
            }
        }
        return $cluster;
       
        }

        public function cluster_adjust($cluster){
            $avg_dif = $this->first_init()->average_diff;
            
            foreach($cluster as $key=>$value){
                //Prinsip 1 Langkah 3
                if(count($cluster[$key])>2){
                    $new_cluster[$key][0]=$cluster[$key][0];
                    $new_cluster[$key][1]=end($cluster[$key]);
                }elseif(count($cluster[$key])==2){//Prinsip 2 Langkah 3
                    $new_cluster[$key][0]=$cluster[$key][0];
                    $new_cluster[$key][1]=$cluster[$key][1];
                }
                elseif(count($cluster[$key])==1){//Prinsip 3 Langkah 3
                    $new_cluster[$key][0]=$cluster[$key][0]-$avg_dif;
                    $new_cluster[$key][1]=$cluster[$key][0]+$avg_dif;
                    //Kondisi 1 Prinsip 3 Langkah 3
                    if($key == 0){
                        $new_cluster[$key][0]=$cluster[$key][0];
                    }elseif($key == array_key_last($cluster)){//Kondisi 2 Prinsip 3 Langkah 3
                        $new_cluster[$key][1]=$cluster[$key][0];
                    }
                    if($new_cluster[$key][0] < end($cluster[$key-1])){//Kondisi 3 Prinsip 3 Langkah 3
                        unset($new_cluster[$key]);
                        $new_cluster[$key][0]=$cluster[$key][0];
                    }

                }
            }

            return $new_cluster;
        }

        public function interval_init_acflr($new_cluster){
            //Inisiasi interval pertama
            $interval[1]['lBound']=$new_cluster[0][0];
            $interval[1]['uBound']=$new_cluster[0][1];
            $interval[1]['midPoint'] = ($interval[1]['lBound']+ $interval[1]['uBound'])/2;
            $j = 2;
            for($i=1;$i<count($new_cluster);$i++){
                $curr_interval = end($interval);
                $curr_cluster = $new_cluster[$i];

                //Kondisi 1 Langkah 4
                if($curr_cluster[0]<=$curr_interval['uBound']){
                    $interval[$j]['lBound'] = $curr_interval['uBound'];
                    $interval[$j]['uBound'] = $curr_cluster[1];
                    $interval[$j]['midPoint'] = ($interval[$j]['lBound']+ $interval[$j]['uBound'])/2;
                }elseif($curr_cluster[0]>$curr_interval['uBound']){
                    if(count($curr_cluster)==1){
                        $interval[$j-1]['lBound'] = $curr_interval['lBound']; 
                        $interval[$j-1]['uBound'] = $curr_cluster[0];
                        $interval[$j-1]['midPoint'] = ($interval[$j-1]['lBound']+ $interval[$j-1]['uBound'])/2;
                        $j--; 
                    }else{
                        $interval[$j]['lBound'] = $curr_interval['uBound'];
                        $interval[$j]['uBound'] = $curr_cluster[0];
                        $interval[$j]['midPoint'] = ($interval[$j]['lBound']+ $interval[$j]['uBound'])/2;
                        $interval[$j+1]['lBound'] = $curr_cluster[0];
                        $interval[$j+1]['uBound'] = $curr_cluster[1];   
                        $interval[$j+1]['midPoint'] = ($interval[$j+1]['lBound']+ $interval[$j+1]['uBound'])/2; 
                        $j++;
                        
                    }
                }
                $j++;
                
                }
                return $interval;
            }

            public function sub_interval_init($interval, $p){
                $j=1;
                foreach($interval as $key=>$value){
                    $gap = $interval[$key]['uBound']-$interval[$key]['lBound'];
                    $plus = $gap/$p;
                    $new_interval[$j]['lBound']=$interval[$key]['lBound'];

                    for($i=1;$i<=$p;$i++){
                        $new_interval[$j]['uBound'] = $interval[$key]['lBound']+$i*$plus;
                        $new_interval[$j]['midPoint'] = ($interval[$key]['lBound']+$new_interval[$j]['uBound'])/2;
                        $new_interval[$j+1]['lBound'] = $new_interval[$j]['uBound'];
                        $j++;
                    }
                }

                unset($new_interval[array_key_last($new_interval)]);

                return $new_interval;
            }
            
        }
        
            
        
        
        
    

