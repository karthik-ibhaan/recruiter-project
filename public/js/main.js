const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))

$(function() {
  $('#sidebarCollapse').on('click', function() {
    $('#topBar').toggleClass('active');
    $('#sidebar, #content').toggleClass('active');
  });

  $(document).ready(function () {
    var today = new Date().toISOString().split('T')[0];
    $("#submissionDate").attr('max', today);

    $('#interview-date').attr('min', today);
});
});