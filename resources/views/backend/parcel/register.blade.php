@extends('layouts.master')

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
<style>
  .mypic img {
    max-width: 100%;
    max-height: 100%;
  }
  .mypic .square {
      height: 300px;
      width: 300px;
  }
  .mypic img {
      image-orientation: from-image;
  }
  .square2 {
      height: 80px;
      width: 80px;
  }
</style>
@endsection

@section('content')
<section id="portfolio" class="portfolio section-bg">
<div class="container">

        <div class="section-bg mb-4">
            <div class="row">
                <div class="col-md-8">
                    <h3 class="ps-3">ลงทะเบียนวัสดุใหม่
                    <button type="button" class="btn btn-primary" 
                        data-bs-toggle="modal" data-bs-target='#popup' onclick="popup_add()">เพิ่ม</button>
                    </h3>
                </div>
                <div class="col-md-4" align="right">
                    <div class="input-group mb-2">
                    <span class="input-group-text">หมวด</span>
                    <select class="form-select" id="group">
                        <option value="99999999">ทั้งหมด</option>
                        @foreach($group as $id=>$name)
                        <option value="{{$id}}">{{$name}}</option>
                        @endforeach
                    </select>
                    </div>
                </div>
            </div>
        </div>

        <div data-aos="fade-left">
        <table id="myTable" class="table table-bordered table-striped table-reponsive table-sm">
                    <thead class="bg-gradient-blue">
                        <tr>
                        <th>ภาพประกอบ</th>
                        <th>รหัส - ชื่อ</th>
                        <th>ชื่อ</th>
                        <th>คงเหลือ</th>
                        <th>หน่วยนับ</th>
                        <th>ที่เก็บ</th>
                        <th>แสดง</th>
                        <th style="width: 80px;">เมนู</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($data as $item)
                    <tr>
                      <td>
                        @if(empty($item->pic))
                        <img src="{{asset('assets/img/blank.png') }}" class="square">
                        @else
                        <img src="{{asset('assets/parcel') }}/{{$item->pic}}" class="square2">
                        @endif
                      </td>
                      <td>{{ $item->code }}</td>
                      <td>{{ $item->name }}</td>
                      <td align="right">
                        {{ number_format($item->balance) }}
                        @if( $item->red > 0 )
                        <br><div class="small">
                          <span class="text-danger">{{$item->red}}</span> : 
                          <span class="text-success">{{$item->green}}</span>
                        </div>
                        @endif
                      </td>
                      <td>{{ $item->unit }}</td>
                      <td>{{ $area[$item->area_id] }}</td>
                      <td>
                      <label>
                        <input type="checkbox" id="chk_{{ $item->id }}" onclick="fn_chk('{{ $item->id }}')" <?=( ($item->status==1)?"checked":"" )?>>
                        <span class="d-none">{{ $item->status }}</span>
                      </label>
                      </td>
                      <td>
                      <button class='btn btn-sm btn-warning' title='แก้ไขข้อมูล' 
                        data-bs-toggle="modal" data-bs-target='#popup' 
                        onclick="popup_edit('{{$item->id}}', '{{$item->name}}')">
                        <i class="bi bi-pencil-square"></i>
                      </button>
                      <button class='btn btn-sm btn-danger' title='ลบข้อมูล'
                          onclick="popup_del('{{$item->id}}', '{{$item->name}}')">
                          <i class="bi bi-trash3"></i>
                      </button>
                      </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>

        </div>

      </div>
</section>
@endsection

