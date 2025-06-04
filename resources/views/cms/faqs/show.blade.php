@extends('layouts.app')

@section('content')


<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">View FAQs</h4>
    </div>
    <div class="card-body">
     
           <div class="mb-3">
            <label class="form-label fw-semibold">Question</label>
            <div class="border p-3">{{ $faq->question }}</div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Answer</label>
            <div class="border p-3">{!! $faq->description !!}</div>
        </div>

            <a href="{{ route('cms.faqs.index') }}" class="btn btn-secondary addButton">Back to List</a>
        
    </div>
</div>
{{-- <div class="container mt-4">
    <h2>About Us Details</h2>

    <div class="mb-3">
        <strong>Description:</strong>
        <div class="border p-3">{!! $faq->description !!}</div>
    </div>

    <a href="{{ route('cms.faqs.index') }}" class="btn btn-secondary">Back to List</a>
    <a href="{{ route('cms.faqs.edit', $faq->id) }}" class="btn btn-warning">Edit</a>
</div> --}}
@endsection
