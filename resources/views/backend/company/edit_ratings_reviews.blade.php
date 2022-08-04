@extends('backend.layouts.app')

@section('title', __('labels.backend.company.management') . ' | ' . __('labels.backend.company.create'))

@section('content')
{{ html()->form('POST', route('admin.company.update_ratings_reviews'))->attribute('enctype', 'multipart/form-data')->class('form-horizontal')->open() }}
<input type="hidden" name="ratings_reviews_id" value="{{$ratings_reviews->id}}">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-5">
                    <h4 class="card-title mb-0">
                        @lang('labels.general.actions1.ratings_reviews_management') 
                        <small class="text-mgit uted">@lang('labels.general.actions1.edit_ratings_reviews')</small>
                    </h4>
                </div><!--col-->
            </div><!--row-->
            <hr>

            <div class="row mt-4">
                <div class="col">

                <div style="visibility:hidden">
                    <div class="col-md-10">
                        {{ html()
                            ->text('user_id')
                            ->value($ratings_reviews->user_id)
                            ->required() }}
                    </div>
                </div>


                <div class="form-group row">
                    <label class="col-md-2 form-control-label">@lang('labels.backend.review.table.User Name')</label>

                    <div class="col-md-10">
                        {{ html()->text('username')
                                    ->class('form-control')
                                    ->placeholder('username')
                                    ->attribute('maxlength', 191)
                                    ->value($total_service_requests->username)
                                    ->disabled()
                                    ->required()
                                    ->autofocus() }}
                    </div>
                    <!--col-->
                </div>
                <!--form-group-->

                <div class="form-group row">
                    <label class="col-md-2 form-control-label">@lang('labels.backend.review.table.Price')</label>

                    <div class="col-md-10">
                        {{ html()->text('price')
                                            ->class('form-control')
                                            ->placeholder('price')
                                            ->attribute('maxlength', 1)
                                            ->value($ratings_reviews->price)
                                            ->required()
                                            ->autofocus() }}
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-2 form-control-label">@lang('labels.backend.review.table.Puntuality')</label>

                    <div class="col-md-10">
                        {{ html()->text('puntuality')
                                    ->class('form-control')
                                    ->placeholder('puntuality')
                                    ->attribute('maxlength', 1)
                                    ->value($ratings_reviews->puntuality)
                                    ->required()
                                    ->autofocus() }}
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 form-control-label">@lang('labels.backend.review.table.Service')</label>

                    <div class="col-md-10">
                        {{ html()->text('service')
                                    ->class('form-control')
                                    ->placeholder('service')
                                    ->attribute('maxlength', 1)
                                    ->value($ratings_reviews->service)
                                    ->required()
                                    ->autofocus() }}
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 form-control-label">@lang('labels.backend.review.table.Quality')</label>

                    <div class="col-md-10">
                        {{ html()->text('quality')
                                    ->class('form-control')
                                    ->placeholder('quality')
                                    ->attribute('maxlength', 1)
                                    ->value($ratings_reviews->quality)
                                    ->required()
                                    ->autofocus() }}
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-2 form-control-label">@lang('labels.backend.review.table.Amiability')</label>

                    <div class="col-md-10">
                        {{ html()->text('amiability')
                                    ->class('form-control')
                                    ->placeholder('amiability')
                                    ->attribute('maxlength', 1)
                                    ->value($ratings_reviews->amiability)
                                    ->required()
                                    ->autofocus() }}
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-2 form-control-label">@lang('labels.backend.review.table.Review')</label>

                    <div class="col-md-10">
                        {{ html()->text('review')
                                    ->class('form-control')
                                    ->placeholder('review')
                                    ->attribute('maxlength', 191)
                                    ->value($ratings_reviews->review)
                                    ->required()
                                    ->autofocus() }}
                    </div>
                </div>


                </div><!--col-->
            </div><!--row-->

        </div><!--card-body-->

        <div class="card-footer">
            <div class="row">
                <div class="col">
                    {{ form_cancel(route('admin.company.ratings_reviews', $ratings_reviews->user_id), __('buttons.general.cancel')) }}
                </div><!--col-->

                <div class="col text-right">
                    {{ form_submit(__('buttons.general.crud.update')) }}
                </div><!--col-->
            </div><!--row-->
        </div><!--card-footer-->
    </div><!--card-->
{{ html()->form()->close() }}

<script type="text/javascript">
    $('#add_more').click(function(){

        var added_input = $(".ans").length;
        var input = '<div id="row_'+added_input+'" ><input type="text" name="ans[en]['+added_input+']" value="" class="ans form-control" required="" placeholder="write in English"> <input type="text" name="ans[es]['+added_input+']" required="" placeholder="write in Spanish" value="" class="form-control"> <button type="button" onclick="removeRow(this);"> Remove row</button></div>';
        $('#append_rows').append(input);
    });

    function removeRow($this) {

        $($this).parent('div').remove();
    }
</script>

<script type="text/javascript">
    $('#inWholeCountryTrue').click(function(){
     $('#proviencesArea').hide();
     $('#citiesArea').hide();
    });


    $('#inWholeCountryFalse').click(function(){
     $('#proviencesArea').show();
     $('#citiesArea').show();
    });

</script>

{{--  <script type="text/javascript">
    $(function () {
        $('#datetimepicker5').datetimepicker({
            defaultDate: "11/1/2013",
            disabledDates: [
                moment("12/25/2013"),
                new Date(2013, 11 - 1, 21),
                "11/22/2013 00:53"
            ]
        });
    });
</script> --}}
@endsection
