@extends('a.main')

@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon-small bg-malibu-beach">
                <i class="{{ $icon }} text-white">
                </i>
            </div>
            <div>{{ $title }}
            </div>
        </div>
        <div class="page-title-actions">
            <div class="d-inline-block dropdown">
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 mb-3">
        <div class="card p-3">
            @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
           @endif
            <form action="{{ url('roles/'.Formatter::encrypt0($role->id).'/give-permissions') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    @error('permission')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror

                    <label for="">Permissions</label>

                    <div class="row">
                        @foreach ($permissions as $permission)
                        <div class="col-md-12">
                            <label>
                                <input
                                    type="checkbox"
                                    name="permission[]"
                                    value="{{ $permission->name }}"
                                    {{ in_array($permission->id, $rolePermissions) ? 'checked':'' }}
                                />
                                {{ $permission->name }}
                            </label>
                        </div>
                        @endforeach
                    </div>

                </div>
                <div class="mb-3">
                    <a href="{{route('role.main')}}" class="btn btn-warning">Kembali</a>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>

    </div>
</div>


<script src="{{asset('assets/vendor/ckeditor5Document/ckeditor.js')}}"></script>
<script src="{{asset('assets/js/page/permissions/role.js')}}"></script>

@endsection

