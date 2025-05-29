@extends('layouts.app')

@section('content')

            <div class="card">
                <div class="card-header">
                    <h4>Edit Banner</h4>
                </div>

                <div class="card-body">
                    <!-- Display Validation Errors -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Banner Edit Form -->
                    <form action="{{ route('medicinebanner.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                    
                        <div class="mb-3 error-msg">
                            <label for="image" class="form-label">Banner Image</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            <small class="text-muted">Allowed formats: jpeg, png, jpg</small>
                        </div>
                    
                        <div class="mb-3 error-msg">
                            <label for="priority" class="form-label">Priority</label>
                            <input type="number" name="priority" id="priority" class="form-control" value="{{ old('priority', $banner->priority ?? 0) }}" min="0">
                            <small class="text-muted">Higher priority banners appear first</small>
                        </div>
                    
                        <div class="mb-3">
                            <label for="current_image" class="form-label">Current Banner</label>
                            <div>
                                <img src="{{ asset('banners/' . $banner->image) }}" alt="Current Banner" class="img-fluid" style="max-width: 100%; height: auto;">
                            </div>
                        </div>
                    
                        <div class="modal-footer gap-2">
                            <button href="{{ route('medicinebanner.index') }}" class="btn btn-primary">Back to List</button>
                            <button type="submit" class="btn btn-primary">Update Banner</button>
                        </div>
                    </form>
                    
                    
                </div>
            </div>
        
@endsection
@section('scripts')
    <script src="{{ asset('js/homebanner/homebanner_form.js') }}"></script>
@endsection