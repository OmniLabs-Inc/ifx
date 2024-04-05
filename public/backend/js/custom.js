(function($) {
    "use strict";
    //Email template config page
    $('select[name=email_method]').on('change', function () {
        var method = $(this).val();

        $('.configForm').addClass('d-none');
        if (method != 'sendmail') {
            $(`#${method}`).removeClass('d-none');
        }
    }).change();

    $('#navbar_search').on('input', function () {
        var search = $(this).val().toLowerCase();
        var search_result_pane = $('#navbar_search_area .navbar_search_result');
        $(search_result_pane).html('');
        if (search.length == 0) {
            return;
        }

        var match = $('#s7__sidebar-nav .sidebar-link').filter(function (idx, element) {
            return $(element).text().trim().toLowerCase().indexOf(search) >= 0 ? element : null;
        }).sort();

        if (match.length == 0) {
            $(search_result_pane).append('<li class="text-muted">No search result found.</li>');
            return;
        }

        match.each(function (index, element) {
            var item_url = $(element).attr('href') || $(element).data('default-url');
            var item_text = $(element).text().replace(/(\d+)/g, '').trim();
            $(search_result_pane).append(`<li><a href="${item_url}">${item_text}</a></li>`);
        });
    });

    $(document).ready(function () {
        $('.editButton').on('click',function () {
            $('#confirmDel').attr('action',$(this).data('route'));
        });
    });

    $(document).ready(function () {
        $('.editBtn').on('click',function () {
            $('#editPartner').attr('action',$(this).data('route'));
            $('#image-preview2').attr('src',$(this).data('image'));
        });
    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#image-preview').attr('src', e.target.result);
                $('#image-preview').hide();
                $('#image-preview').fadeIn(650);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    function readURL2(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#image-preview2').attr('src', e.target.result);
                $('#image-preview2').hide();
                $('#image-preview2').fadeIn(650);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    function readURL3(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#image-preview3').attr('src', e.target.result);
                $('#image-preview3').hide();
                $('#image-preview3').fadeIn(650);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#file-input").on('change',function() {
        readURL(this);
    });
    $("#file-input2").on('change',function() {
        readURL2(this);
    });
    $("#file-input3").on('change',function() {
        readURL3(this);
    });
    
})(jQuery);