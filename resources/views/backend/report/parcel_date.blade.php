<?php
use App\CmsHelper as cms;
?>
@extends('layouts.master')

@section('css')
<link rel="stylesheet" href="{{ asset('assets/js/dataTables/css/dataTables.bootstrap5.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/js/dataTables/css/buttons.dataTables.min.css') }}">
<style>
    th {
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
      <div class="col-md-5"><h3 class="ps-3">รับเข้า-จ่ายออก ตามช่วงเวลา</h3></div>
      <div class="col-md-3">
        <div class="input-group">
        <span class="input-group-text bg-dark text-white">เริ่ม</span>
        <input type="date" class="form-control" id="start_date" value="{{ date('Y-m').'-01' }}">
        </div>
      </div>
      <div class="col-md-3">
        <div class="input-group">
        <span class="input-group-text bg-dark text-white">ถึงวันที่</span>
        <input type="date" class="form-control" id="end_date" value="{{ date('Y-m-d') }}">
        </div>
      </div>
      <div class="col-md-1">
        <button type="button" class="btn btn-primary" onclick="search()">ค้นหา</button>
      </div>
    </div>
  </div>

  <div class="py-3" data-aos="fade-up">
    <div class="card p-3">
        <div class="table-responsive">
        <table id="myTable" class="table table-bordered table-sm">
            <thead class="bg-dark text-white">
                <tr>
                <th>id</th>
                <th>วันที่</th>
                <th>รหัส</th>
                <th>ชื่อวัสดุ</th>
                <th style="width: 60px;">หน่วย</th>
                <th style="width: 90px;">เข้า</th>
                <th style="width: 90px;">ออก</th>
                <th style="width: 90px;">ราคา (บาท)</th>
                <th style="width: 90px;">รับเข้า (บาท)</th>
                <th style="width: 90px;">จ่ายออก (บาท)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $in_sum = 0;
                $out_sum = 0;
                ?>
                @foreach($data as $item)
                <?php
                    $in_num = '';
                    $out_num = '';
                    $in_price = '';
                    $out_price = '';
                    $amount = number_format(ABS($item->amount));

                    $price = ABS($item->amount * $parcel_store[$item->parcel_detail_id]);
                    $price_txt = number_format($price,2);
                    if( $item->amount > 0) {
                        $in_sum += $price;
                        $in_price = $price_txt;
                        $in_num = $amount;
                    }else{
                        $out_sum += $price;
                        $out_price = "<span class='text-danger'>".$price_txt."</span>";
                        $out_num = "<span class='text-danger'>".$amount."</span>";
                    }
                ?>
                <tr>
                <td>{{ $item->id }}</td>
                <td style="width: 90px;">{{ cms::DateThai($item->created_at) }}</td>
                <td>{{ $parcel_detail[$item->parcel_detail_id]['code'] }}</td>
                <td>{{ $parcel_detail[$item->parcel_detail_id]['name'] }}</td>
                <td style="width: 60px;">{{ $parcel_detail[$item->parcel_detail_id]['unit'] }}</td>
                <td class="r" style="width: 90px;">{!! $in_num !!}</td>
                <td class="r" style="width: 90px;">{!! $out_num !!}</td>
                <td class="r" style="width: 90px;">{{ number_format($parcel_store[$item->parcel_detail_id],2) }}</td>
                <td class="r" style="width: 90px;">{!! $in_price !!}</td>
                <td class="r" style="width: 90px;">{!! $out_price !!}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="bg-sky">
                    <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                    <td><div align="right">รวม</div></td>
                    <td><div align="right">{!! number_format($in_sum,2) !!}</div></td>
                    <td><div align="right">{!! "<span class='text-danger'>".number_format($out_sum,2)."</span>" !!}</div></td>
                </tr>
            </tfoot>
        </table>
        </div>
    </div>
  </div>
</div>
</section>

@endsection

@section('modal')
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script src="{{asset('assets/js/dataTables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/js/dataTables/dataTables.bootstrap5.min.js')}}"></script>
<script src="{{asset('assets/js/dataTables/dataTables.buttons.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>








<script>
var obj_data = <?=json_encode($data, JSON_UNESCAPED_UNICODE)?>;
var row_data = [];
var tb;
var counter = 0;

$(function() {
    $("#myTable").DataTable({
      "language": {
            "lengthMenu": "แสดง _MENU_ ข้อมูล/หน้า",
            "zeroRecords": "ไม่มีข้อมูล",
            "info": "หน้า _PAGE_ / _PAGES_",
            "infoEmpty": "",
            "infoFiltered": "(กรองจากข้อมูลทั้งหมด _MAX_ )"
        },
        pageLength : 20,
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

  });

  function search() {
    let d1 = $("#start_date").val();
    let d2 = $("#end_date").val();
    window.location.href = "{{ Route('report.date') }}"+"/"+d1+"/"+d2;
  }
</script>
@endsection

