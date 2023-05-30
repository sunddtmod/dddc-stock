@extends('layouts.master')

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
<link
    href="https://cdn.jsdelivr.net/npm/dual-listbox/dist/dual-listbox.css"
    rel="stylesheet"
/>
@endsection

@section('content')
<div class="container">

<select class="select2" multiple>
    <option value="1">One</option>
    <option value="2">Two</option>
    <option value="3">Three</option>
</select>


</div>


@endsection

@section('modal')
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="{{asset('assets/js/dataTables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/js/dataTables/dataTables.bootstrap5.min.js')}}"></script>

<script src="https://cdn.jsdelivr.net/npm/dual-listbox/dist/dual-listbox.min.js"></script>

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



  let dlb2 = new DualListbox('.select2', {
        availableTitle:'Available numbers',
        selectedTitle: 'Selected numbers',
        addButtonText: '>',
        removeButtonText: '<',
        addAllButtonText: '>>',
        removeAllButtonText: '<<',
        searchPlaceholder: 'search numbers'
    });
    dlb2.addEventListener('added', function(event){
        console.log(event);
    });
    dlb2.addEventListener('removed', function(event){
        console.log(event);
    });
</script>
@endsection