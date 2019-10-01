<?php


namespace App\Service;


class AddTime
{
    public function __construct()
    {
    }

    public function addtime($time1,$time2)
    {
         $x = $time1;
         $y = $time2;

         $interval1 = $x->diff(new \DateTime('1970-01-01 00:00:00')) ;
         $interval2 = $y->diff(new \DateTime('1970-01-01 00:00:00')) ;

         $e = new \DateTime('1970-01-01 00:00:00');
         $f = clone $e;
         $e->add($interval1);
         $e->add($interval2);
         $total = $f->diff($e)->format("%H:%I:%S");
         return $total;
    }
}
