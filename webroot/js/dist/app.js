$(document).ready(function() {

    $('#select-limit').change(function() {
        $('#frm-search').submit();
    });

    $('#billing_after_date, #billing_before_date, #billing_start_at, #billing_end_at, #notice_start_date, #notice_end_date, #par_notice_before_date, #par_notice_after_date, .input-date-format-full')
        .daterangepicker({
            singleDatePicker: true,
            timePicker: true,
            timePicker24Hour: true,
            autoUpdateInput: true,
            locale: {
                format: 'YYYY-MM-DD HH:mm',
                applyLabel: "保存",
                cancelLabel: 'キャンセル',
                monthNames: ["1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "9月", "10月", "11月", "12月"],
                daysOfWeek: ["日", "月", "火", "水", "木", "金", "土"]
            }
        })
        .on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD HH:mm'));
        });

    //highlight menu active
    $('ul.sidebar-menu li a').each(function(){
        if($($(this))[0].href.split("/")[$($(this))[0].href.split("/").length -1] != 'logout') {
            if (String(window.location).indexOf("/"+$($(this))[0].href.split("/")[$($(this))[0].href.split("/").length -1]) > -1)
                $(this).parent().addClass('active');
        }
    });
    $('[data-popup-open]').on('click', function(e)  {
        var targeted_popup_class = jQuery(this).attr('data-popup-open');
        $('[data-popup="' + targeted_popup_class + '"]').fadeIn(350);
        google.maps.event.trigger(map, "resize");
        e.preventDefault();
    });
    $('[data-popup-close]').on('click', function(e)  {
        var targeted_popup_class = jQuery(this).attr('data-popup-close');
        $('[data-popup="' + targeted_popup_class + '"]').fadeOut(350);

        e.preventDefault();
    });

    $('.required-icon').tooltip({
        placement: 'left',
        title: 'Required field'
    });
    //golf search nation
    $('#nation').change(function() {
        var nation_id = $(this).val();
        var url_pre = $("#url_pre").val();
        $.ajax({
            url: url_pre,
            type: 'GET',
            data: {'nation_id':nation_id},
            dataType: 'html',
            cache: true,
            success: function(response) {
                obj = jQuery.parseJSON(response);
                $('#prefecture').html('');
                $('#prefecture').append('<option value="">---</option>');
                if(Object.keys(obj).length > 0){
                    $.each(obj, function (key, value) {
                        $('#prefecture').append('<option value="' + key + '">' + value + '</option>');
                    });
                }
            },
            error: function() {
                console.log('error prefecture');
            }
        });
    });
    //man edit-bill
    $('#billing_type_edit,#combined_object_flg').change(function() {
        var type = $('#billing_type_edit').val();
        var com = $('#combined_object_flg').val();
        var date_end = new Date($('#billing_end_at').val());
        if(com == 0) {
            var date_update = moment(date_end).format('YYYY-MM-DD HH:mm:ss');
            $('#billing_end_date').html(date_update);
        } else {
            if (type == 1) {
                var date_update = moment(date_end).add(1, 'months').format('YYYY-MM-DD HH:mm:ss');
                $('#billing_end_date').html(date_update);
            } else if (type == 2) {
                var date_update = moment(date_end).add(3, 'months').format('YYYY-MM-DD HH:mm:ss');
                $('#billing_end_date').html(date_update);
            } else if (type == 3) {
                var date_update = moment(date_end).add(6, 'months').format('YYYY-MM-DD HH:mm:ss');
                $('#billing_end_date').html(date_update);
            } else if (type == 4) {
                var date_update = moment(date_end).add(1, 'years').format('YYYY-MM-DD HH:mm:ss');
                $('#billing_end_date').html(date_update);
            }
        }
    });

    //open popup add hole in screen hole/add
    $('[data-popup-open-golf]').on('click', function(e)  {
        if($("#lat").val() <= 0 || $("#long").val() <= 0){
            alert(LATLONG_EMPTY);
        }else{
            if($('.allCourse').length == 0){
                alert(COURSE_EXIT_NOT_ADD_HOLE);
            }else{
                var allRadioCourse = 0;
                $('.allRadioCourse').each(function(){
                    if (!$(this).hasClass("delete")) {
                        if ($(this).prop('checked')) {
                            allRadioCourse = $(this).val();
                        }
                    }
                });
                if(allRadioCourse > 0){
                    var targeted_popup_class = jQuery(this).attr('data-popup-open-golf');
                    $('[data-popup="' + targeted_popup_class + '"]').fadeIn(350);
                    e.preventDefault();
                    google.maps.event.trigger(map, "resize");
                }else{
                    alert(COURSE_NOT_CHECKED);
                }
            }
        }
    });
});
//validation phone
function inputPhone(evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode
    if ((evt.ctrlKey && charCode==99) || (evt.ctrlKey && charCode==118)){
        return ;
    }
    if ( charCode != 37 && charCode != 67 && charCode != 17 && charCode != 39 && (charCode != 46) && (charCode != 43) && (charCode < 48 || charCode > 57) && (charCode < 8 || charCode > 9) && (charCode != 45)  && (charCode != 13) && (charCode != 46) && (charCode != 43))
        return false;
    return true;
}
//validation lat long
function inputLatlong(evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode
    if ((evt.ctrlKey && charCode==99) || (evt.ctrlKey && charCode==118)){
        return ;
    }
    if ( charCode != 37 && charCode != 67 && charCode != 17 && charCode != 39 && (charCode != 46) && (charCode != 43) && (charCode < 48 || charCode > 57) && (charCode < 8 || charCode > 9)  && (charCode != 13) && (charCode != 46) && (charCode != 43))
        return false;
    return true;
}
//validation number
function inputNumber(evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode
    if ((evt.ctrlKey && charCode==99) || (evt.ctrlKey && charCode==118)){
        return ;
    }
    if ( charCode != 37 && charCode != 67 && charCode != 17 && charCode != 39  && (charCode != 43) && (charCode < 48 || charCode > 57) && (charCode < 8 || charCode > 9)  && (charCode != 13) && (charCode != 43))
        return false;
    return true;
}
//get lat long
function getlatlongByaddress(address){
    var address = address.value;
    var url_latlong = $("#url_latlong").val();
    if(address != '' && address != 0){
        $.ajax({
            url: url_latlong,
            type: 'GET',
            data: {'address':address},
            dataType: 'html',
            cache: true,
            success: function(response) {
                obj = jQuery.parseJSON(response);
                $('#lat').val(obj.latitude);
                $('#long').val(obj.longitude);
            },
            error: function() {
                console.log('error get lat long');
            }
        });
    }
}
