<?php

namespace Codebreaker;

class Codebreaker
{
    private const TOTAL_TRIES=10;

    private $tryNumber=0;

    public function play()
    {
        $userController = new userController();
        $gameLogic=new gameLogic();
        $userController->welcome();
        while($this->tryNumber<self::TOTAL_TRIES){
            $answer=$userController->getUserResponse();
            if($userController->userWantToQuit($answer)){
                break;
            }
            if(!$gameLogic->checkValues($answer)) {
                $userController->error();
                continue;
            }
            $success=$gameLogic->checkAnswer($answer);
            $this->tryNumber++;
            if ($success ==='++++') {
                $userController->gameComplete($this->tryNumber);
                break;
            }
            if($this->tryNumber===self::TOTAL_TRIES) {
                $userController->gameIncomplete($gameLogic->getSolution());
            } else{
                $userController->gameContinue($success,self::TOTAL_TRIES-$this->tryNumber);
            }
        }
    }

}
