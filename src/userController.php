<?php declare(strict_types=1);

namespace Codebreaker;

class userController
{
    public function welcome()
    {
        return fwrite(STDOUT,"Hi Let's play CodeBreaker!!! Write q to finish the game\n\nWrite 4 numbers between 1 and 6\n\n");
    }

    public function userWantToQuit($answer)
    {
        if ($answer[0] === 'q'){
            return fwrite(STDOUT, "Bye\n");
        }
        return false;
    }

    public function getUserResponse()
    {
        $userResponse=trim(fgets(STDIN));

        return str_split($userResponse);
    }

    public function error()
    {
        return fwrite(STDOUT, "Please introduce 4 numbers between 1 and 6\n");
    }

    public function gameComplete($tries)
    {
        return fwrite(STDOUT, "CONGRATULATIONS!!! YOU FIND THE CORRECT ANSWER IN $tries ATTEMPTS\n");
    }

    public function gameIncomplete($solution)
    {
        return fwrite(STDOUT, "Sorry the correct answer was $solution\n");
    }

    public function gameContinue($clue,$tries)
    {
        return fwrite(STDOUT, "$clue \n $tries Attempts left\n\n");
    }
}
