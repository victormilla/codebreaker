<?php

class Codebreaker
{
    private const TOTAL_TRIES=10;
    private const VALUES=['1','2','3','4','5','6'];
    private const SIZE=4;

    private $tryNumber=0;
    private $final=[];

    public function play()
    {
        fwrite(STDOUT,"Hi Let's play CodeBreaker!!! Write q to finish the game\n\n");
        $this->createSolution();
        fwrite(STDOUT, "Write 4 numbers between 1 and 6\n\n");
        while($this->tryNumber<self::TOTAL_TRIES){
            $answer=$this->getUserResponse();
            if ($answer[0] === 'q'){
                fwrite(STDOUT, "Bye\n");
                break;
            }
            if(count($answer)!==self::SIZE||!$this->checkValues($answer)) {
                fwrite(STDOUT, "Please introduce 4 numbers between 1 and 6\n");
                continue;
            }
            $success=$this->checkAnswer($answer);
            $this->tryNumber++;
            if ($success ==='++++') {
                fwrite(STDOUT, "CONGRATULATIONS!!! YOU FIND THE CORRECT ANSWER IN $this->tryNumber ATTEMPTS\n");
                break;
            }
            if($this->tryNumber===self::TOTAL_TRIES) {
                $response='Sorry the correct answer was '.implode($this->final)."\n";
            } else{
                $rest=self::TOTAL_TRIES-$this->tryNumber;
                $response="$success \n $rest Attempts left\n\n";
            }
            fwrite(STDOUT, $response);
        }
    }
    private function checkAnswer($answer)
    {
        $checks=[];
        for($j=0; $j< self::SIZE; $j++){
            $checks[]='notFound';
        }
        for($j=0; $j< self::SIZE; $j++){
            if($answer[$j]===$this->final[$j]){
                $checks[$j]='found';
            }
        }
        for($j=0; $j< self::SIZE; $j++) {
            if($checks[$j]!=='found'){
                for($k=0; $k< self::SIZE; $k++){
                    if($checks[$k]==='notFound'&&$answer[$j]===$this->final[$k]){
                        $checks[$k]='notCorrect';
                        $k=self::SIZE;
                    }
                }
            }
        }

        return $this->buildAnswer($checks);
    }
    private function buildAnswer($checks)
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
    private function createSolution()
    {
        $totalValues=count(self::VALUES)-1;
        $solution=[];
        for($i=0;$i<self::SIZE;$i++){
            $solution[]=self::VALUES[random_int(0,$totalValues)];
        }

        $this->final=$solution;
    }
    private function getUserResponse()
    {
        $userResponse=trim(fgets(STDIN));

        return str_split($userResponse);
    }

    private function checkValues($answer)
    {
        $checkValues=true;
        foreach ($answer as $a){
            if(in_array($a,self::VALUES,true)===false){
                $checkValues=false;
            }
        }

        return $checkValues;
    }
}

$game=new Codebreaker();
$game->play();
