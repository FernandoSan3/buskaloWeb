@extends('frontend.layouts.app')

@section('title', app_name() . ' | ' . __('navs.general.home'))

@section('content')

<section class="works-sec serv-online mx term&conditions">
    <div class="container">
        <div class="heading">
            <span class="bottom-border"></span>
        </div>
        <div class="row" style="text-align: justify; font-size: 16px;">
            <div class="col-lg-12">
                {!! $terms->description_purchase !!}
                <div class="btns-div">
                    @if($user = Auth::user())
                        <button class="btn login-btn acceptbtn"> Aceptar </button>
                    @else
                        <button class="btn login-btn acceptbtnwthout"> Aceptar </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
<script> 
    $(document).ready(function() { 
        $(".acceptbtn").click(function() { 
            //if($(".term-ckeck").is(':checked')){
                sessionStorage.setItem("tc",1);
                var queryParams = new URLSearchParams(window.location.search);
                queryParams.set("approval_status", "1");
                window.location.href = "{{url('opportunities')}}"+"?"+queryParams;
            //} 
            // else{ 
            //     sessionStorage.setItem("tc",0);
            //     alert("Check box is Unchecked"); 
            // } 
        }); 
        
        $(".acceptbtnwthout").click(function() { 
           // if($(".term-ckeck").is(':checked')){
                //const queryString = window.location.search;
                var queryParams = new URLSearchParams(window.location.search);
                queryParams.set("approval_status", "1");
                sessionStorage.setItem("tc",1);
                window.location.href = "{{url('register/')}}"+"?"+queryParams+'&tc_approved=1';
            // } else { 
            //     sessionStorage.setItem("tc",0);
            //     alert("Check box is Unchecked"); 
            // }
        }); 
    });         
</script>

@endsection



