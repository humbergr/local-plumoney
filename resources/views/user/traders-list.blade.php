@extends('layouts.coinbank-layout')

@section('content')

<section id="transactions" class="py-section-3">
      <div class="container">
          <div class="d-flex justify-content-between align-items-end border-bottom flex-wrap pb-3">
              <h6 class="text-primary font-weight-bold text-truncate mb-md-0">Lista de Traders</h6>
              <form action="" method="get" class="form-inline flex-md-nowrap ml-md-3">
                  <div class="input-group mb-3 mb-md-0 mr-3" style="width: 180px">
                      <div class="input-group-prepend">
                          <span class="input-group-text">
                              <i class="fa fa-search text-muted"></i>
                          </span>
                      </div>
                      <input type="text"
                             class="form-control"
                             name="user-name"
                             value="{{request()['user-name']}}"
                             placeholder="Buscar por nombre">
                  </div>
                  <div class="input-group mb-3 mb-md-0 mr-3" style="width: 180px">
                      <div class="input-group-prepend">
                          <span class="input-group-text">
                              <i class="fa fa-search text-muted"></i>
                          </span>
                      </div>
                      <input type="text"
                             class="form-control"
                             name="user-lastname"
                             value="{{request()['user-lastname']}}"
                             placeholder="Buscar por Apellido">
                  </div>
                  <div class="input-group mb-3 mb-md-0 mr-3" style="width: 180px">
                      <div class="input-group-prepend">
                          <span class="input-group-text">
                              <i class="fa fa-search text-muted"></i>
                          </span>
                      </div>
                      <input type="text"
                             class="form-control"
                             name="user-email"
                             value="{{request()['user-email']}}"
                             placeholder="Buscar por Email">
                  </div>
                  <div class="input-group mb-3 mb-md-0">
                      <input type="submit" class="ml-3 btn btn-primary" value="Filtrar">
                  </div>
                  @if (request()['user-name'] || request()['user-lastname'] || request()['user-email'])
                      <div class="input-group mb-3 mb-md-0">
                          <a href="/traders" class="ml-3 btn btn-primary">Limpiar</a>
                      </div>
                  @endif
              </form>
          </div>
          <div class="card">
              <table class="table table-striped table-borderless mb-0">
                <thead>
                  <tr>
                    <th class="text-center"><h5 class="text-muted font-weight-bold mb-0">Name</h5></th>
                    <th class="text-center"><h5 class="text-muted font-weight-bold mb-0">Email</h5></th>
                    <th class="text-center"><h5 class="text-muted font-weight-bold mb-0">Exchanges Number</h5></th>
                    <th class="text-center"></th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($users as $user)
                  <tr>
                    <th class="text-center">{{$user->name}}</th>
                    <td class="text-center">{{$user->email}}</td>
                    <td class="text-center">{{$user->assignedExchanges->count()}}</td>
                    <td class="text-center"><a href="{{URL::to('trader-details/'.$user->id)}}">Details</a></td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
          </div>

          <div class="d-flex justify-content-center mt-3">
              {{ $users->links() }}
          </div>
      </div>
  </section>

@endsection
