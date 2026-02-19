<?php

class Resultat
{

    public const DISPLAY_TYPE_POPUP = "Popup";

    public const DISPLAY_TYPE_ERREUR = "Erreur";


    private bool $succeeded;

    private string $message;

    private string $display_type;

    private bool $redirect;

    private string $redirectURL;

    public function __construct(bool $succeeded = false, string $message = "", string $display_type = "", bool $redirect = false, string $redirectURL = "")
    {
        $this->succeeded = $succeeded;
        $this->message = $message;
        $this->display_type = $display_type;
        $this->redirect = $redirect;
        $this-> redirectURL = $redirectURL;
    }

    public function getSucceeded(): bool
    {
        return $this->succeeded;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getDisplay_type(): string
    {
        return $this->display_type;
    }

    public function getRedirect(): bool {
        return $this->redirect;
    }

    public function getRedirectURL(): string {
        return $this->redirectURL;
    }

    public function setSucceeded(bool $newValue): void
    {
        $this->succeeded = $newValue;
    }

    public function setMessage(string $newValue): void
    {
        $this->message = $newValue;
    }

    public function setDisplay_type(string $newValue): void
    {
        $this->display_type = $newValue;
    }

    public function setRedirect(bool $redirect): void {
        $this->redirect = $redirect;
    }

    public function setRedirectURL(string $redirectURL): void {
        $this->redirectURL = $redirectURL;
    }

    public function isPopup(): bool
    {
        if ($this->display_type == Resultat::DISPLAY_TYPE_POPUP) {
            return true;
        }
        return false;
    }

    public function isErreur(): bool
    {
        if ($this->display_type == Resultat::DISPLAY_TYPE_ERREUR) {
            return true;

        }
        return false;

    }

}

?>