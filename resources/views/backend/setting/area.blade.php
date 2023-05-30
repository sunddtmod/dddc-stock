@extends('layouts.master')

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
<div class="container">
    <div class="row py-4">
        <div class="col-md-5 d-none d-sm-block">
            <div class="client-logo">
              <img src="{{asset('tempage/assets/img/services-7.jpg')}}" class="img-fluid w-100" alt="" data-aos="flip-right">
            </div>
        </div>

        <div class="col-md-7">
            <div data-aos="fade-left">
                <h3 class="section-bg p-2">สถานที่เก็บพัสดุ 
                <button type="button" class="btn btn-sm btn-primary" 
                  data-bs-toggle="modal" data-bs-target='#popup' onclick="popup_add()">เพิ่ม</button>
                </h3>
                
                <table id="myTable" class="table table-bordered table-striped table-reponsive table-sm">
                    <thead class="bg-gradient-blue">
                        <tr>
                        <th style="width: 60px;">ID</th>
                        <th>NAME</th>
                        <th style="width: 80px;">MENU</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($data as $item)
                    <tr>
                      <td>{{$item->id}}</td>
                      <td>{{$item->name}}</td>
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