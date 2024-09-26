@extends('layouts.master')

@section('content')
@section('title')
@lang('translation.Form_Layouts')
@endsection @section('content')
@include('components.breadcum')
<div class="row">
    <div class="col-12">
    </div>
    <div class="card">
        <div class="card-body">
            @if(isset($data) && !empty($data))
            <form class="" name="main_form" id="main_form" method="post" action="{{route('admin.category.update',$data->id)}}">
                @method('PATCH')
                @else
                <form class="" name="main_form" id="main_form" method="post" action="{{route('admin.category.store')}}">
                    @endif
                    {!! get_error_html($errors) !!}
                    @csrf
                    <div class="row m-2">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Master Category Name</label>
                                <input type="text" name="mastercatName" class="form-control" value="{{$mastercat->name}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Master Category Description</label>
                                <input type="text" name="mastercatDesc" class="form-control" value="{{$mastercat->description}}">
                            </div>
                        </div>
                    </div>
                   
        </div>
        <div class="kt-portlet__foot">
            <div class=" ">
                <div class="row">
                    <div class="wd-sl-modalbtn">
                        <button type="submit" class="btn btn-primary waves-effect waves-light" id="save_changes">Submit</button>
                        <a href="{{route('admin.category.index')}}" id="close"><button type="button" class="btn btn-outline-secondary waves-effect">Cancel</button></a>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </div>
</div>
</div>
@endsection
@section('script')
<script>
    $(function() {

        $("#main_form").validate({

            rules: {
                title: {
                    required: true,

                },
                sequence: {
                    digits: true,

                },
            },
            messages: {
                title: {
                    required: "Please enter title",
                },
                sequence: {
                    digits: "Please enter only number",
                },

            },
            submitHandler: function(form) {
                addOverlay();
                form.submit();
            }
        });

    });
</script>
@endsection