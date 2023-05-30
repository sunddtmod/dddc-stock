<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>ระบบจัดการวัสดุ กองดิจิทัลเพื่อการควบคุมโรค</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  @include('layouts.css')
  @yield('css')
  <!-- =======================================================
  * Template Name: Bocor
  * Updated: Mar 10 2023 with Bootstrap v5.2.3
  * Template URL: https://bootstrapmade.com/bocor-bootstrap-template-nice-animation/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>
  @include('layouts.header')
  @include('layouts.hero')
  <main id="main">
  @yield('content')
  </main>
  @yield('modal')

  @include('layouts.footer')
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  @include('layouts.js')
  @yield('js')
</body>

</html>