@extends('layouts.app')

@section('content')
<example :user="{{ auth()->user() }}"></example>
@endsection
