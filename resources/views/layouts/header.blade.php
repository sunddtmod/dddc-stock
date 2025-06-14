<!-- ======= Header ======= -->
<header id="header">
    <div class="container d-flex align-items-center justify-content-between">

      <div class="logo">
        <h1><a href="{{Route('home')}}">DDDC<span>-</span>STORE</a></h1>
      </div>

      <nav id="navbar" class="navbar">
        <ul>
          <li><a class="nav-link {{ Active::check('/') }} " href="{{Route('home')}}">หน้าหลัก</a></li>

          @canany(['parcel'])
          <li class="dropdown {{ Active::checkRoute('parcel.*') }}"><a href="#"><span>วัสดุ</span> <i class="bi bi-chevron-down"></i></a>
            <ul>
              <li><a href="{{ Route('parcel.in') }}">รับเข้า</a></li>
              <li><a href="{{ Route('parcel.out') }}">จ่ายออก</a></li>
              <li><a href="{{ Route('parcel.register') }}">ลงทะเบียนวัสดุใหม่</a></li>
            </ul>
          </li>
          @endcan

          @canany(['report'])
          <li class="dropdown {{ Active::checkRoute('report.*') }}"><a href="#"><span>รายงาน</span> <i class="bi bi-chevron-down"></i></a>
            <ul>
              <li><a href="{{ Route('report.balance') }}">รายงานวัสดุคงเหลือ</a></li>
              <li><a href="{{ Route('report.in') }}">รายงานรับเข้า</a></li>
              <li><a href="{{ Route('report.out') }}">รายงานจ่ายออก</a></li>
              <li><a href="{{ Route('report.date') }}">ประวัติทำรายการ-วันที่</a></li>
              <li><a href="{{ Route('report.person') }}">ประวัติทำรายการ-บุคคล</a></li>
              <li><a href="{{ Route('report.one') }}">ประวัติทำรายการ-วัสดุ</a></li>
            </ul>
          </li>
          @endcan

          @canany(['setting'])
          <li class="dropdown {{ Active::checkRoute('setting.*') }}"><a href="#"><span>ตั้งค่า</span> <i class="bi bi-chevron-down"></i></a>
            <ul>
              <li><a href="{{ Route('setting.area') }}">ที่เก็บ</a></li>
              <li><a href="{{ Route('setting.group') }}">หมวดพัสดุ</a></li>
              <li><a href="{{ Route('setting.user') }}">ผู้ใช้งานระบบ</a></li>
              <!-- <li><a href="{{ Route('setting.sign_list') }}">ชื่อผู้ลงนาม</a></li> -->
            </ul>
          </li>
          @endcan
          
          @if( !empty(Auth::user()) )
          <li class="dropdown {{ Active::checkRoute('user.*') }}"><a href="#"><span>{{ (Auth::user()->name) }}</span> <i class="bi bi-chevron-down"></i></a>
            <ul>
              <li><a href="{{route('keycloak.logout')}}">ออกจากระบบ</a></li>
            </ul>
          </li>
          @else
          <ul>
              <li><a href="{{route('keycloak.login')}}">login</a></li>
          </ul>
          @endif

        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav><!-- .navbar -->

    </div>
  </header><!-- End Header -->