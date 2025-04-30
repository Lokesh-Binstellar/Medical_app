@extends('layouts.app')
@section('content')
    <div class="page-inner">
        @php
        use App\Models\Role;
        use App\Models\Permission;
        use App\Models\User;
    
        $loggedInUser = Auth::user();
        $permissions = [];
        $roleId = User::where('id', Auth::user()->id)->value('role_id');
        $data = Permission::where('role_id', $roleId)->pluck('module', 'id')->toArray();
        $permissions = array_unique($data);
    
        $isSuperAdmin = 0;
        if ($loggedInUser->role_id == 1) {
            $isSuperAdmin = 1;
        }
        // dd(Auth::user());
    @endphp
        <div class="row">
            <div class="col-sm-6 col-md-3  @if (!in_array('Roles', $permissions) &&  $isSuperAdmin != 1) d-none @endif">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-primary bubble-shadow-small">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Total User</p>
                                    <a href="{{ route('user.index') }}"></a>
                                    <h4 class="card-title">1,294</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="col-sm-6 col-md-3  @if (!in_array('Pharmacies', $permissions)) d-none @endif">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-success bubble-shadow-small">
                                    <i class="fas fa-luggage-cart"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Total laboratories</p>
                                    <h4 class="card-title">1,345</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- @php
                $chk = \App\Models\Permission::checkCRUDPermissionToUser('pharmacies', 'create');
                echo $chk;
            @endphp --}}
         
            <div class="col-sm-6 col-md-3  @if (!in_array('Laboratories', $permissions) &&  $isSuperAdmin != 1) d-none @endif">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-success bubble-shadow-small">
                                    <i class="fas fa-luggage-cart"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Total laboratories</p>
                                    <h4 class="card-title">1,345</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <img src="{{asset('storage/medicines/fNA5PHJZ4X_DRS000012_2.jpg')}}" alt=""> --}}
            {{-- order --}}
            {{-- <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-secondary bubble-shadow-small">
                                    <i class="far fa-check-circle"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Order</p>
                                    <h4 class="card-title">576</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
@endsection
