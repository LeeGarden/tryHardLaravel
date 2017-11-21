@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"><h3 class="panel-title">Upload Multi Image</h3></div>

                <div class="panel-body">
                    <form method="post">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                      <div class="form-group">
                        <label for="images">Select Multi Images:</label>
                        <input type="file" class="form-control" id="images" name="image[]" accept="image/*" multiple>
                      </div>
                      <button type="submit" class="btn btn-default">Upload</button>
                    </form>
                    @if(count($errors)>0)
                        <div class="alert alert-danger col-sm-8" >
                            <ul>
                                @foreach($errors->all() as $error)
                                <li><strong>Error! </strong>{!! $error !!}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
