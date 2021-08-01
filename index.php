<?php


interface Handler {
    public function setNext(Handler $handler): Handler;

    public function handle(string $request): ?string;
}

abstract class AbstractHandler implements Handler {
    private $nextHandler;

    public function setNext(Handler $handler):Handler {
        $this->nextHandler = $handler;
        return $handler;
    }

    public function handle(string $request): ?string {
        if($this->nextHandler) {
            return $this->nextHandler->handle($request);
        }
        return null;
    }
}


class MonkeyHandler extends AbstractHandler {
    public function handle(string $request): ?string 
    {
        if($request === "Banana") {
            return "Monkey: I'll eat the $request .\n";
        }else{
            return parent::handle($request);
        }
    }
}

class SquirrelHandler extends AbstractHandler {
    public function handle(string $request): ?string 
    {
        if( $request === "Nut") {
            return "Squirrel: i'll eat the $request. \n";
        }else{
            return parent::handle($request);
        }
    }
}

class DogHandler extends AbstractHandler {
    public function handle(string $request): ?string 
    {
        if($request === 'MeatBall') {
            return "Dog: i'll eat the $request. \n";
        }else{
            return parent::handle($request);
        }
    }
}

/**
 * the client code is usually suited to work with a single handler.
 *  In most cases, it is not even aware that the handler is part of a chain.
 */

 function clientCode(Handler $handler)
 {
     $data = ["Nut","Banana","Cup of Coffee"];
     foreach($data as $item){
         echo "Client: who wants a $item ? \n";
         $result = $handler->handle($item);
         if($result){
             echo " " . $result;
         }else{
             echo " ". $item . "was left untouched!";
         }
     }
 }

 $monkey = new MonkeyHandler();
 $squirrel = new SquirrelHandler();
 $dog = new DogHandler();

 $monkey->setNext($squirrel)->setNext($dog);

 /**
  * the cluent should be able to send a request to any handler, not jyst the first on in the chain
  */
  echo "Chain:Monkey > Squirrel > Dog \n\n";
  clientCode($monkey);
  echo "\n";

  echo "Subchain: Squirrel > Dog \n\n";
  clientCode($squirrel);