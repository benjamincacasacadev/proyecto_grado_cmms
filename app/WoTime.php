<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use Carbon\Carbon;
use Carbon\CarbonInterval;

class WoTime extends Model {
    protected $fillable = [
        'work_order_id', 'init_work_date', 'end_work_date', 'user_id'
    ];

    protected $dates = ['init_work_date','end_work_date'];

    // RELACION A ORDENES DE TRABAJO
    public function workOrder() {
        return $this->belongsTo(WorkOrders::class, 'work_order_id');
    }
    // RELACION A USUARIOS
    public function users() {
        return $this->belongsTo(User::class, 'user_id');
    }

    //  Accessor para obtener el tiempo duracion
    public function getDurationTextAttribute(){
        $interval = CarbonInterval::createFromFormat('H:i:s', '00:00:00');
        if(isset($this->init_work_date) && isset($this->end_work_date)){
            $firstTime = $this->init_work_date;
            $lastTime = $this->end_work_date;
            $difSegundos = Carbon::parse($lastTime)->floatDiffInSeconds($firstTime);
            $interval->addSeconds($difSegundos)->cascade();
            $intervaloText =  $interval->forHumans();
        }else{
            $intervaloText = 'Indefinido';
        }

        return $intervaloText;
    }

    public function scopeInitDate($query, $initTime){
        if($initTime != ''){
            $query->whereDate('end_work_date','>',$initTime)->orWhere('end_work_date',null);
        }
    }
}
