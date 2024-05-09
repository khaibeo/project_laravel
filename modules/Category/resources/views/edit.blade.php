@extends('layouts.admin')

@section('content')
    <form action="" method="post">
        <div class="row">
            <div class="col-6">
                <div class="mb-3">
                    <label for="" class="form-label">Tên</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror title" value="{{old('name') ?? $category->name}}">
                    @error('name')
                        <div class="invalid-feedback">{{$message}}</div>
                    @enderror 
                </div>

                <div class="mb-3">
                    <label for="" class="form-label">Cha</label>
                    <select name="parent_id" class="form-select @error('parent_id') is-invalid @enderror">
                        <option value="0">Không</option>
                        <option value="1">Nhóm 1</option>
                    </select>
                    @error('parent_id')
                        <div class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
            </div>

            <div class="col-6">
                <div class="mb-3">
                    <label for="" class="form-label">Slug</label>
                    <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror slug" value="{{old('slug') ?? $category->slug}}">
                    @error('slug')
                        <div class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
            </div>
            @csrf   
            <div class="col-12">
                <button type="submit" class="btn btn-success">Lưu</button>
                <a href="{{route('admin.categories.index')}}" class="btn btn-danger">Hủy</a>
            </div>
        </div>
    </form>
@endsection