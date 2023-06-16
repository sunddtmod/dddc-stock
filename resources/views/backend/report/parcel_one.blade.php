<?php
use App\CmsHelper as cms;
?>
@extends('layouts.master')

@section('css')
<link rel="stylesheet" href="{{ asset('assets/js/dataTables/css/dataTables.bootstrap5.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/js/dataTables/css/buttons.dataTables.min.css') }}">
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


    th, td {
        text-align: center !important;
    }
    td.r {
        text-align: right;
    }
</style>
@endsection

@section('content')
<section id="portfolio" class="portfolio section-bg">
<div class="container">


  <div class="section-bg" data-aos="fade-left">
    <div class="row">
      <div class="col-md-5"><h3 class="ps-3">รับเข้า-จ่ายออก รายวัสดุ</h3></div>
      <div class="col-md-3">
        <!-- <div class="input-group">
          <span class="input-group-text bg-dark text-white">เดือน</span>
          <input type="month" class="form-control" id="at_month" value="{{ date('Y-m') }}">
        </div> -->  
      </div>
      <div class="col-md-4">
        <div class="input-group">
        <span class="input-group-text bg-dark text-white">วัสดุ</span>
        <select class="form-select" id="store_id" onchange="search()">
            @foreach($parcel_detail_list as $id=>$list)
            <option value="{{$id}}" <?=(($id==$curr_id)?"selected":"")?> >{{$list}}</option>
            @endforeach
        </select>
        </div>
      </div>
    </div>
  </div>

  <div class="py-3" data-aos="fade-up">
    <div class="card p-3">
        <div class="row">
            <div class="col-md-3">
                <?php
                  if(empty($item->pic)) {
                    $src=asset('assets/img/blank.png');
                  }else{
                    $src=asset('assets/parcel')."/".$item->pic;
                  }

                  // |00000000|000|
                  // ID => |หมวด|รหัสของ|
                  // $gen_code = $item->parcel_id.$item->code;

                  $gen_code = $item->parcel_id.$item->code;
                ?>
              <div class="card" align="center">
                <span class="cricle-img"><img src="{{$src}}" class="img-fluid" alt=""></span>
                <div align="center">
                  <h4>{{ number_format($item->balance) }} {{$item->unit}}</h4>
                    
                    <svg class="barcode"
                      jsbarcode-format="upc"
                      jsbarcode-height="40"
                      jsbarcode-textmargin="0"
                      jsbarcode-value="{{ $gen_code }}"
                    >
                    </svg>
                    <p>{{ $item->name }}</p>
                </div>
              </div>
              <br><ul>
                <li>รหัสวัสดุ : {{ $item->parcel_id }}</li>
                <li>โค้ด : {{ $item->code }}</li>
                <li>หน่วย : {{ $item->unit }}</li>
                <li>ราคาต่อชิ้น : {{ number_format( $parcel_store->price , 2 ) }} บาท</li>
              </ul>

            </div>
            <div class="col-md-9">
                <div class="table-responsive">
                    <table id="myTable" class="table table-bordered table-sm">
                        <thead class="bg-dark text-white">
                            <tr>
                            <th>id</th>
                            <th>วันที่</th>
                            <th style="width: 90px;">เข้า</th>
                            <th style="width: 90px;">ออก</th>
                            <th style="width: 180px;">จำนวนคงเหลือ</th>
                            <th style="width: 180px;">มูลค่าคงเหลือ (บาท)</th>
                            </tr>
                        </thead>

                        <tbody>
                          <?php
                            $in_sum = 0;
                            $out_sum = 0;
                            $price = $parcel_store->price;
                          ?>
                          @foreach( $data as $obj )
                          <?php
                              $in_num = '';
                              $out_num = '';
                              $amount = number_format(ABS($obj->amount));
                              $balance =  number_format($obj->balance);
                              $price_balance =  number_format( (($obj->balance)*$price) , 2 );

                              if( $obj->amount > 0) {
                                  $in_sum += $price;
                                  $in_num = $amount;
                              }else{
                                  $out_sum += $price;
                                  $out_num = "<span class='text-danger'>".$amount."</span>";
                              }
                          ?>

                          <tr>
                            <td>{{ $obj->id }}</td>
                            <td>{{ cms::DateThai($obj->created_at, 'd-m-Y') }}</td>
                            <td class="r">{!! $in_num !!}</td>
                            <td class="r">{!! $out_num !!}</td>
                            <td class="r">{!! $balance !!}</td>
                            <td class="r">{!! $price_balance !!}</td>
                          </tr>
                          @endforeach
                        </tbody>

                        <tfoot>
                        </tfoot>

                    </table>
                </div>
            </div>
        </div>

    </div>
  </div>
</div>
</section>

@endsection

@section('modal')
@endsection

@section('js')
<script src="{{asset('assets/js/JsBarcode.all.min.js')}}"></script>

<script src="{{asset('assets/js/dataTables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/js/dataTables/dataTables.bootstrap5.min.js')}}"></script>
<script src="{{asset('assets/js/dataTables/dataTables.buttons.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>


<script>

$(function() {
    $("#myTable").DataTable({
      "language": {
            "lengthMenu": "แสดง _MENU_ ข้อมูล/หน้า",
            "zeroRecords": "ไม่มีข้อมูล",
            "info": "หน้า _PAGE_ / _PAGES_",
            "infoEmpty": "",
            "infoFiltered": "(กรองจากข้อมูลทั้งหมด _MAX_ )"
        },
        "ordering": false,
        columnDefs: [
          {
              target: 0,
              visible: false,
          },
        ],
        dom: 'Bfrtip',"buttons": ["excel"]
    });
  });

  $( document ).ready(function() {
    JsBarcode(".barcode").init();
  });

  function search() {
    let store_id = $("#store_id").val();
    window.location.href = "{{ Route('report.one') }}"+"/"+store_id;
  }
</script>
@endsection

