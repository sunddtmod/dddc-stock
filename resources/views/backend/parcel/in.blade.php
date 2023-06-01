@extends('layouts.master')

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">

<style>
    .dual-listbox__available {
        width: 27vw !important;
    }
    @media only screen and (max-width: 600px) {
        .dual-listbox__available {
        width: 100vw !important;
        }
    }

    th {
        text-align: center !important;
    }
    td .form-control {
        text-align: right;
    }

    .sel_chk::before {
        content: ' \2611';
        margin-right: 5px;
    }
    .bg-sky {
      background-color: lightskyblue;
    }
</style>
@endsection

@section('content')
<section id="portfolio" class="portfolio section-bg">
<form method="post" action="{{ Route('parcel.in.add') }}" id="formPopup">
@csrf

<div class="container">

  <div class="section-bg" data-aos="fade-left">
  <h3 class="ps-3">รับเข้า</h3>
  </div>

  <div class="py-3" data-aos="fade-up">
    <div class="bg-white p-2">
        <div class="row">
            <div class="col-md-4">
                <h5 class="p-2 bg-dark text-white">รายการวัสดุ</h5>
                <input type="text" id="searchFilter" placeholder="Search" class="form-control" onkeyup="FilterItems(this.value);" />
                <select class="form-control"  name="parcel_sel[]" id="parcel_sel" multiple size = 12 onchange="fn_parcel_sel(this)">
                @foreach( $data as $item )
                <option value="{{$item->id}}">{{$item->name}}</option>
                @endforeach
                </select>
            </div>
            <div class="col-md-8">


                <div class="row p-2 bg-dark text-white">
                    <div class="col-md-4">
                    <h5>รายการวัสดุที่เลือก</h5>
                    </div>
                    <div class="col-md-4">
                      <div class="input-group">
                        <span class="input-group-text bg-primary text-white">เลขที่ใบสั่งซื้อ</span>
                        <input type="text" class="form-control" placeholder="เลขที่ใบสั่งซื้อ" name="order_number" required>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="input-group">
                        <span class="input-group-text bg-primary text-white">วันที่ซื้อ</span>
                        <input type="date" class="form-control" placeholder="วันที่ซื้อ" name="purchase_date" required value="{{date('Y-m-d')}}">
                      </div>
                    </div>
                </div>


                <div class="table-responsive">
                <table id="myTable" class="table table-bordered table-sm">
                    <thead class="bg-gradient-blue">
                        <tr>
                        <th style="width: 60px;">รหัส</th>
                        <th>ชื่อวัสดุ</th>
                        <th style="width: 60px;">หน่วย</th>
                        <th style="width: 90px;">จำนวน</th>
                        <th style="width: 90px;">ราคา (บาท)</th>
                        <th style="width: 90px;">รวม (บาท)</th>
                        <th style="width: 60px;">ลบ</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot>
                        <tr>
                            <td></td><td></td><td></td><td></td>
                            <td><div align="right">รวม</div></td>
                            <td><input type='number' step='0.01' class='form-control' readonly id="num_sum_all"></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
                </div>

                <br>
                <button type="submit" class="w-100 fs-2 btn btn-primary">บันทึก</button>

            </div>
        </div>
    </div>
      
  </div>


</div>
</form>
</section>

@endsection

@section('modal')
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="{{asset('assets/js/dataTables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/js/dataTables/dataTables.bootstrap5.min.js')}}"></script>

@if(session()->has('Success'))
<script>
  Swal.fire({
    icon: 'success',
    title: "{{Session::get('Success')}}",
    showConfirmButton: false,
    timer: 1500
  })
</script>
@endif
@if(session()->has('Error'))
<script>
  Swal.fire({
    icon: 'warning',
    title: "{{Session::get('Error')}}",
    showConfirmButton: false,
    timer: 1500
  })
</script>
@endif

<script>
var obj_data = <?=json_encode($data, JSON_UNESCAPED_UNICODE)?>;
var row_data = [];
var tb;
var counter = 1;

