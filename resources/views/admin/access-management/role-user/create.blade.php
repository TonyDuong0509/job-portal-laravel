@extends('admin.layouts.master')

@section('contents')
    <section class="section">
        <div class="section-header">
            <h1>Roles Users</h1>
        </div>

        <div class="section-body">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h4>Create User</h4>
                        <a class="btn btn-primary ml-2" href="{{ route('admin.role-user.index') }}">
                            <- Back </a>
                    </div>
                    <div class="card-body p-0">
                        <form action="{{ route('admin.role-user.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="">Name</label>
                                <input type="text" class="form-control {{ \App\Helpers\hasError($errors, 'name') }}"
                                    value="{{ old('name') }}" name="name">
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>
                            <div class="form-group">
                                <label for="">Email</label>
                                <input type="email" class="form-control {{ \App\Helpers\hasError($errors, 'email') }}"
                                    value="{{ old('email') }}" name="email">
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>
                            <div class="form-group">
                                <label for="">Password</label>
                                <input type="password" class="form-control {{ \App\Helpers\hasError($errors, 'password') }}"
                                    name="password">
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>
                            <div class="form-group">
                                <label for="">Confirm Password</label>
                                <input type="password"
                                    class="form-control {{ \App\Helpers\hasError($errors, 'password_confirmation') }}"
                                    name="password_confirmation">
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                            </div>
                            <div class="form-group">
                                <label for="">Role</label>
                                <select name="role" class="form-control {{ \App\Helpers\hasError($errors, 'role') }}">
                                    <option value="">Select Role</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('role')" class="mt-2" />
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Create</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.checkAll').on('click', function() {
                let isChecked = $(this).is(':checked');
                $('input[name="permissions[]"]').prop('checked', isChecked);
            });
        });
    </script>
@endpush
