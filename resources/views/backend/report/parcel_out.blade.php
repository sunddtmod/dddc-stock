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
      <div class="col-md-5"><h3 class="ps-3">รายการจ่ายออก</h3></div>
      <div class="col-md-4">
      </div>
      <div class="col-md-3">
        <div class="input-group">
        <span class="input-group-text bg-dark text-white">ปีงบประมาณ</span>
        <select class="form-select" id="fyear" onchange="search()">
        @for( $y=$nowyear ; $y>=$oldyear ; $y-- ) {
          <option value="{{$y}}" <?=(($y==$curryear)?"selected":"")?> >{{$y}}</option>
        @endfor
        </select>
        </div>
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
                </tr>
            </thead>

            <tbody>
            </tbody>

            <tfoot>
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
    let fyear = $("#fyear").val();
    window.location.href = "{{ Route('report.in') }}"+"/"+fyear;
  }
</script>
@endsection

