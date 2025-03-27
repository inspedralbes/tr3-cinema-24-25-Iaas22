<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReservaConfirmadaMail extends Mailable
{
    use SerializesModels;

    public $name;
    public $apellidos;
    public $seats;
    public $total;
    public $session_id;

    /**
     * Create a new message instance.
     *
     * @param string $name
     * @param string $apellidos
     * @param array $seats
     * @param float $total
     * @param int $session_id
     */
    public function __construct($name, $apellidos, $seats, $total, $session_id)
    {
        $this->name = $name;
        $this->apellidos = $apellidos;
        $this->seats = $seats;
        $this->total = $total;
        $this->session_id = $session_id;
    }

    public function build()
    {
        return $this->subject('Confirmación de Reserva')
                    ->view('emails.reserva_confirmada');  // Se usará un archivo Blade para el contenido
    }
}
