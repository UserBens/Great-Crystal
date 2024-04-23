<footer class="main-footer text-sm">
   <strong>Copyright &copy; 2023 <a target="_blank" href="https://great.sch.id/">Great Crystal School</a>.</strong>
   All rights reserved.
   <div class="float-right d-none d-sm-inline-block">
     <b>Version</b> 1.0
   </div>
 </footer>


 <!-- Control Sidebar -->
 <aside class="control-sidebar control-sidebar-dark">
   <!-- Control sidebar content goes here -->
 </aside>
 <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{asset('template')}}/plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{asset('template')}}/plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
 $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{asset('template')}}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="{{asset('template')}}/plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="{{asset('template')}}/plugins/sparklines/sparkline.js"></script>
<!-- jQuery Knob Chart -->
<script src="{{asset('template')}}/plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="{{asset('template')}}/plugins/moment/moment.min.js"></script>
<script src="{{asset('template')}}/plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{asset('template')}}/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="{{asset('template')}}/plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="{{asset('template')}}/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
{{-- <script src="{{asset('template')}}/dist/js/adminlte.js"></script> --}}
<!-- AdminLTE for demo purposes -->
<script src="{{asset('template')}}/dist/js/demo.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{asset('template')}}/dist/js/pages/dashboard.js"></script>

<!-- Select2 -->
<script src="{{asset('template')}}/plugins/select2/js/select2.full.min.js"></script>
<!-- Bootstrap4 Duallistbox -->
<script src="{{asset('template')}}/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>

<script src="{{asset('template')}}/plugins/inputmask/jquery.inputmask.min.js"></script>
<!-- bootstrap color picker -->
<script src="{{asset('template')}}/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>

<!-- Bootstrap Switch -->
<script src="{{asset('template')}}/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<!-- BS-Stepper -->
<script src="{{asset('template')}}/plugins/bs-stepper/js/bs-stepper.min.js"></script>
<!-- dropzonejs -->
<script src="{{asset('template')}}/plugins/dropzone/min/dropzone.min.js"></script>
<!-- AdminLTE App -->
<script src="{{asset('template')}}/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{asset('template')}}/dist/js/demo.js"></script>


<script>
   $(function () {
    
 
     //Datemask dd/mm/yyyy
     $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
     $('#datemaskMonth').inputmask('mm/yyyy', { 'placeholder': 'mm/yyyy' })
     //Datemask2 mm/dd/yyyy
     $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
     //Money Euro
     $('[data-mask]').inputmask()
 
     //Date picker
     $('#reservationdate').datetimepicker({
         format: 'DD/MM/YYYY'
     });
     $('#reservationdateStudentDateExp').datetimepicker({
         format: 'DD/MM/YYYY'
     });
     $('#reservationdateStudentDateReg').datetimepicker({
         format: 'DD/MM/YYYY'
     });
     $('#reservationFatherBirthDate').datetimepicker({
         format: 'DD/MM/YYYY'
     });
     $('#reservationMotherBirthDate').datetimepicker({
         format: 'DD/MM/YYYY'
     });
     $('#reservationMotherBirthDate').datetimepicker({
         format: 'DD/MM/YYYY'
     });
     $('#reservationMotherBirthDate').datetimepicker({
         format: 'DD/MM/YYYY'
     });
     $('#reservationBrotherOrSisterBirthDate1').datetimepicker({
         format: 'DD/MM/YYYY'
     });
     $('#reservationBrotherOrSisterBirthDate2').datetimepicker({
         format: 'DD/MM/YYYY'
     });
     $('#reservationBrotherOrSisterBirthDate3').datetimepicker({
         format: 'DD/MM/YYYY'
     });
     $('#reservationBrotherOrSisterBirthDate4').datetimepicker({
         format: 'DD/MM/YYYY'
     });
     $('#reservationBrotherOrSisterBirthDate5').datetimepicker({
         format: 'DD/MM/YYYY'
     });
     $('#reservationBillFrom').datetimepicker({
         format: 'DD/MM/YYYY'
     });
     $('#reservationBillTo').datetimepicker({
         format: 'DD/MM/YYYY'
     });
     $('#reservationReportBillFrom').datetimepicker({
         format: 'MM/YYYY'
     });
     $('#reservationReportBillTo').datetimepicker({
         format: 'MM/YYYY'
     });
     
     //Date and time picker
     $('#reservationdatetime').datetimepicker({ icons: { time: 'far fa-clock' } });
 
     //Date range picker
     $('#reservation').daterangepicker()
     //Date range picker with time picker
     $('#reservationtime').daterangepicker({
       timePicker: true,
       timePickerIncrement: 30,
       locale: {
         format: 'MM/DD/YYYY hh:mm A'
       }
     })
     //Date range as a button
     $('#daterange-btn').daterangepicker(
       {
         ranges   : {
           'Today'       : [moment(), moment()],
           'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month'  : [moment().startOf('month'), moment().endOf('month')],
           'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
         },
         startDate: moment().subtract(29, 'days'),
         endDate  : moment()
       },
       function (start, end) {
         $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
       }
     )
 
     //Timepicker
     $('#timepicker').datetimepicker({
       format: 'LT'
     })
 
     //Bootstrap Duallistbox
     $('.duallistbox').bootstrapDualListbox()
 
     //Colorpicker
     $('.my-colorpicker1').colorpicker()
     //color picker with addon
     $('.my-colorpicker2').colorpicker()
 
     $('.my-colorpicker2').on('colorpickerChange', function(event) {
       $('.my-colorpicker2 .fa-square').css('color', event.color.toString());
     })
 
     $("input[data-bootstrap-switch]").each(function(){
       $(this).bootstrapSwitch('state', $(this).prop('checked'));
     })
 
   })
   // BS-Stepper Init
   document.addEventListener('DOMContentLoaded', function () {
     window.stepper = new Stepper(document.querySelector('.bs-stepper'))
   })
 
   // DropzoneJS Demo Code Start
   Dropzone.autoDiscover = false
 
   // Get the template HTML and remove it from the doumenthe template HTML and remove it from the doument
   var previewNode = document.querySelector("#template")
   previewNode.id = ""
   var previewTemplate = previewNode.parentNode.innerHTML
   previewNode.parentNode.removeChild(previewNode)
 
   var myDropzone = new Dropzone(document.body, { // Make the whole body a dropzone
     url: "/target-url", // Set the url
     thumbnailWidth: 80,
     thumbnailHeight: 80,
     parallelUploads: 20,
     previewTemplate: previewTemplate,
     autoQueue: false, // Make sure the files aren't queued until manually added
     previewsContainer: "#previews", // Define the container to display the previews
     clickable: ".fileinput-button" // Define the element that should be used as click trigger to select files.
   })
 
   myDropzone.on("addedfile", function(file) {
     // Hookup the start button
     file.previewElement.querySelector(".start").onclick = function() { myDropzone.enqueueFile(file) }
   })
 
   // Update the total progress bar
   myDropzone.on("totaluploadprogress", function(progress) {
     document.querySelector("#total-progress .progress-bar").style.width = progress + "%"
   })
 
   myDropzone.on("sending", function(file) {
     // Show the total progress bar when upload starts
     document.querySelector("#total-progress").style.opacity = "1"
     // And disable the start button
     file.previewElement.querySelector(".start").setAttribute("disabled", "disabled")
   })
 
   // Hide the total progress bar when nothing's uploading anymore
   myDropzone.on("queuecomplete", function(progress) {
     document.querySelector("#total-progress").style.opacity = "0"
   })
 
   // Setup the buttons for all transfers
   // The "add files" button doesn't need to be setup because the config
   // `clickable` has already been specified.
   document.querySelector("#actions .start").onclick = function() {
     myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED))
   }
   document.querySelector("#actions .cancel").onclick = function() {
     myDropzone.removeAllFiles(true)
   }
 </script>



