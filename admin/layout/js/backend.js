$(function () {
    'use strict';
    // DashBoard toggel-info
    $('.toggel-info').click(function () {
        $(this).toggleClass('selected').parent().next('.panel-body').fadeToggle(100);
        if($(this).hasClass('selected')) {
            $(this).html(' <i class="fa fa-plus fa-lg"></i>');
        } else {
            $(this).html(' <i class="fa fa-minus fa-lg"></i>');

        }
    });

    // This function to cofirm delete memeber
    $('.confirm').click(function () {
        return confirm('Are You Sure do it ?');
    });
});


// Start Dropdowmn
    /* When the user clicks on the button,
    toggle between hiding and showing the dropdown content */
    function myFunction() {
        document.getElementById("myDropdown").classList.toggle("show");
    }

    // Close the dropdown menu if the user clicks outside of it
    window.onclick = function(event) {
    if (!event.target.matches('.dropbtn')) {
        var dropdowns = document.getElementsByClassName("dropdown-content");
        var i;
        for (i = 0; i < dropdowns.length; i++) {
        var openDropdown = dropdowns[i];
        if (openDropdown.classList.contains('show')) {
            openDropdown.classList.remove('show');
        }
        }
    }
    }


