@extends('layouts.coinbank-layout')

@section('content')
<profile-notification></profile-notification>
<section id="transactions" class="py-section-3">
      <div class="container">
          <div class="d-flex justify-content-between align-items-end border-bottom flex-wrap pb-3">
              <h6 class="text-primary font-weight-bold text-truncate mb-md-0">Lista de Perfiles por aprobar</h6>
          </div>
          <div class="card">
              <table class="table table-striped table-borderless mb-0">
                <thead>
                  <tr>
                    <th class="text-center"><h5 class="text-muted font-weight-bold mb-0">Name</h5></th>
                    <th class="text-center"><h5 class="text-muted font-weight-bold mb-0">Email</h5></th>
                    <th class="text-center"><h5 class="text-muted font-weight-bold mb-0">Profile</h5></th>
                    <th class="text-center"><h5 class="text-muted font-weight-bold mb-0">Client since</h5></th>
                    <th class="text-center"></th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($profiles as $profile)
                  <tr>
                    <th class="text-center">{{$profile->user->name}}</th>
                    <td class="text-center">{{$profile->user->email}}</td>
                    <td class="text-center">{{$profile->user->userProfile()}}</td>
                    <td class="text-center">{{$profile->user->created_at->format('d M Y')}}</td>
                    <td class="text-center"><a href="{{URL::to('user-profile/'.$profile->user_id)}}">View Profile</a></td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
          </div>

          <div class="d-flex justify-content-center mt-3">
              {{ $profiles->links() }}
          </div>
      </div>
  </section>

@endsection
