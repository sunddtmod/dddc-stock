@extends('layouts.master')

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
<div class="container py-3">
    
<div class="section-bg" data-aos="fade-left">
    <h3 class="ps-3">รายชื่อผู้ลงนาม</h3>
</div>
<div class="row bg-sky py-4 mt-4">

    <div class="col-md-4">
        <label>(1) ผู้เบิกวัสดุ</label>
        <div class="ms-2 p-2">
        <input type="text" class="form-control text-center" name="forerunner_name" placeholder="ชื่อ-สกุล">
        </div>
    </div>
    <div class="col-md-4">
        <label>(2) ผู้สั่งจ่าย</label>
        <div class="ms-2 p-2">
        <input type="text" class="form-control text-center" name="payer_name" placeholder="ชื่อ-สกุล">
        </div>
    </div>
    <div class="col-md-4">
        <label>(3) หัวหน้ากลุ่ม</label>
        <div class="ms-2 p-2">
        <input type="text" class="form-control text-center" name="leader_name" placeholder="ชื่อ-สกุล">
        </div>
    </div>

    <div class="col-md-4">
        <label>(4) หัวหน้าเจ้าหน้าที่พัสดุ</label>
        <div class="ms-2 p-2">
        <input type="text" class="form-control text-center" name="parcel_officer_name" placeholder="ชื่อ-สกุล">
        </div>
    </div>
    <div class="col-md-4">
        <label>(5) ผู้รับของ</label>
        <div class="ms-2 p-2">
        <input type="text" class="form-control text-center" name="consignee_name" placeholder="ชื่อ-สกุล">
        </div>
    </div>
    <div class="col-md-4">
        <label>(6) ผู้จ่ายวัสดุ</label>
        <div class="ms-2 p-2">
        <input type="text" class="form-control text-center" name="material_payer_name" placeholder="ชื่อ-สกุล">
        </div>
    </div>
</div>

<div class="col-md-12 text-center"><br>
    <button type="submit" id="btn_submit" class="fs-2 btn btn-primary w-50">บันทึก</button>
</div>

</div>
@endsection

@section('modal')
<!-- Modal -->
<div class="modal fade" id="popup" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="popup_header"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="post" action="" id="formPopup">
          @csrf
        <span>ID</span>
        <input type="text" class="form-control" name="id" id='curr_id' readonly>
        <span>NAME</span>
        <input type="text" class="form-control mb-2" name="name" id="name" required>
          
        <button type="submit" class="btn btn-block btn-primary" id="btn_submit">ยืนยัน</button>
        <input type="reset" id="btn_reset" style="display: none;">
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
        }
    });
  });

  function popup_add() {
    $("#popup_header").html("เพิ่ม");
    $("#btn_submit").attr("class","btn btn-block btn-primary");
    $("#formPopup").attr("action", "{{ Route('setting.area.add') }}");

    $("#curr_id").readonly = true;
  }
  function popup_edit(id, name) {
    $("#popup_header").html("แก้ไข");
    $("#btn_submit").attr("class","btn btn-block btn-warning");
    $("#formPopup").attr("action", "{{ Route('setting.area.update') }}"+"/"+id);

    $("#curr_id").readonly = false;
    $("#curr_id").val(id);
    $("#name").val(name);
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
        window.location.href = "{{ Route('setting.area.delete') }}"+"/"+id;
      }
    })
  }
</script>
@endsection