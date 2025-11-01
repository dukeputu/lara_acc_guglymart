<!-- jQuery 2.1.3 -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  
    <!-- Bootstrap 3.3.2 JS -->
    <script src="{{ asset('admin/bootstrap/js/bootstrap.min.js')}}" type="text/javascript"></script>    

    <script src="{{ asset('admin/dist/js/app.min.js')}}" type="text/javascript"></script>

        <!-- DATA TABES SCRIPT -->
       <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>


<!-- DataTables Buttons + Export -->
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<!-- Required for Excel export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<!-- Required for PDF export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>

<!-- CSS for buttons -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">


  </body>
</html>

<script type="text/javascript">
$(document).ready(function() {
    $('#fileTable1').DataTable({
        "pageLength": 20,
        "lengthMenu": [5, 10, 20, 50],
        "scrollX": true,            // Enables horizontal scrolling
        "responsive": false,         // Disables responsive layout
        "dom": 'Bfrtip', // Add buttons container
        "buttons": [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
});
</script>


<script>
    // Toggle Sidebar (Mobile)
    function toggleSidebar() {
        document.getElementById('modernSidebar').classList.toggle('active');
    }

    // Toggle Submenu
    function toggleSubmenu(element) {
        const menuItem = element.parentElement;
        menuItem.classList.toggle('open');
    }

    // Active Menu Highlight
    document.addEventListener('DOMContentLoaded', function () {
        const currentPath = window.location.pathname;

        document.querySelectorAll('.menu-link, .submenu-link').forEach(link => {
            if (link.getAttribute('href') === currentPath) {
                link.closest('.menu-item').classList.add('active');

                // Open parent submenu if it's a submenu link
                const parentSubmenu = link.closest('.submenu');
                if (parentSubmenu) {
                    parentSubmenu.previousElementSibling.click();
                }
            }
        });

        // Load saved phone search
        const savedPhone = localStorage.getItem('searchedPhone');
        if (savedPhone) {
            document.getElementById('phoneSearch').value = savedPhone;
        }

        // Save phone on search
        document.getElementById('phoneSearch').addEventListener('input', function () {
            localStorage.setItem('searchedPhone', this.value);
        });
    });

    // Close sidebar when clicking outside (mobile)
    document.addEventListener('click', function (event) {
        const sidebar = document.getElementById('modernSidebar');
        const toggle = document.querySelector('.menu-toggle');

        if (window.innerWidth <= 1024) {
            if (!sidebar.contains(event.target) && !toggle.contains(event.target)) {
                sidebar.classList.remove('active');
            }
        }
    });
</script>



<script>
    // ✅ Toggle submenu open/close
    function toggleSubmenu(element) {
        const parent = element.closest('.menu-item');
        const submenu = parent.querySelector('.submenu');
        const arrow = parent.querySelector('.menu-arrow');
        if (submenu) {
            submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';
            if (arrow) arrow.classList.toggle('rotate');
        }
    }

    // ✅ Live search across multiple sidebar menus
    document.getElementById('menuSearch').addEventListener('keyup', function() {
        const filter = this.value.toLowerCase();
        const sidebarMenus = document.querySelectorAll('.sidebar-menu'); // Multiple sidebars support

        sidebarMenus.forEach(menu => {
            const menuItems = menu.querySelectorAll('.menu-item');
            menuItems.forEach(item => {
                const text = item.innerText.toLowerCase();
                item.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    });
</script>

