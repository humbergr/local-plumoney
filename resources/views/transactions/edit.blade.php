@if ($transaction->type === 'Incoming')
 @include('transactions.editout')
@else
@include('transactions.editint')
@endif