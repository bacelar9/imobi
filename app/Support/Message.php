<?php 
/**
* | Classe responsável por renderizar 
* | estilos de Mensagens
*/
namespace LaraDev\Support;

class Message 
{
    /** Atributos */
    private $text;
    private $type;

    public function getText()
    {
        return $this->text;
    }

    public function getType()
    {
        return $this->type;
    }

    public function success(string $message): Message
    {
        $this->type = 'success';
        $this->text = $message;
        return $this;
    }

    public function error(string $message): Message
    {
        $this->type = 'error';
        $this->text = $message;
        return $this;
    }

    public function render()
    {
        return "<div class='message {$this->getType()}'>{$this->getText()}</div>";
    }

}
