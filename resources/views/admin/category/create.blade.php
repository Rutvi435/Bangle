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
                    <div class="mb-3 row">
                        <label for="example-text-input" class="col-md-2 col-form-label">Sequence</label>
                        <div class="col-md-10">
                            <input type="text" name="sequence" id="sequence" class="float-number form-control" value="{{($data->sequence) ?? 0}}">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="example-text-input" class="col-md-2 col-form-label"><span class="text-danger">*</span>Title</label>
                        <div class="col-md-10">
                            <input type="text" name="title" id="title" class="form-control" value="{{($data->title) ?? ''}}">
                        </div>
                    </div>
                   
                    <div class="kt-portlet__foot">
                        <div class=" ">
                            <div class="row">
                                <div class="wd-sl-modalbtn">
                                    <button type="submit" class="btn btn-primary waves-effect waves-light" id="save_changes">Submit</button>
                                    <a href="{{route('admin.goals.index')}}" id="close"><button type="button" class="btn btn-outline-secondary waves-effect">Cancel</button></a>

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
                     digits:"Please enter only number",
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