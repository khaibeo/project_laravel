@extends('layouts.admin')

@section('content')
<a class="btn btn-primary mb-3" href="{{route('admin.categories.create')}}">Thêm</a>

@session('msg')
    <div class="alert alert-success">{{session('msg')}}</div>
@endsession

<table id="myTable" class="table table-bordered">
    <thead>
        <tr>
            <th>Tên</th>
            <th>Link</th>
            <th>Thời gian</th>
            <th>Sửa</th>
            <th>Xóa</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>Tên</th>
            <th>Link</th>
            <th>Thời gian</th>
            <th>Sửa</th>
            <th>Xóa</th>
        </tr>
    </tfoot>
</table>
@endsection

@include('parts.admin.delete')
@section('script')
<script>
    $(document).ready( function () {
    $('#myTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{route('admin.categories.data')}}",
        columns: [
            {data: "name"},
            {data: "link"},
            {data: "created_at"},
            {data: "edit"},
            {data: "delete"},
        ]
    });
} );
</script>
@endsection
