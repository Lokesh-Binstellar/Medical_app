<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- {{ __("You're logged in!") }} --}}
    
                    <div class="container">
                        <div class="flex justify-between">
    
                            <!-- Pharmacist Card -->
                            <div class="col-md-6" onclick="window.location='{{ url('/Pharmacist') }}'" style="cursor: pointer;">
                                <div class="card shadow-sm bg-success text-black" style="width: 350px; height: 200px; border-radius: 5px;">
                                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                        <h5 class="card-title">Pharmacist</h5>
                                        <h4 class="card-title">Total Pharmacist: {{ $pharmacyCount }}</h4>
                                    </div>
                                </div>
                            </div>
                            
    
                            <!-- Laboratory Card -->
                            <div class="col-md-6" onclick="window.location='{{ url('/laboratorie') }}'">
                                <div class="card shadow-sm">
                                    <div class="w-[350px] h-[200px] bg-[rgb(32,32,48)] text-black text-[18px] font-bold flex flex-col justify-center items-center rounded cursor-pointer" >
                                        <h5 class="card-title">Laboratory</h5>
                                        <h5 class="card-title">Total Laboratories: {{ $labCount }}</h5>
                                     
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6" onclick="window.location='{{ url('/users') }}'">
                                <div class="card shadow-sm">
                                    <div class="w-[350px] h-[200px] bg-[rgb(32,32,48)] text-black text-[18px] font-bold flex flex-col justify-center items-center rounded cursor-pointer">
                                        <h5 class="card-title">User</h5>
                                        <h5 class="card-title">Total User: {{ $userCount}}</h5>
                                     
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6" onclick="window.location='{{ url('/roles ') }}'">
                                <div class="card shadow-sm">
                                    <div class="w-[350px] h-[200px] bg-[rgb(32,32,48)] text-black text-[18px] font-bold flex flex-col justify-center items-center rounded cursor-pointer">
                                        <h5 class="card-title">Role</h5>
                                        <h5 class="card-title">Total Role: </h5>
                                     
                                    </div>
                                </div>
                            </div>
                        </div> 
                    </div> 
                 
            </div>
            
        </div>
        </div>
    </div>
    
</x-app-layout>
