@extends('layouts.master')

@section('css')
<style>
  .cricle-img {
    border: 6px solid rgba(0, 0, 0, 0.15);
    margin: 0px auto;
  }
  img {
    object-fit: cover;
    object-position: center;
    width: 200px !important;
    height: 200px !important;
  }
  .zoomable {
    object-fit: fill;
    object-position: center;
    width: 100% !important;
    height: 100% !important;
  }

  .portfolio .portfolio-wrap {
      text-align: center;
  }
  .barcode {
    width: 100%;
  }
</style>
@endsection

@section('content')
<section id="portfolio" class="portfolio section-bg">
      <div class="container">

        <div class="section-bg" data-aos="fade-left">
            <div class="row">
                <div class="col-md-8">
                    <h3 class="ps-3">รายการพัสดุ</h3>
                </div>
                <div class="col-md-4">
                    <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="คำค้น" id="key" value="{{$key}}">
                    <span class="input-group-text" onclick="search()"><i class="bi bi-search"></i></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-4">
            <div class="row" id="area">
              @if( count($data) > 0 )
                @foreach( $data as $item )
                  <?php
                  if(empty($item->pic)) {
                    $src=asset('assets/img/blank.png');
                  }else{
                    $src=asset('assets/parcel')."/".$item->pic;
                  }

                  // |00000000|000|
                  // ID => |หมวด|รหัสของ|
                  $gen_code = $item->parcel_id.$item->code;
                  ?>
                  <div class="col-xl-3 col-md-4 col-sm-6 portfolio-item filter-app">
                    <div class="portfolio-wrap bg-white">
                      <span class="cricle-img"><img src="{{$src}}" class="img-fluid" alt=""></span>
                      <div class="portfolio-links">
                        <a href="{{$src}}" data-gallery="portfolioGallery" class="portfolio-lightbox" 
                        title="{{ $gen_code }}<br>{{ $item->name }}">
                        
                        <i class="bi bi-plus fs-1" onclick="fn_goto('{{$item->id}}')"></i>
                      
                        </a>
                      </div>
                      <div align="center">
                        <h4>{{ number_format($item->balance) }} {{$item->unit}}</h4>
                          
                          <svg class="barcode"
                            jsbarcode-format="upc"
                            jsbarcode-height="40"
                            jsbarcode-textmargin="0"
                            jsbarcode-value="{{ $gen_code}}"
                          >
                          </svg>
                          <p>{{ $item->name }}</p>
                      </div>
                    </div>
                  </div>
                  @endforeach
                @else
                  <div class="text-danger fs-1 text-center" data-aos="fade-up">ไม่พบข้อมูล</div>
                @endif

            </div>
        </div>
      </div>
    </section>
    
@endsection

@section('js')
<script src="{{asset('assets/js/JsBarcode.all.min.js')}}"></script>
<script>
  var input = document.getElementById("key");
  input.addEventListener("keypress", function(event) {
  if (event.key === "Enter") {
    event.preventDefault();
    search();
  }
});
  function search() {
    let = key = $("#key").val();
    window.location.href = "{{ Route('home') }}"+"/"+key;
  }

  $( document ).ready(function() {
    JsBarcode(".barcode").init();
  });


  function fn_goto(id) {
    window.location.href = "{{ Route('report.one') }}"+"/"+id;
  }
</script>
@endsection