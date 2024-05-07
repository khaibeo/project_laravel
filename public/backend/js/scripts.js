/*!
    * Start Bootstrap - SB Admin v7.0.7 (https://startbootstrap.com/template/sb-admin)
    * Copyright 2013-2023 Start Bootstrap
    * Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-sb-admin/blob/master/LICENSE)
    */
    // 
// Scripts
// 
window.addEventListener('DOMContentLoaded', event => {

    // Toggle the side navigation
    const sidebarToggle = document.body.querySelector('#sidebarToggle');
    if (sidebarToggle) {
        // Uncomment Below to persist sidebar toggle between refreshes
        // if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
        //     document.body.classList.toggle('sb-sidenav-toggled');
        // }
        sidebarToggle.addEventListener('click', event => {
            event.preventDefault();
            document.body.classList.toggle('sb-sidenav-toggled');
            localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
        });
    }

    const tableList = document.querySelector('#myTable');
    const formDelete = document.querySelector('.delete-form');

    tableList.addEventListener('click',(e) => {
        if(e.target.classList.contains('delete-action')){
            e.preventDefault();
            Swal.fire({
                title: "Bạn có chắc là muốn xóa ?",
                text: "Bạn sẽ không thể khôi phục",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Xóa"
              }).then((result) => {
                if (result.isConfirmed) {
                    const action = e.target.getAttribute('href');
                    formDelete.action = action;
                    formDelete.submit();
                }
              });
            
        }
    })
});

// $('#myTable').DataTable( {
//     serverSide: true,
//     ajax: '/data-source'
// } );