<script>
    // Mendapatkan elemen input
    const input = document.getElementById("amount");

    // Menambahkan event listener pada input saat pengguna mengetik
    input.addEventListener("input", function () {
        // Mengambil nilai input tanpa tanda titik dan karakter non-angka
        const rawValue = input.value.replace(/[^0-9]/g, '');

        // Mengubah nilai input dengan menambahkan tanda titik setiap 3 digit
        const formattedValue = addThousandSeparator(rawValue);

        // Memasukkan nilai yang telah diformat kembali ke dalam input
        input.value = formattedValue;
    });

    // Fungsi untuk menambahkan tanda titik sebagai pemisah ribuan
    function addThousandSeparator(value) {
        return value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
    const inputM = document.getElementById("amount_monthly_fee");

    // Menambahkan event listener pada input saat pengguna mengetik
    inputM.addEventListener("input", function () {
        // Mengambil nilai input tanpa tanda titik dan karakter non-angka
        const rawValue = inputM.value.replace(/[^0-9]/g, '');

        // Mengubah nilai input dengan menambahkan tanda titik setiap 3 digit
        const formattedValue = addThousandSeparator(rawValue);

        // Memasukkan nilai yang telah diformat kembali ke dalam input
        inputM.value = formattedValue;
    });

    // Fungsi untuk menambahkan tanda titik sebagai pemisah ribuan
    function addThousandSeparator(value) {
        return value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // Mendapatkan elemen input
    const inputdp = document.getElementById("dp");

    // Menambahkan event listener pada input saat pengguna mengetik
    inputdp.addEventListener("input", function () {
        // Mengambil nilai input tanpa tanda titik dan karakter non-angka
        const rawValue = inputdp.value.replace(/[^0-9]/g, '');

        // Mengubah nilai input dengan menambahkan tanda titik setiap 3 digit
        const formattedValue = addThousandSeparator(rawValue);

        // Memasukkan nilai yang telah diformat kembali ke dalam input
        inputdp.value = formattedValue;
    });

    // Fungsi untuk menambahkan tanda titik sebagai pemisah ribuan
    function addThousandSeparator(value) {
        return value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

</script>


<link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>
<script src="{{ asset('js/logout.js') }}" defer></script>
