@extends('layouts.master')

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
<div class="container">
    <div class="row py-4">
        <div class="col-md-5 d-none d-sm-block">
            <div class="client-logo">
              <img src="{{asset('tempage/assets/img/manUser.jpg')}}" class="img-fluid w-100" alt="" data-aos="flip-right">
            </div>
        </div>

        <div class="col-md-7">
            <div data-aos="fade-left">
                <h3 class="section-bg p-2">จัดการสมาชิก
                <button type="button" class="btn btn-sm btn-primary" 
                  data-bs-toggle="modal" data-bs-target='#popup' onclick="popup_add()">เพิ่ม</button>
                </h3>
                
                <table id="myTable" class="table table-bordered table-striped table-reponsive table-sm">
                    <thead class="bg-gradient-blue">
                        <tr>
                            <th>ID</th>
                            <th>ชื่อ</th>
                            <th style="width: 50px;" class="small">พัสดุ</th>
                            <th style="width: 50px;" class="small">รายงาน</th>
                            <th style="width: 50px;" class="small">ตั้งต่า</th>
                            <th style="width: 80px;" class="small">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($data as $item)
                    <tr>
                      <td>{{$item->id}}</td>
                      <td>{{$item->name}}</td>
                      <td>
                        @if($item->role_parcel)
                        <i class="bi bi-check-lg"></i>
                        @endif
                      </td>
                      <td>
                        @if($item->role_report)
                        <i class="bi bi-check-lg"></i>
                        @endif
                      </td>
                      <td>
                        @if($item->role_setting)
                        <i class="bi bi-check-lg"></i>
                        @endif  
                      </td>
                      <td>

                      <button class='btn btn-sm btn-warning' title='แก้ไขข้อมูล' 
                        data-bs-toggle="modal" data-bs-target='#popup' 
                        onclick="popup_update('{{$item->id}}', '{{$item->name}}', '{{$item->role_parcel}}', '{{$item->role_report}}', '{{$item->role_setting}}')">
                        <i class="bi bi-pencil-square"></i>
                      </button>
                      <button class='btn btn-sm btn-danger' title='ลบข้อมูล'
                          onclick="popup_delete('{{$item->id}}', '{{$item->name}}')">
                          <i class="bi bi-trash3"></i>
                      </button>
                      </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
    </div>
</div>
@endsection

@section('modal')
<div class="modal fade" id="popup" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="popup_header"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="post" action="" id="formPopup">
          {{csrf_field()}}
        
          <span>เลขบัตรประชาชน</span>
          <div class="input-group mb-3" id="area_cid">
            <input type="text" class="form-control" name="cid" id='cid' maxlength="13" 
              OnKeyPress="return chkNumber(this)" required>
            <div class="input-group-append">
              <button class="btn btn-outline-secondary" type="button" onclick="fn_find_cid()">ค้นหา</button>
            </div>
          </div>

          <div id="massage_error" class="text-danger"></div>

          <div id="area_detail" style="display: none;">
          <span>ชื่อ-นามสกุล</span>
          <input type="text" class="form-control" name="name" id="name" readonly>

          <span>สิทธิ์</span>
            <div class="border p-3 rounded">
            <label><input type="checkbox" name="role_parcel" id="role_parcel" value="1" class="mr-3">จัดการพัสดุ</label><br>
            <label><input type="checkbox" name="role_report" id="role_report" value="1" class="mr-3">รายงาน</label><br>
            <label><input type="checkbox" name="role_setting" id="role_setting" value="1" class="mr-3">ตั้งค่าระบบ</label>
            </div>

          <br>
          <button type="submit" class="btn btn-block btn-primary" id="btn_submit">ยืนยัน</button>
          <input type="reset" id="btn_reset" style="display: none;">
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
$(function() {
    $("#myTable").DataTable({
      "language": {
            "lengthMenu": "แสดง _MENU_ ข้อมูล/หน้า",
            "zeroRecords": "ไม่มีข้อมูล",
            "info": "หน้า _PAGE_ / _PAGES_",
            "infoEmpty": "",
            "infoFiltered": "(กรองจากข้อมูลทั้งหมด _MAX_ )"
        },
        order: [[2, 'desc']],
    });
  });

  function popup_add() {
    $("#area_detail").hide();
    $("#popup_header").html("เพิ่มผู้ใช้งานระบบ");
    $("#btn_submit").attr("class","btn btn-block btn-primary");
    $("#formPopup").attr("action", "{{ Route('setting.user.add') }}");
  }

  function popup_update(id, name, p,r,s ) {
    $("#area_detail").show();
    $("#popup_header").html("แก้ไขผู้ใช้งานระบบ");
    $("#btn_submit").attr("class","btn btn-block btn-warning");
    $("#formPopup").attr("action", "{{ Route('setting.user.update') }}"+"/"+id);

    $("#cid").val( id );
    $("#name").val( name );

    if( p==1 ) {
      $("#role_parcel").click();
    }
    if( r==1 ) {
      $("#role_report").click();
    }
    if( s==1 ) {
      $("#role_setting").click();
    }
  }

  function popup_delete(cid, name) {
    Swal.fire({
      title: 'ยืนยันการลบ',
      text: name,
      confirmButtonText: 'ยืนยันการลบ',
      confirmButtonColor: '#dc3545',
      showCancelButton: true,
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = "{{Route('setting.user.delete')}}"+"/"+cid;
      }
    })
  }


  function fn_find_cid() {
    var cid = $("#cid").val();
    if( cid.length==13 ) {

        $.ajax({
          url: "{{route('user_detail')}}"+"/"+cid,
          success:function(response){
            if(response) {
              if( response['data'] != "[]" ) {
                var obj = JSON.parse(response['data']);
                //-------------------------
                $("#area_detail").show();
                $("#massage_error").html('');
                $("#name").val(obj['user_name']);
                //-------------------------
              }else{
                $("#btn_reset").click();
                $("#area_detail").hide();
                $("#massage_error").html('ไม่พบพนักงานที่ท่านค้นหา');
              }
            }
          },
        });
      
    }
  }

  function chkNumber(ele)
  {
    var vchar = String.fromCharCode(event.keyCode);
    if ((vchar<'0' || vchar>'9') && (vchar != '.')) return false;
    ele.onKeyPress=vchar;
  }

  
</script>
@endsection