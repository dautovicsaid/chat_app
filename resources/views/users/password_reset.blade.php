@extends('layouts.user')
@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Change password</div>
                    <div class="card-body ">
                        <form class="" action="{{route('users.password_reset_update',['user' =>$user])}}"
                              enctype="multipart/form-data" method="post">

                            @csrf
                            @method('PATCH')
                            <div class="form-group mb-3">
                                <input type="password" class="form-control " name="password"
                                       placeholder="New password"
                                >
                                @error('password'){{$message}} @enderror
                            </div>
                            <div class="form-group mb-3">
                                <input type="password" class="form-control " name="password_confirmation"
                                       placeholder="Confirm password"
                                >
                                @error('password_confirmation'){{$message}} @enderror
                            </div>
                            <button class="btn btn-primary col-4 offset-4">Change password</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
