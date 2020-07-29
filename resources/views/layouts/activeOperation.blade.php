<?php

use App\UserExchangeTransactions;
use Illuminate\Support\Facades\Auth;

$user = Auth::user();
if ($user !== null) {
    $activeTransaction = UserExchangeTransactions::where(
        [
            'user_id' => $user->id,
            'status'  => 0
        ]
    )->orderBy('created_at', 'DESC')
        ->first();

    if (!isset($sendCash) && $activeTransaction && $activeTransaction['payment_way'] === 'cash_deposit') {
        echo '<a href = "/send-cash/' . $activeTransaction['id'] . '" class="__active_transaction_message">
            <img src="/img/icons/warning-sign.svg" alt="Alerta, Alert!">
            <span class="mb-2 mt-2">Tienes una operación de <strong>envío con pago asistido</strong> activa.</span>
            <span class="btn btn-secondary btn-sm btn-pill">Ir a la operación</span>
        </a>';
    }
}
?>
