<?php declare(strict_types=1);

namespace Codebreaker;

class gameLogic
{
    private const VALUES=['1','2','3','4','5','6'];
    private const SIZE=4;

    private $solution;

    public function __construct()
    {
        $this->solution=$this->createSolution();
    }

    public function createSolution()
    {
        $totalValues=count(self::VALUES)-1;
        $solution=[];
        for($i=0;$i<self::SIZE;$i++){
            $solution[]=self::VALUES[random_int(0,$totalValues)];
        }

        return $solution;
    }
    public function checkAnswer($answer)
    {
        $checks=[];
        for($j=0; $j< self::SIZE; $j++){
            $checks[]='notFound';
        }
        for($j=0; $j< self::SIZE; $j++){
            if($answer[$j]===$this->solution[$j]){
                $checks[$j]='found';
            }
        }
        for($j=0; $j< self::SIZE; $j++) {
            if($checks[$j]!=='found'){
                for($k=0; $k< self::SIZE; $k++){
                    if($checks[$k]==='notFound'&&$answer[$j]===$this->solution[$k]){
                        $checks[$k]='notCorrect';
                        $k=self::SIZE;
                    }
                }
            }
        }

        return $this->buildAnswer($checks);
    }
    public function buildAnswer($checks)
    {
        $success="";
        for($j=0; $j< self::SIZE; $j++) {
            if($checks[$j]==='found'){
                $success.='+';
            }
        }
        for($j=0; $j< self::SIZE; $j++) {
            if($checks[$j]==='notCorrect'){
                $success.='-';
            }
        }

        return $success;
    }

    public function checkValues($answer)
    {
        $checkValues=true;
        if(count($answer)!==self::SIZE){
            $checkValues=false;
        }else {
            foreach ($answer as $a) {
                if (in_array($a, self::VALUES, true) === false) {
                    $checkValues = false;
                }
            }
        }

        return $checkValues;
    }

    public function getSolution()
    {
        return implode($this->solution);
    }
}
