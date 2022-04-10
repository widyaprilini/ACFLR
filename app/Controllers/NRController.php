<?php

namespace App\Controllers;

class NRController extends BaseController
{
    function __construct(){ 
        $this->modelNR = model(NR_Model::class); 
    }

    public function store(){
        $data = [
            'year'=>$this->request->getVar('year'),
            'actual_vol'=>$this->request->getVar('actual_vol')
        ];
        $year = $data['year'];
        $query = $this->modelNR->insert($data);

        if($query){
            return "<script>alert('Volume ekspor karet alam tahun $year berhasil ditambahkan');document.location.href = '/';</script>";
            
        }
    }
    
    public function forecast(){

        if (isset($_POST['forecast-acflr'])) {

        $forecast = (Object)[];
        $forecast->fts = $this->modelNR->fts_forecasting();
        $forecast->acflr = $this->modelNR->acflr_forecasting(); 
        
        $data=[
            'actual_value'=>$this->modelNR->findAll(),
            'asc_value'=>$this->modelNR->ascending_data(),
            'latest_year'=>$this->modelNR->latest(),
            'first_year'=>$this->modelNR->first(),
            'forecast'=>$forecast
        ];
        
        return view ('dashboard_forecast', $data);
            }
            else {
          // JIKA REQUEST GET, MAKA BALIK KE HALAMAN AWAL
          return redirect('/');
            }
        
    }

    public function zoom_dashboard_forecasted()
    {
        $forecast = (Object)[];
        $forecast->fts = $this->modelNR->fts_forecasting();
        $forecast->acflr = $this->modelNR->acflr_forecasting();
        
        $data=[
            'actual_value'=>$this->modelNR->findAll(),
            'latest_year'=>$this->modelNR->latest(),
            'first_year'=>$this->modelNR->first(),
            'forecast'=>$forecast
        ];
        
        return view('full_chart_forecasted', $data);
    }
}