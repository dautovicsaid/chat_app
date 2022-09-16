@extends('layouts.user')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @error('msg')
                <div class="alert alert-danger">{{ $message }}</div> @enderror
                <div class="card">
                    <div class="card-header">People you may know</div>
                    <div class="card-body table-fluid">
                        <table class="table table-striped">
                            <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <div class="row">
                                        <td class="col-2">
                                            <div class="circular--user--icon">
                                                <img
                                                    src="{{$user->profile_picture}}" alt="user"
                                                />
                                            </div>
                                        </td>
                                        <td class="col-6">{{$user->name}}</td>
                                        <td class="col-2">
                                            <a href="{{route('users.show',["user" => $user->id])}}"
                                               class="btn btn-outline-primary"> Profile </a></td>
                                        <td>
                                        <td class="col-2">
                                            <form action="{{route('add_friend',['target_user'=> $user->id])}}"
                                                  method="POST">
                                                @csrf
                                                <button class="btn btn-outline-primary">
                                                    <i class="fas fa-user-plus"></i></button>
                                            </form>
                                        </td>
                                    </div>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="row mt-2 float-end">
                            <div class="col-12">
                                {{$users->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

