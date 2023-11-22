@extends('layouts.dashboard')

@section('dashboard-content')

<div class="item">
    <h2>Add Conference</h2>
    <form>
        <div class="form-group">
            <label for="firstName">First Name</label>
            <input type="text" class="form-control" id="firstName" placeholder="Enter your first name">
        </div>
        <div class="form-group">
            <label for="lastName">Last Name</label>
            <input type="text" class="form-control" id="lastName" placeholder="Enter your last name">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

</div>




@endsection