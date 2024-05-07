@extends('layouts.admin')

@section('content')
    <form action="" method="post">
        <div class="row">
            <div class="col-6">
                <div class="mb-3">
                    <label for="" class="form-label">Tên</label>
                    <input type="text" name="name" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="" class="form-label">Nhóm</label>
                    <select name="group" class="form-select">

                    </select>
                </div>
            </div>

            <div class="col-6">
                <div class="mb-3">
                    <label for="" class="form-label">Email</label>
                    <input type="text" name="email" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="" class="form-label">Mật khẩu</label>
                    <input type="password" name="password" class="form-control">
                </div>
            </div>

            <div class="col-12">
                <button class="btn btn-success">Lưu</button>
                <a href="{{route('admin.users.index')}}" class="btn btn-danger">Hủy</a>
            </div>
        </div>


    </form>
@endsection