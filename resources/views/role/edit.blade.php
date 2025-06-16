@extends('layouts.app')
@section('title', 'Update Role')

@section('styels')
@endsection
@section('content')

            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 page-header-title">Role Update</h5>
                </div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data" action="{{ route('roles.update', $role->id) }}">
                        @csrf
                        @method('put')
                        <div class="row">
                            <input type="hidden" name="id" value="{{ $role->id }}">

                            <div class="form-floating form-floating-outline mb-4">
                                <input type="text" class="form-control" name="name" id="role"
                                    value="{{ $role->name }}" required />
                                <label for="role">Role</label>
                                @error('role')
                                    <small class="red-text ml-10" role="alert">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>
                            <div class="input-field col-sm-12">
                                <div class="card">
                                    <h5 class="card-header">Permissions</h5>
                                    <div class="table-responsive text-nowrap">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Module Permission</th>
                                                    <th>Read</th>
                                                    {{-- <th>Write</th> --}}
                                                    <th>Create</th>
                                                    <th>Update</th>
                                                    <th>Delete</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($accessData as $key => $value)
                                                    @php
                                                        $data = $permissionData
                                                            ->where('module', $value)
                                                            ->where('role_id', $role->id)
                                                            ->first();
                                                        if (!empty($data)) {
                                                            $read = $data['read'] == 1 ? 'checked' : '';
                                                            // $write = $data['write'] == 1 ? 'checked' : '';
                                                            $create = $data['create'] == 1 ? 'checked' : '';
                                                            $update = $data['update'] == 1 ? 'checked' : '';
                                                            $delete = $data['delete'] == 1 ? 'checked' : '';
                                                        }
                                                    @endphp
                                                    @if (!empty($data) && $data['module'] == $value)
                                                        <tr>
                                                            <td>{{ $value }}</td>
                                                            <td>
                                                                <label class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        name="permission[{{ $key }}][read]"
                                                                        {{ $read }} />
                                                                    <span></span>
                                                                </label>
                                                            </td>
                                                            {{-- <td>
                                                                <label class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        name="permission[{{ $key }}][write]"
                                                                        {{ $write }} />
                                                                    <span></span>
                                                                </label>
                                                            </td> --}}
                                                            <td>
                                                                <label class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        name="permission[{{ $key }}][create]"
                                                                        {{ $create }} />
                                                                    <span></span>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        name="permission[{{ $key }}][update]"
                                                                        {{ $update }} />
                                                                    <span></span>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        name="permission[{{ $key }}][delete]"
                                                                        {{ $delete }} />
                                                                    <span></span>
                                                                </label>
                                                            </td>
                                                        </tr>
                                                    @else
                                                        <tr>
                                                            <td>{{ $value }}</td>
                                                            <td>
                                                                <label class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        name="permission[{{ $key }}][read]" />
                                                                    <span></span>
                                                                </label>
                                                            </td>
                                                            {{-- <td>
                                                                <label class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        name="permission[{{ $key }}][write]" />
                                                                    <span></span>
                                                                </label>
                                                            </td> --}}
                                                            <td>
                                                                <label class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        name="permission[{{ $key }}][create]" />
                                                                    <span></span>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        name="permission[{{ $key }}][update]" />
                                                                    <span></span>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        name="permission[{{ $key }}][delete]" />
                                                                    <span></span>
                                                                </label>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-6">
                                <button href="{{ route('roles.index') }}" class="btn btn-primary">Back</button>
                            </div>
                            <div class="col-6 mb-4 text-end">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        
@endsection
@section('scripts')
    <script>
        $('form').parsley();
    </script>
@endsection
