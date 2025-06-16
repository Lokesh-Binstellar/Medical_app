@extends('layouts.app')

@section('content')

    <div class="card">
        <div class="card-header">
            <h4>Edit QR Code</h4>
        </div>

        <div class="card-body">

            {{-- ✅ Validation Errors --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- ✅ Edit QR Form --}}
            <form action="{{ route('upload_qr.update', $uploadQR->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Upload New QR --}}
                <div class="mb-3">
                    <label for="qr_image" class="form-label">Upload New QR Code</label>
                    <input type="file" class="form-control" id="qr_image" name="qr_image" accept="image/*">
                    <small class="text-muted">Allowed formats: jpeg, png, jpg</small>
                </div>

                {{-- Show Current QR --}}
                <div class="mb-3">
                    <label class="form-label">Current QR Code</label>
                    <div>
                        <img src="{{ asset('uploadQR/' . $uploadQR->qr_image) }}" alt="Current QR Code" class="img-fluid"
                            style="max-width: 250px; height: auto;">
                    </div>
                </div>

                {{-- Actions --}}
                <div class="d-flex gap-2">
                    <a href="{{ route('upload_qr.index') }}" class="btn btn-secondary">Back to List</a>
                    <button type="submit" class="btn btn-primary">Update QR Code</button>
                </div>

            </form>

        </div>
    </div>

@endsection
