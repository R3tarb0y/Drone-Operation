@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Approve Request</h1>

    <form action="{{ route('receive.storeApproval', $request->id) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="received_quantity">Jumlah Diterima</label>
            <input type="number" name="received_quantity" class="form-control" id="received_quantity" 
                   max="{{ $request->quantity - $request->received_quantity }}" required>
        </div>
        <button type="submit" class="btn btn-success">Submit</button>
    </form>
</div>
@endsection
