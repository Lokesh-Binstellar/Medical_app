@extends('layouts.app')

@section('content')

    <div class="card shadow-lg rounded">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Banner Details</h5>
            <a href="{{ route('medicinebanner.index') }}" class="btn btn-primary addButton">← Back to List</a>
        </div>

        <div class="card-body text-center">
            @if($banner->image)
                <img src="{{ asset('banners/' . $banner->image) }}" alt="Banner Image" class="img-fluid shadow-lg mb-4" style="max-height: 400px;">
                <p class="text-muted"><strong>Uploaded On:</strong> {{ $banner->created_at->format('d M Y, h:i A') }}</p>
            @else
                <p class="text-danger">No image found for this banner.</p>
            @endif
        </div>
    </div>

@endsection
