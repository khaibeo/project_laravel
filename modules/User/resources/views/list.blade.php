@extends('layouts.layout')

@section('title')
    ahihi
@endsection

@section('content')
    <h1>Nội dung trang</h1>
    <h2>Bài hát : {{ __('User::test.info', ['name' => 'khai']) }}</h2>
    <h3>User : {{$user->name}}</h3>
@endsection