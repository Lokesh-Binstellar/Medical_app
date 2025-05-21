

@extends('layouts.app')


@section('content')
    <div class="container">
        <div class="col-12">
            <div class="card">
                <h5 class="card-header">Add Phlebotomist</h5>
                <div class="card-body">
                    <form class="row g-3" action="{{ route('phlebotomist.store') }}" method="POST">
                        @csrf


                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="text" name="name" class="form-control" required
                                    placeholder="Name" />
                                <label for="name">Name</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="tel" name="contact_number" class="form-control" required placeholder="Phone"
                                    pattern="^\d{7,12}$"
                                    oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,12)" />
                                <label for="phone">Contact Number</label>
                            </div>
                        </div>


                        <div class="card-action">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <button type="button" class="btn btn-primary"
                                onclick="window.location='{{ route('phlebotomist.index') }}'">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

