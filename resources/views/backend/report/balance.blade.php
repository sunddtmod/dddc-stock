<?php
use App\CmsHelper as cms;
?>
@extends('layouts.master')

@section('css')
<link rel="stylesheet" href="{{ asset('assets/js/dataTables/css/dataTables.bootstrap5.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/js/dataTables/css/buttons.dataTables.min.css') }}">
<style>
    th, td {
        text-align: center !important;
    }
    th.l, td.l {
        text-align: left !important;
    }
    th.r, td.r {
        text-align: right !important;
    }
</style>
@endsection

@section('content')
<section id="portfolio" class="portfolio section-bg">
<div class="container">

  <div class="section-bg" data-aos="fade-left">
    <div class="row">
        <div class="col-md-12">
            <h3 class="ps-3">รายงานผลการตรวจนับวัสดุวัสดุคงคลัง ณ วันที่ {{ cms::DateThai(date('Y-m-d'), 'd-M-Y') }}</h3>
        </div>
    </div>
  </div>

  <div class="py-3" data-aos="fade-up">
    <div class="card p-3">
        <div class="table-responsive">
        <table id="myTable" class="table table-bordered table-sm">
            <thead class="bg-dark text-white">
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th colspan="3">รายงานผลการตรวจนับวัสดุคงคลัง</th>
                    <th></th>
                </tr>
                <tr>
                    <th>ลำดับ</th>
                    <th>หมวดพัสดุ</th>
                    <th>รายการ</th>
                    <th>หน่วยนับ</th>
                    <th>จำนวนคงเหลือ</th>
                    <th>ราคาต่อหน่วย</th>
                    <th>รวมมูลค่า</th>
                    <th>หมายเหตุ</th>
                </tr>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th>(หน่วย)</th>
                    <th>(บาท)</th>
                    <th>(บาท)</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                <?php
                    $sum_price = 0;
                ?>
                @foreach($data as $x=>$item)
                <?php
                    $cal_price = $item->balance * $item->price;
                    $sum_price += $cal_price;
                ?>
                <tr>
                    <td>{{ $x+1 }}</td>
                    <td>{{ $parcel_detail[$item->parcel_detail_id]['parcel_id'] }}</td>
                    <td class="l">{{ $parcel_detail[$item->parcel_detail_id]['name'] }}</td>
                    <td>{{ $parcel_detail[$item->parcel_detail_id]['unit'] }}</td>
                    <td>{{ number_format($item->balance) }}</td>
                    <td class="r">{{ number_format($item->price,2) }}</td>
                    <td class="r">{{ number_format($cal_price,2) }}</td>
                    <td></td>
                </tr>
                @endforeach
            </tbody>

            <tfoot>
                <tr class="bg-sky">
                    <th></th><th></th><th></th><th></th><th></th>
                    <th class="r">รวม</th>
                    <th class="r">{{ number_format($sum_price,2) }}</th>
                    <th></th>
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
$(function() {
    $("#myTable").DataTable({
      "language": {
            "lengthMenu": "แสดง _MENU_ ข้อมูล/หน้า",
            "zeroRecords": "ไม่มีข้อมูล",
            "info": "หน้า _PAGE_ / _PAGES_",
            "infoEmpty": "",
            "infoFiltered": "(กรองจากข้อมูลทั้งหมด _MAX_ )"
        },
      dom: 'Bfrtip',
      "buttons": ["excel"]
    });
  });

  $( document ).ready(function() {

  });
</script>
@endsection

