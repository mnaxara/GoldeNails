<?php
namespace App\Service;

class Month{

    public $days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];

    private $months = [
        1 => ['name' =>'Janvier'],
        ['name' =>'Février'],
        ['name' =>'Mars'],
        ['name' =>'Avril'],
        ['name' =>'Mai'],
        ['name' =>'Juin'],
        ['name' =>'Juillet'],
        ['name' =>'Aout'],
        ['name' =>'Septembre'],
        ['name' =>'Octobre'],
        ['name' =>'Novembre'],
        ['name' =>'Decembre']
    ];

    private $date;

    public function __construct(\DateTime $date){
        $this->date = $date;
    }

    public function getMonthData (){
        $month = $this->getMonth();
        $year = $this->getYear();
        $monthData = [];
        for ($i = 1; $i <= $this->getNumberOfDay(); $i++){
            $date = new \DateTime($i."-".$month."-".$year);
            $monthData[$i] = [
                'day' => $this->getDayName($date),
                'dayNumber' => $i,
            ];
        }
        return $monthData;
    }


    /**
     * @return mixed
     */
    public function getMonth (){
        return $this->date->format('m');
    }

    /**
     * @return mixed
     */
    public function getMonthName (){
        return $this->months[$this->date->format('n')];
    }

    /**
     * @return mixed
     */
    public function getNumberOfDay (){
        return $this->date->format('t');
    }

    /**
     * @return mixed
     */
    public function getYear()
    {
        return $this->date->format('Y');
    }

    public function getDayName (\DateTime $date): string {
        return $this->days[intval($date->format('N'))-1];
    }


}
