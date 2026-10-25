</div> <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const content = document.querySelector('.content-area');
            
            if (window.innerWidth > 768) {
                // Di Desktop: Tambah/Hapus class 'hide'
                // Karena content-area punya flex: 1, ia akan melebar otomatis
                sidebar.classList.toggle('hide');
            } else {
                // Di Mobile: Tambah/Hapus class 'show' untuk overlay
                sidebar.classList.toggle('show');
            }
        }
    </script>
</body>
</html>