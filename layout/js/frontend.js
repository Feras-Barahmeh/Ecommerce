$(function () {
    'use strict';
    

    // switch between login and signup
    $('.body-login h1 span').click(function() {
        $(this).addClass('active').siblings().removeClass('active');
        $('.body-login form').hide();
        $('.' + $(this).data('class')).show();
    });
    


    // This function to cofirm delete memeber
    $('.confirm').click(function () {
        return confirm('Are You Sure do it ?');
    });

    // Start Live in add categorie
        $('.live-name').keyup(function () {
            $('.live-edit .caption h3').text($(this).val());
        });

        $('.live-description').keyup(function () {
            $('.live-edit .caption p').text($(this).val());
        });

        $('.live-price').keyup(function () {
            $('.live-edit span').text("$" + $(this).val());
        });
    // End Live in add categorie
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