@extends('layouts.master')

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
<div class="container">
    <div class="row py-4">
        <div class="col-md-5 d-none d-sm-block">
            <div class="client-logo">
              <img src="{{asset('tempage/assets/img/parcelCategory.jpg')}}" class="img-fluid w-100" alt="" data-aos="flip-right">
            </div>
        </div>

        <div class="col-md-7">
            <div data-aos="fade-left">
                <h3 class="section-bg p-2">หมวดพัสดุ</h3>
                
                <table id="myTable" class="table table-bordered table-striped table-reponsive table-sm">
                    <thead class="bg-gradient-blue">
                        <tr>
                            <th style="width: 60px;">ID</th>
                            <th>NAME</th>
                            <th style="width: 80px;">STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($data as $item)
                    <tr>
                      <td>{{$item->id}}</td>
                      <td>{{$item->name}}</td>
                      <td>
                      <label>
                        <input type="checkbox" 
                        id="chk_{{$item->id}}"
                        onclick="fn_chk('{{$item->id}}')"
                        <?=( ($item->status==1)?"checked":"" )?>>
                        <span class="d-none">{{$item->status}}</span>
                      </label>
                      </div>

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
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="{{asset('assets/js/dataTables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/js/dataTables/dataTables.bootstrap5.min.js')}}"></script>

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


  function fn_chk(id) {
      let chk = document.getElementById("chk_"+id).checked;
      let val = (chk)?1:0;

      $.ajax({
          url: "{{route('setting.group.status')}}"+"/"+id+"/"+val,
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