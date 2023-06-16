<?php
use App\CmsHelper as cms;
$curr_id = 0;
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
      <div class="col-md-5"><h3 class="ps-3">รายการรับเข้า</h3></div>
      <div class="col-md-4">
      </div>
      <div class="col-md-3">
        <div class="input-group">
        <span class="input-group-text bg-dark text-white">ปีงบประมาณ</span>
        <select class="form-select" id="fyear" onchange="search()">
        @for( $y=$nowyear ; $y>=$oldyear ; $y-- )
          <option value="{{$y}}" <?=(($y==$curryear)?"selected":"")?> >{{$y}}</option>
        @endfor
        </select>
        </div>
      </div>
    </div>
  </div>

  <div class="py-3" data-aos="fade-up">
    <div class="card p-3">
      <div class="row">
        <div class="col-md-3">
          <h5 class="p-2 bg-dark text-white">รายการใบสั่งซื้อ</h5>
          <input type="text" id="searchFilter" placeholder="Search" class="form-control" 
            onkeyup="FilterItems(this.value);" />
          <select id="list_sel" class="form-select" multiple size = '12' onchange="fn_sel_list(this)">
          @foreach( $data as $x=>$item )
          <?php
          $sel = '';
          if( $x==0 ) {
            $sel = "selected";
            $curr_id = $item->id;
          }
          ?>
          <option value="{{$item->id}}" {{$sel}}>[{{ cms::DateThai($item->purchase_date, 'Y-m-d') }}]-{{$item->order_number}}</option>
          @endforeach
          </select>
        </div>
        <div class="col-md-9">

          <div class="table-responsive">
            <table id="myTable" class="table table-bordered table-sm">
                <thead class="bg-dark text-white">
                    <tr>
                    <th>id</th>
                    <th style="width: 60px;">รหัส</th>
                    <th>ชื่อวัสดุ</th>
                    <th style="width: 60px;">หน่วย</th>
                    <th style="width: 90px;">จำนวน</th>
                    <th style="width: 90px;">ราคา (บาท)</th>
                    <th style="width: 90px;">รวม (บาท)</th>
                    </tr>
                </thead>

                <tbody></tbody>

                <tfoot>
                  <tr class="bg-sky">
                    <td></td><td></td><td></td><td></td><td></td>
                    <td><div align="right">รวม</div></td>
                    <td></td>
                  </tr>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script src="{{asset('assets/js/dataTables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/js/dataTables/dataTables.bootstrap5.min.js')}}"></script>
<script src="{{asset('assets/js/dataTables/dataTables.buttons.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>

<script>
var tb;

$(function() {
  tb = $("#myTable").DataTable({
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
    ajax_tb("{{$curr_id}}");
  });

  var ddlText, ddlValue, ddl;
  function CacheItems() {
  ddlText = new Array();
  ddlValue = new Array();
  ddl = document.getElementById("list_sel");
  for (var i = 0; i < ddl.options.length; i++) {
      ddlText[ddlText.length] = ddl.options[i].text;
      ddlValue[ddlValue.length] = ddl.options[i].value;
  }
  }
  window.onload = CacheItems;

  function FilterItems(value) {
    //กรณียิง barcode
    ddl.options.length = 0;
    for (var i = 0; i < ddlText.length; i++) {
        if (ddlText[i].toLowerCase().indexOf(value) != -1 || ddlText[i].toUpperCase().indexOf(value) != -1) {
            AddItem(ddlText[i], ddlValue[i]);
        }
    }
  }

  function fn_sel_list() {
    let id = $("#list_sel").val();
    ajax_tb(id);
  }
  function ajax_tb(id) {
    let store_id = parseInt(id);
    tb.clear().draw();
    $.ajax({
      url: "{{route('report.in.ajax')}}"+"/"+store_id,
      success:function(response){
        if(response) {
          if( response['data'] != "[]" ) {
            var obj = JSON.parse(response['data']);
            //-------------------------
            for (const [key, value] of Object.entries(obj)) {
              let data_row = [];
              data_row.push(value['id']);
              data_row.push(value['code']);
              data_row.push(value['name']);
              data_row.push("<div align='center'>"+value['unit']+"</div>");
              data_row.push("<div align='center'>"+value['amount']+"</div>");
              data_row.push("<div align='right'>"+value['price']+"</div>");
              data_row.push("<div align='right'>"+value['sum']+"</div>");
              tb.row.add(data_row).draw();
            }
            //-------------------------
          }
        }
      },
    });
  }

  function search() {
    let fyear = $("#fyear").val();
    window.location.href = "{{ Route('report.in') }}"+"/"+fyear;
  }
</script>
@endsection

