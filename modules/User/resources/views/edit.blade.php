@extends('layouts.admin')

@section('content')
    <form action="" method="post">
        <div class="row">
            <div class="col-6">
                <div class="mb-3">
                    <label for="" class="form-label">Tên</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{old('name') ?? $user->name}}">
                    @error('name')
                        <div class="invalid-feedback">{{$message}}</div>
                    @enderror 
                </div>

                <div class="mb-3">
                    <label for="" class="form-label">Nhóm</label>
                    <select name="group_id" class="form-select @error('group_id') is-invalid @enderror">
                        <option value="0">--Chọn nhóm--</option>
                        <option value="1">Nhóm 1</option>
                    </select>
                    @error('group_id')
                        <div class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
            </div>

            <div class="col-6">
                <div class="mb-3">
                    <label for="" class="form-label">Email</label>
                    <input type="text" name="email" class="form-control @error('email') is-invalid @enderror" value="{{old('email') ?? $user->email}}">
                    @error('email')
                        <div class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="" class="form-label">Mật khẩu</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                    @error('password')
                        <div class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
            </div>
            @csrf   
            <div class="col-12">
                <button type="submit" class="btn btn-success">Lưu</button>
                <a href="{{route('admin.users.index')}}" class="btn btn-danger">Hủy</a>
            </div>
        </div>
    </form>
@endsection