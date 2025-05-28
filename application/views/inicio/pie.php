</div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 3.2.0
    </div>
    <strong>Copyright &copy; 2025 <a href="https://madifac.madifactory.com/">MERCURIO</a>.</strong> All rights reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="<?php echo base_url();?>resources/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo base_url();?>resources/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<script src="<?php echo base_url();?>resources/plugins/select2/js/select2.full.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="<?php echo base_url();?>resources/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url();?>resources/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo base_url();?>resources/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?php echo base_url();?>resources/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?php echo base_url();?>resources/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url();?>resources/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="<?php echo base_url();?>resources/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="<?php echo base_url();?>resources/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="<?php echo base_url();?>resources/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>


<script src="<?php echo base_url();?>resources/plugins/jszip/jszip.min.js"></script>
<script src="<?php echo base_url();?>resources/plugins/pdfmake/pdfmake.min.js"></script>
<script src="<?php echo base_url();?>resources/plugins/pdfmake/vfs_fonts.js"></script>

<!-- AdminLTE App -->
<script src="<?php echo base_url();?>resources/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url();?>resources/dist/js/demo.js"></script>
<!-- Page specific script -->
<script>
  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
    
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script>
<script type="text/javascript">
    $(document).ready(function(){
       $(".cerrarPDF").click(function () {
            $("#cargarArchivoModal").hide();
            $("#pdfModal").hide();
            $("#divCapa").removeClass("overlay");
            return false;
       });
    });
</script>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    let currentUrl = window.location.href; // Obtiene la URL actual
    let links = document.querySelectorAll(".nav-treeview .nav-link"); 

    links.forEach(link => {
        if (link.href === currentUrl) {
            link.classList.add("active"); // Marca la opción activa

            let parentNavItem = link.closest(".nav-item"); // Encuentra el <li> padre inmediato
            let grandParentNavItem = parentNavItem.closest(".nav-item"); // Encuentra el <li> del nivel superior

            if (parentNavItem) {
                parentNavItem.classList.add("menu-open");
            }

            if (grandParentNavItem) {
                grandParentNavItem.classList.add("menu-open");
                let parentLink = grandParentNavItem.querySelector(".nav-link");
                if (parentLink) {
                    parentLink.classList.add("active");
                }
            }

            // También muestra el submenu activando la clase `d-block`
            let parentTreeView = parentNavItem.closest(".nav-treeview");
            if (parentTreeView) {
                parentTreeView.style.display = "block"; // Asegura que esté visible
            }
        }
    });
  });
</script>

</body>
</html>
