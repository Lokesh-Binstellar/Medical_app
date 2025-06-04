@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/katex.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/editor.css') }}" />
@endsection

@section('content')

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Add Privacy Policy</h4>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

           
            <form id="myForm" action="{{ route('cms.privacy-policies.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Description</label>
                    <div id="editor" style="height: 300px;"></div>
                    <input type="hidden" name="description" id="description" value="{{ old('description') }}">
                </div>

                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{ route('cms.privacy-policies.index') }}" class="btn btn-secondary">Cancel</a>
            </form>

        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('assets/vendor/libs/quill/katex.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/quill/quill.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var quill = new Quill('#editor', {
                theme: 'snow',
                modules: {
                    toolbar: [
                        [{
                            header: [1, 2, false]
                        }],
                        ['bold', 'italic', 'underline'],
                        ['link', 'blockquote', 'code-block', 'image'],
                        [{
                            list: 'ordered'
                        }, {
                            list: 'bullet'
                        }]
                    ]
                }
            });

            var form = document.getElementById('myForm');
            var descriptionInput = document.getElementById('description');

            // Prefill quill editor if old or existing description exists
            if (descriptionInput.value) {
                quill.root.innerHTML = descriptionInput.value;
            }

            form.addEventListener('submit', function(e) {
                descriptionInput.value = quill.root.innerHTML.trim();

                if (!quill.getText().trim()) {
                    e.preventDefault();
                    alert('Description field is required.');
                    return false;
                }
            });
        });
    </script>
@endsection
