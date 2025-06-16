@extends('layouts.app')

@section('content')
    <div class="card shadow-lg rounded">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Gomeds QR Code Details</h5>
            @if (auth()->user()->role->name != 'delivery_person')
                <a href="{{ route('upload_qr.index') }}" class="btn btn-light addButton">← Back to List</a>
            @endif
        </div>

        <div class="card-body text-center">
            @if ($uploadQR->qr_image)
                <img src="{{ asset('uploadQR/' . $uploadQR->qr_image) }}" alt="QR Code" class="img-fluid shadow-lg mb-4"
                    style="max-height: 400px;">
                <p class="text-muted">
                    <strong>Uploaded On:</strong> {{ $uploadQR->created_at->format('d M Y, h:i A') }}
                </p>
            @else
                <p class="text-danger">No QR Code found.</p>
            @endif
        </div>
    </div>
@endsection
