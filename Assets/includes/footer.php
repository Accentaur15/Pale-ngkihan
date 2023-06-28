  <!-- Main Footer -->
  <footer class="main-footer text-center text-white" style = "background-color: #678A71;">
    <!-- Default to the left -->
    <strong>Copyright &copy; Pale-ngkihan 2023.</strong> All rights reserved.
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="../Assets/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../Assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../Assets/dist/js/adminlte.min.js"></script>
<!-- Summernote -->
<script src="../Assets/plugins/summernote/summernote-bs4.min.js"></script>
<!-- CodeMirror -->
<script src="../Assets/plugins/codemirror/codemirror.js"></script>
<script src="../Assets/plugins/codemirror/mode/css/css.js"></script>
<script src="../Assets/plugins/codemirror/mode/xml/xml.js"></script>
<script src="../Assets/plugins/codemirror/mode/htmlmixed/htmlmixed.js"></script>
<!-- DataTables  & Plugins -->
<script src="../Assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../Assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../Assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../Assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../Assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../Assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../Assets/plugins/jszip/jszip.min.js"></script>
<script src="../Assets/plugins/pdfmake/pdfmake.min.js"></script>
<script src="../Assets/plugins/pdfmake/vfs_fonts.js"></script>
<script src="../Assets/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="../Assets/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="../Assets/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<script>
  $("#productlist").DataTable({
    "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
  });

  $(document).ready(function() {
  $('#summernote').summernote({
    toolbar: [
  ['style', ['style']],
  ['font', ['bold', 'underline', 'clear']],
  ['fontname', ['fontname']],
  ['color', ['color']],
  ['para', ['ul', 'ol', 'paragraph']],
  ['view', ['fullscreen', 'help']],
],
  });
  
});

</script>


</body>
</html>