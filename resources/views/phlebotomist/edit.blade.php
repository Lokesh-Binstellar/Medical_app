@extends('layouts.app')


@section('content')
    <div class="container">
        <div class="col-12">
            <div class="card">
                <h5 class="card-header">Update Phlebotomist</h5>
                <div class="card-body">
                    <form class="row g-3" action="{{ route('phlebotomist.update', $phlebotomist->id) }}" method="POST">
                        @csrf

                        @method('PUT')
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="text" name="name" value="{{ $phlebotomist->name }}" class="form-control"
                                    required />
                                <label for="name">Name</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="text" name="contact_number" value="{{ $phlebotomist->contact_number }}"
                                    class="form-control" required />
                                <label for="phone">Contact Number</label>
                            </div>
                        </div>


                        <div class="card-action">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <button href="{{ route('phlebotomist.index') }}" class="btn btn-primary">Back</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
