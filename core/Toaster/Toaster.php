<?php 
namespace Core\Toaster;

use Core\Session\SessionInterface;

class Toaster
{
    private const SESSION_KEY = 'toast';

    const ERROR = 0;
    const WARNING = 1;
    const SUCCESS = 2;

    private Toast $toast;
    private SessionInterface $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
        $this->toast = new Toast();
    }

    public function makeToast(string $message, int $etat): void
    {
        switch($etat){

            case 0:
                $this->session->setArray(self::SESSION_KEY, $this->toast->error($message));
                break;
            case 1:
                $this->session->setArray(self::SESSION_KEY, $this->toast->warning($message));
                break;
            case 2:
                $this->session->setArray(self::SESSION_KEY, $this->toast->success($message));
        }
    }

    public function renderToast(): ?array
    {
        $toast = $this->session->get(self::SESSION_KEY);
        $this->session->delete(self::SESSION_KEY);

        return $toast;
    }

    public function hasToast(): bool
    {
       if($this->session->has(self::SESSION_KEY) && sizeof( $this->session->get(self::SESSION_KEY)) > 0)
       {
          return true;
       }
       return false;
    }
      
}