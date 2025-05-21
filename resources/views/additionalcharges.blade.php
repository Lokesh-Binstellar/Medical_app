@extends('layouts.app')

@section('styles')
@endsection
@section('content')

<div class="container mt-4">
    <div class="card shadow-xl rounded-3">
        <!-- Blue Header Title -->
        <div class="card-header bg-primary text-white fw-bold fs-5">
            Additional Charges
        </div>

        <!-- Card Body with Form -->
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success mt-3">
                    {{ session('success') }}
                </div>
            @endif
           <form action="{{ route('platform-fee.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="platfrom-fee" class="form-label">Platform Fee</label>
                    <input type="text" class="form-control" id="platfrom-fee" name="platfrom_fee" placeholder="Enter platform fee" value="{{ old('platfrom_fee', $charge->platfrom_fee ?? '') }}">
                </div>

                <button type="submit" class="btn btn-primary">Save Platform Fee</button>
            </form>
            
        </div>
    </div>
</div>

@endsection

