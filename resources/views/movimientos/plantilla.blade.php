@extends('layouts.coinbank-layout')

@section('content')

    <main class="dashboard__main"
          style="padding: 24px; background: #f4f4f9">
        <div class="container">




<div class="card ">
    
    <div class="card-header text-primary font-weight-bold"> <h4>Cajas & Bancos</h4></div>

<div class="card-body">
    
 

 <form action="{{ url('bancos') }}" method="POST" accept-charset="utf-8">
      

               
 </form>
</div>
</div>            
                </div>
         
    </main>

@endsection
