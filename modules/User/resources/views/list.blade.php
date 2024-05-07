@extends('layouts.admin')

@section('content')
<a class="btn btn-primary mb-3" href="{{route('admin.users.add')}}">Thêm</a>
<table id="datatablesSimple">
    <thead>
        <tr>
            <th>Tên</th>
            <th>Email</th>
            <th>Nhóm</th>
            <th>Thời gian</th>
            <th>Sửa</th>
            <th>Xóa</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>Tên</th>
            <th>Email</th>
            <th>Nhóm</th>
            <th>Thời gian</th>
            <th>Sửa</th>
            <th>Xóa</th>
        </tr>
    </tfoot>
    <tbody>
        <tr>
            <td>Tiger Nixon</td>
            <td>System Architect</td>
            <td>Edinburgh</td>
            <td>61</td>
            <td>
                <a href="#" class="btn btn-warning">Sửa</a>
            </td>
            <td>
                <a href="#" class="btn btn-danger">Xóa</a>
            </td>
        </tr>
        <tr>
            <td>Garrett Winters</td>
            <td>Accountant</td>
            <td>Tokyo</td>
            <td>63</td>
            <td>
                <a href="#" class="btn btn-warning">Sửa</a>
            </td>
            <td>
                <a href="#" class="btn btn-danger">Xóa</a>
            </td>
        </tr>
    </tbody>
</table>
@endsection