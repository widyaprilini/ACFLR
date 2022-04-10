<?php

namespace App\Controllers;

class Home extends BaseController
{
    function __construct(){
        
        $this->modelNR = model(NR_Model::class);

    }
    public function index()
    {
        $data=[
            'actual_value'=>$this->modelNR->findAll(),
            'latest_year'=>$this->modelNR->latest(),
            'first_year'=>$this->modelNR->first()
        ];
        
        return view('dashboard', $data);
    }
    public function zoom_dashboard()
    {
        $data=[
            'actual_value'=>$this->modelNR->findAll(),
            'latest_year'=>$this->modelNR->latest(),
            'first_year'=>$this->modelNR->first(),
        ];
        
        return view('full_chart', $data);
    }

    
    
    
}