@section('modal')
<div class="modal fade" id="popup" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="popup_header"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form method="post" action="" id="formPopup" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="curr_id" id="curr_id"" value="0">
          <div class="row">
            <div class="col-md-4">
              <span>ภาพประกอบ</span><br>
              <input type="hidden" name="old_pic" id="old_pic">
              <img src="{{asset('assets/img/blank.png') }}" class="square mybtn border p-2" onclick="$('#image').click()" id="output" style="width: 200px;">
              <input type="file" name="image" id="image" accept="image/*" style="display: none;" onchange="loadFile(event)">
            </div>
            <div class="col-md-8">
              <div class="row">
                <div class="col-md-12">
                  <span>ประเภทวัสดุ</span><span class="text-danger"> * </span>
                  <select class="form-select" name="parcel_id" id="parcel_id" required onchange="auto_txt()">
                  <option value="">--โปรดระบุ--</option>
                  @foreach($group as $id=>$name)
                    <option value="{{$id}}">{{$id}} - {{$name}}</option>
                  @endforeach
                  </select>
                </div>

                <div class="col-md-12">
                  <span>รหัส</span><span class="text-danger"> * </span>
                  <input type="text" class="form-control" name="code" id="code" required>
                </div>
                <div class="col-md-12">
                  <span>ชื่อ</span><span class="text-danger"> * </span>
                  <input type="text" class="form-control" name="name" id="name" required>
                </div>
                <div class="col-md-12">
                  <span>คุณสมบัติ</span>
                  <input type="text" class="form-control" name="detail" id="detail">
                </div>

                <div class="col-md-6">
                  <span>หน่วยนับ</span><span class="text-danger"> * </span>
                  <input type="text" class="form-control" name="unit" id="unit" required value="ชิ้น" placeholder="เช่น ชิ้น, กล่อง, ขวด">
                </div>
                <div class="col-md-6">
                  <span>สถานที่เก็บ</span><span class="text-danger"> * </span>
                  <select class="form-select" name="area_id" id="area_id" required>
                  @foreach($area as $id=>$name)
                    <option value="{{$id}}">{{$name}}</option>
                  @endforeach
                  </select>
                </div>

                
                <div class="col-md-6">
                  <span class="bg-danger text-white px-2"> <= </span>
                  <input type="number" class="form-control" name="area_red" id="area_red" value="0">
                </div>
                <div class="col-md-6">
                  <span class="bg-success text-white px-2"> >= </span>
                  <input type="number" class="form-control" name="area_green" id="area_green"  value="0">
                </div>

                <div class="col-md-12">
                <br>
                <div class="d-grid">
                  <button type="submit" class="btn btn-primary" id="btn_submit"><h2>บันทึก</h2></button>
                </div>
                <input type="reset" id="btn_reset" style="display: none;">
                </div>

              </div>
            </div>
          </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
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
var obj = <?=json_encode($data, JSON_UNESCAPED_UNICODE)?>;

$(function() {
    $("#myTable").DataTable({
      "language": {
            "lengthMenu": "แสดง _MENU_ ข้อมูล/หน้า",
            "zeroRecords": "ไม่มีข้อมูล",
            "info": "หน้า _PAGE_ / _PAGES_",
            "infoEmpty": "",
            "infoFiltered": "(กรองจากข้อมูลทั้งหมด _MAX_ )"
        }
    });
  });

    var loadFile = function(event) {
    var output = document.getElementById('output');
    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function() {
      URL.revokeObjectURL(output.src) // free memory
    }
  };

  function auto_txt() {
    let code= $("#parcel_id").val();
    $("#code").val(code);
  }

  function popup_add() {
    $("#popup_header").html("เพิ่ม");
    $("#btn_submit").attr("class","btn btn-block btn-primary");
    $("#formPopup").attr("action", "{{ Route('parcel.add') }}");
  }
  function popup_edit(id) {
    $("#popup_header").html("แก้ไข");
    $("#btn_submit").attr("class","btn btn-block btn-warning");
    $("#formPopup").attr("action", "{{ Route('parcel.update') }}"+"/"+id);

    $("#curr_id").val(id);
    for (const [key, value] of Object.entries(obj)) {
      if( id==value['id'] ) {
        $("#parcel_id").val( value['parcel_id'] );
        $("#code").val( value['code'] );
        $("#name").val( value['name'] );
        $("#detail").val( value['detail'] );
        $("#unit").val( value['unit'] );
        $("#area_id").val( value['area_id'] );
        $("#red").val( value['red'] );
        $("#green").val( value['green'] );

        if(value['pic']) {
          $("#old_pic").val(value['pic']);
          $("#output").attr("src","{{asset('assets/parcel') }}"+"/"+value['pic']);
        }else{
          $("#old_pic").val("");
          $("#output").attr("src","{{asset('assets/img/blank.png') }}");
        }
        break;
      }
    }
  }
  function popup_del(id, name) {
    Swal.fire({
      title: 'ยืนยันการลบ',
      text: name,
      confirmButtonText: 'ยืนยันการลบ',
      confirmButtonColor: '#dc3545',
      showCancelButton: true,
      responsive: true,
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = "{{ Route('parcel.delete') }}"+"/"+id;
      }
    })
  }

  function fn_chk(id) {
      let chk = document.getElementById("chk_"+id).checked;
      let val = (chk)?1:0;

      $.ajax({
          url: "{{route('parcel.status')}}"+"/"+id+"/"+val,
          success:function(response){
              if(response) {
                Swal.fire({
                  icon: 'success',
                  title: 'แก้ไขสำเสร็จ',
                  showConfirmButton: false,
                  timer: 1500
                })
              }
          }
      });
  }
</script>
@endsection