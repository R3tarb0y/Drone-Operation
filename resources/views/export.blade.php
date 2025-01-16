@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Export Requests</h2>
    <form action="{{ route('requests.export') }}" method="GET">
        <button type="submit" class="btn btn-primary">Export to Excel</button>
    </form>
</div>
@endsection