$(function() {
    tb = $("#myTable").DataTable({
      "language": {
            "lengthMenu": "แสดง _MENU_ ข้อมูล/หน้า",
            "zeroRecords": "ไม่มีข้อมูล",
            "info": "หน้า _PAGE_ / _PAGES_",
            "infoEmpty": "",
            "infoFiltered": "(กรองจากข้อมูลทั้งหมด _MAX_ )"
        }
    });
  });

  $( document ).ready(function() {

  });

    var ddlText, ddlValue, ddl;
    function CacheItems() {
    ddlText = new Array();
    ddlValue = new Array();
    ddl = document.getElementById("parcel_sel");
    for (var i = 0; i < ddl.options.length; i++) {
        ddlText[ddlText.length] = ddl.options[i].text;
        ddlValue[ddlValue.length] = ddl.options[i].value;
    }
    }
    window.onload = CacheItems;

    function FilterItems(value) {
    ddl.options.length = 0;
    for (var i = 0; i < ddlText.length; i++) {
        if (ddlText[i].toLowerCase().indexOf(value) != -1 || ddlText[i].toUpperCase().indexOf(value) != -1) {
            AddItem(ddlText[i], ddlValue[i]);
        }
    }
    }

    function AddItem(text, value) {
    var opt = document.createElement("option");
    opt.text = text;
    opt.value = value;
    ddl.options.add(opt);
    }



  var parcel_select = [];
  function fn_parcel_sel() {
      let id = $("#parcel_sel").val();
      let val = $("#parcel_sel").children("option:selected").val();

      if( parcel_select.includes(val) ) {
        // $("#parcel_sel option:selected").removeClass("sel_chk");
        // let index = parcel_select.indexOf(val);
        // if (index !== -1) {
        //   parcel_select.splice(index, 1);
        // }
        
      }else{
        parcel_select.push(val);
        $("#parcel_sel option:selected").addClass("sel_chk");
        add_row(id);
      }
  }

  function add_row(id) {
    for (const [key, value] of Object.entries(obj_data)) {
        if( id==value['id'] ) {
            let code = "<input type='hidden' name=parcel_detail_id[] value='"+value['id']+"'>" + value['code'];
            let btn = "<button class='btn btn-sm btn-danger' title='ลบ' onclick='del_row("+id+")' id='btn"+id+"'>"
            + "<i class='bi bi-trash3'></i></button>";

            let data_row = [];
            data_row.push("<div align='center'>"+code+"</div>");
            data_row.push(value['name']);
            data_row.push("<div align='center'>"+value['unit']+"</div>");
            data_row.push("<input type='number' min=0 class='form-control' name='amount[]' onchange='calnum(this)'>");
            data_row.push("<input type='number' min=0 step='0.01' class='form-control' name='price[]' onchange='calnum(this)'>");
            data_row.push("<input type='number' min=0 step='0.01' class='calnum form-control' readonly>");
            data_row.push(btn);
            tb.row.add(data_row).draw();
        }
    };

    
  }
  function del_row(id) {
    let node = $("#btn"+id);
    tb.row( node.parents('tr') ).remove().draw();

    $("#parcel_sel").val(id);
    $("#parcel_sel option:selected").removeClass("sel_chk");
    let val = $("#parcel_sel").children("option:selected").val();
    let index = parcel_select.indexOf(val);
    if (index !== -1) {
        parcel_select.splice(index, 1);
    }

    calnumall();
}

   

    function calnum(that) {
        let node = $(that).parent().parent();
        let amount = node.find('input').eq(1).val();
        let price = node.find('input').eq(2).val();
        let sum = node.find('input').eq(3);
        if( amount && price ) {
            sum.val(amount*price);
            calnumall();
        }
    }
    function calnumall() {
        let sum = 0;
        var obj = document.getElementsByClassName("calnum");
        for (var i = 0; i < obj.length; i++) {
          sum += parseFloat($(obj[i]).val());
        }
        $("#num_sum_all").val(sum);
    }
    
</script>
@endsection

