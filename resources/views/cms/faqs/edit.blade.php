@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/katex.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/editor.css') }}" />
@endsection

@section('content')

<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Edit FAQs</h4>
    </div>
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="myForm" action="{{ route('cms.faqs.update', $faq->id) }}" method="POST">

            @csrf
            @method('PUT') {{-- 🔁 Important for update --}}
            
            <div class="mb-3">
                <label class="form-label fw-semibold">Question <span class="text-danger">*</span></label>
                <input type="text" name="question" class="form-control" value="{{ old('question', $faq->question) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Answer </label>
                <div id="editor" style="height: 300px;"></div>
                <input type="hidden" name="description" id="description" value="{{ old('description', $faq->description ?? '') }}">
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('cms.faqs.index') }}" class="btn btn-secondary addButton">Cancel</a>
        </form>
    </div>
</div>

@endsection

@section('scripts')
    <script src="{{ asset('assets/vendor/libs/quill/katex.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/quill/quill.js') }}"></script>
    <script>
           document.addEventListener('DOMContentLoaded', function () {
            var quill = new Quill('#editor', {
                theme: 'snow',
                modules: {
                    toolbar: [
                        [{ header: [1, 2, false] }],
                        ['bold', 'italic', 'underline'],
                        ['link', 'blockquote', 'code-block', 'image'],
                        [{ list: 'ordered' }, { list: 'bullet' }],
                         [{ align: [] }],
                    ]
                }
            });

            var descriptionInput = document.getElementById('description');
            if (descriptionInput.value) {
                quill.root.innerHTML = descriptionInput.value;
            }

            var form = document.getElementById('myForm');
            form.onsubmit = function (e) {
                descriptionInput.value = quill.root.innerHTML.trim();

                if (!quill.getText().trim()) {
                    e.preventDefault();
                    alert('Answer field is required.');
                    return false;
                }
            };
        });
    </script>
@endsection
