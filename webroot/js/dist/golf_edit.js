$(document).ready(function() {


});
//add course screen add golf
function  openPopupCourse(){
    $("#editOraddCourse").html('追加');
    $("#flg_editcourse").val('default');
    $('#course_name_add').val('');
    $('#course_name_kana_add').val('');
    $('#course_name_en_add').val('');
    $('#sl_status_course').val('1');
}
//append course
function addNewCourse(){
    $(".error-message-course").remove();
    if($("#course_name_add").val() == ''){
        str = '<div class="error-message-course">'+$("#course_name_add").attr('title')+CHECK_NULL+'</div>';
        $("#course_name_add").addClass('golfboderErrorCourse');
        $(str).insertAfter("#course_name_add_required");
    }else{
        $("#course_name_add").removeClass('golfboderErrorCourse');
        $(".error-message-course").remove();
        var flg_editcourse = $("#flg_editcourse").val();
        var status_course = ["公開中","閉鎖中","破棄済"];

        course_name = $("#course_name_add").val();
        course_name_kana = $("#course_name_kana_add").val();
        course_name_en = $("#course_name_en_add").val();
        service_status = $('#sl_status_course').val();
        field_id = $("#field_id").val();
        //edit course
        if(flg_editcourse != 'default'){
            //show html
            $("#course_name_"+flg_editcourse).val($('#course_name_add').val());
            $("#course_name_kana_"+flg_editcourse).val($('#course_name_kana_add').val());
            $("#course_name_en_"+flg_editcourse).val($('#course_name_en_add').val());
            $("#hdd_status_course"+flg_editcourse).val($('#sl_status_course').val());
            $("#lblcourse_name_"+flg_editcourse).html($('#course_name_add').val());
            $("#status_course_"+flg_editcourse).html(status_course[parseInt($("#sl_status_course").val() - 1)]);

        }else{
            //add course
            var count_course = parseInt($("#count_course").val()) + 1;
            var checked = str = '';
            //if(count_course == 1) checked = 'checked="checked"';

            str += '<div class="box allCourse" id="course_'+count_course+'">';
            str += '<div class="box-body"><label class="control-label" id="lblcourse_name_'+count_course+'">'+$("#course_name_add").val()+'</label>';
            str += '<div class="form-group"></div><div class="col-sm-1" style="width: 2%;">';
            str += '<input type="radio" '+checked+' value="'+count_course+'" class="allRadioCourse" name="allRadioCourse" onclick="clRadioCourse(\''+count_course+'\')"></div><div class="col-sm-3 ">';
            str += '<span>ホール数：&#12288;<span id="hole_num_'+count_course+'">0</span></span></div>';
            str += '<div class="col-sm-3"><span>パー数：&#12288;<span id="par_num_'+count_course+'">0</span></span></div>';
            str += '<div class="col-sm-3"><span>状態：&#12288;<span id="status_course_'+count_course+'">'+status_course[parseInt($("#sl_status_course").val() -1)]+'</span></span></div>';
            str += '<div class="col-sm-2">';
            str += '<a href="javascript:void(0);" class="btn btn-info btn-sm" onclick="editCourse(\''+count_course+'\')">';
            str += '<i class="fa fa-fw fa-edit"></i>編集</a> ';
            str += '<a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="deleteCourse(\''+count_course+'\')">';
            str += '<i class="fa fa-fw fa-remove"></i>削除</a>';
            str += '</div>';
            str += '</div><input name="course['+count_course+'][course_id]" id="course_id_'+count_course+'" value="0" type="hidden">';
            str += '<input name="course['+count_course+'][course_name]" id="course_name_'+count_course+'" value="'+$("#course_name_add").val()+'" type="hidden">';
            str += '<input name="course['+count_course+'][course_name_kana]" id="course_name_kana_'+count_course+'" value="'+$("#course_name_kana_add").val()+'" type="hidden">';
            str += '<input name="course['+count_course+'][course_name_en]" id="course_name_en_'+count_course+'" value="'+$("#course_name_en_add").val()+'" type="hidden">';
            str += '<input name="course['+count_course+'][service_status]" id="hdd_status_course'+count_course+'" value="'+$("#sl_status_course").val()+'" type="hidden">';
            str += '<input name="course['+count_course+'][hole_num]" id="hdd_hole_num_course_'+count_course+'" value="0" type="hidden">';
            str += '<input name="course['+count_course+'][par_num]" id="hdd_par_num_course_'+count_course+'" value="0" type="hidden">';
            str += '<input name="course['+count_course+'][delete]" id="hdd_delete_course_'+count_course+'" value="0" type="hidden">';
            str += '</div>';
            $(str).insertBefore( "#openCourse");
            //success
            $("#count_course").val(count_course);
        }
        $("#flg_editcourse").val('default');
        $("#popupCourse").css('display','none');
    }
}
//editCourse
function editCourse(id){
    $("#editOraddCourse").html('編集');
    $("#popupCourse").css('display','block');
    $("#flg_editcourse").val(id);
    $('#course_name_add').val($("#course_name_"+id).val());
    $('#course_name_kana_add').val($("#course_name_kana_"+id).val());
    $('#course_name_en_add').val($("#course_name_en_"+id).val());
    $('#sl_status_course').val($("#hdd_status_course"+id).val());
}

//deleteCourse
function deleteCourse(id) {
    if (confirm(CONFIRM_DELETE_COURSE)){
        $(".error_course").hide();
        $(".error_course").html('');
        //success delete html in screen
        $("#course_" + id).hide();
        $("#course_" + id).addClass('course_deleted');
        $("#hdd_delete_course_" + id).val('1');
        var allCourse = $('.allCourse').length;
        if (allCourse == 0) $("#count_course").val('0');
        //Khi xoa đúng course đang checked
        $('.allRadioCourse').each(function () {
            if ($(this).prop('checked')) {
                if ($(this).val() == id) {
                    $("#flg_orderCourse").val('0');
                    $("#course_name_of_hole").html('');
                    $("#lbl_hole_of_course").html('');
                    $("#lbl_par_of_course").html('');
                }
            }
        });
        $("#course_" + id +" input[type=radio]").addClass('delete');
        //delete hole of course
        $('.allHoleOfCourse_' + id).remove();
    }
}
//deleteHole
function deleteHoleOfcourse(id){
    $("#onlyholeOfcourse_"+id).hide();
    $("#onlyholeOfcourse_"+id).addClass('hole_deleted');
    $("#hdd_allparofhole_"+id).addClass('par_deleted');
    $("#orderHole_"+id).addClass('orderHole_deleted');
    $("#hdd_delete_hole_add_"+id).val('1');
    countParHole();
}
function clRadioCourse(value){
    //focus course
    $('.allRadioCourse').each(function(){
        if ($(this).val() == value) {
            $(this).parent().parent().addClass('bkChecked');
            $("#flg_orderCourse").val(value);
            //hiên thị hết hole thuộc course này và ẩn hết hole course khác
            $("#course_name_of_hole").html($("#course_name_"+value).val());
            $("#lbl_hole_of_course").html($("#hdd_hole_num_course_"+value).val());
            $("#lbl_par_of_course").html($("#hdd_par_num_course_"+value).val());
        }else{
            $(this).parent().parent().removeClass('bkChecked');
        }
    });
    //hidden show hole in only course
    var flg_orderCourse = $("#flg_orderCourse").val();
    $('.allHoleAllCourse').each(function(){
        if ($(this).hasClass("allHoleOfCourse_"+flg_orderCourse)) {
            $(this).show();
        }else{
            $(this).hide();
        }
    });
    //hide message hole course
    $(".error_hole").hide();
    $(".error_hole").html('');
    $(".error_course").hide();
    $(".error_course").html('');
}
//add holes to course
function addHoleToCourse(){
    var status_hole = ["公開中","閉鎖中","破棄済"];
    var allGreenNew = $('.allGreenNew').length;
    var flg_orderCourse = $("#flg_orderCourse").val();
    var flg_orderHoleOfCourse = $("#flg_orderHoleOfCourse").val();
    //required form
    $(".error-message-hole").remove();
    flg = 0;
    //hole name
    if($("#hole_name_add").val() == ''){
        str = '<div class="error-message-hole">'+$("#hole_name_add").attr('title')+CHECK_NULL+'</div>';
        $("#hole_name_add").addClass('golfboderError');
        $(str).insertAfter("#"+$("#hole_name_add").attr('id')+"_required");
        flg = 1;
    }else{
        $("#hole_name_add").removeClass('golfboderError');
    }
    //par_hole_add
    if($("#par_hole_add").val() == ''){
        str = '<div class="error-message-hole">'+$("#par_hole_add").attr('title')+CHECK_NULL+'</div>';
        $("#par_hole_add").addClass('golfboderError');
        $(str).insertAfter("#"+$("#par_hole_add").attr('id')+"_required");
        flg = 1;
    }else{
        $("#par_hole_add").removeClass('golfboderError');
    }
    //tee_lat_hole_add
    if($("#tee_lat_hole_add").val() == ''){
        str = '<div class="error-message-hole">'+$("#tee_lat_hole_add").attr('title')+CHECK_NULL+'</div>';
        $("#tee_lat_hole_add").addClass('golfboderError');
        $(str).insertAfter("#"+$("#tee_lat_hole_add").attr('id')+"_required");
        flg = 1;
    }else{
        $("#tee_lat_hole_add").removeClass('golfboderError');
    }
    //tee_long_hole_add
    if($("#tee_long_hole_add").val() == ''){
        str = '<div class="error-message-hole">'+$("#tee_long_hole_add").attr('title')+CHECK_NULL+'</div>';
        $("#tee_long_hole_add").addClass('golfboderError');
        $(str).insertAfter("#"+$("#tee_long_hole_add").attr('id')+"_required");
        flg = 1;
    }else{
        $("#tee_long_hole_add").removeClass('golfboderError');
    }
    if(flg == 0){
        //required green
        var checkgreen = 0;
        $('.countGreenOfhole').each(function(){
            if (!$(this).hasClass("delete")) {
                checkgreen ++;
            }
        });
        if(checkgreen == 0){
            alert(GREEN_NOT_EXIT);
        }else{
            var flg_edithole = $("#flg_edithole").val();
            var hole_name_add = $("#hole_name_add").val();
            var status_hole_add = $("#status_hole_add").val();
            var tee_lat_hole_add = $("#tee_lat_hole_add").val();
            var par_hole_add = $("#par_hole_add").val();
            var tee_long_hole_add = $("#tee_long_hole_add").val();
            var countGreenOfhole = $('.countGreenOfhole').length;
            //edit course
            var str = '';
            if(flg_edithole != 'default'){
                //set view hole success edit
                $("#parHole_"+flg_edithole).html(par_hole_add);
                $("#greenHole_"+flg_edithole).html(checkgreen);
                $("#service_status_hole_of_courses_"+flg_edithole).html(status_hole[parseInt(status_hole_add) - 1]);
                coursesPopupTmp = flg_edithole.split("_")[0];
                flg_orderHoleOfCourse = flg_edithole.split("_")[1];
                //tinh lai green khi edit
                //set input hidden
                $(".box_edit_hole_"+flg_edithole).html('');

                str += '<input type="hidden" name="course['+flg_orderCourse+'][hole]['+flg_orderHoleOfCourse+'][hole_name_add]" class="alldataHole_ofCourse_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" id="hdd_hole_name_add_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" value="'+hole_name_add+'"/>';
                str += '<input type="hidden" name="course['+flg_orderCourse+'][hole]['+flg_orderHoleOfCourse+'][status_hole_add]" class="alldataHole_ofCourse_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" id="hdd_status_hole_add_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" value="'+status_hole_add+'"/>';
                str += '<input type="hidden" name="course['+flg_orderCourse+'][hole]['+flg_orderHoleOfCourse+'][tee_lat_hole_add]" class="alldataHole_ofCourse_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" id="hdd_tee_lat_hole_add_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" value="'+tee_lat_hole_add+'"/>';
                str += '<input type="hidden" name="course['+flg_orderCourse+'][hole]['+flg_orderHoleOfCourse+'][par_hole_add]" class="alldataHole_ofCourse_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" id="hdd_par_hole_add_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" value="'+par_hole_add+'"/>';
                str += '<input type="hidden" name="course['+flg_orderCourse+'][hole]['+flg_orderHoleOfCourse+'][tee_long_hole_add]" class="alldataHole_ofCourse_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" id="hdd_tee_long_hole_add_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" value="'+tee_long_hole_add+'"/>';
                str += '<input type="hidden" name="course['+flg_orderCourse+'][hole]['+flg_orderHoleOfCourse+'][allGreenOfHole]" class="alldataHole_ofCourse_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+' allGreenOfOnlyHole" id="hdd_allgreenofhole_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" value="'+checkgreen+'"/>';
                str += '<input type="hidden" name="course['+flg_orderCourse+'][hole]['+flg_orderHoleOfCourse+'][allParOfHole]" class="alldataHole_ofCourse_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+' allParOfOnlyHole_'+flg_orderCourse+'"  id="hdd_allparofhole_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" value="'+par_hole_add+'"/>';
                //lấy lại hidden data holes
                str += '<input id="hdd_hole_id_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" name="course['+flg_orderCourse+'][hole]['+flg_orderHoleOfCourse+'][hole_id]" class="" value="'+$("#popup_hdd_hole_id_"+flg_edithole).val()+'" type="hidden"/>';
                str += '<input id="hdd_version_hole_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" name="course['+flg_orderCourse+'][hole]['+flg_orderHoleOfCourse+'][version]" class="" value="'+$("#popup_hdd_version_hole_"+flg_edithole).val()+'" type="hidden"/>';
                str += '<input id="hdd_delete_hole_add_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" name="course['+flg_orderCourse+'][hole]['+flg_orderHoleOfCourse+'][delete]" class="" value="'+$("#popup_hdd_delete_hole_add_"+flg_edithole).val()+'" type="hidden"/>';
                //green_id, version, delete
                $('.alldataPopupGreen_id_ofHole').each(function(index){
                    idGreen = $(this).attr('id').replace("alldataPopupGreen_id_ofHole", "");
                    str += '<input class="alldataHole_ofCourse_'+flg_edithole+' alldataGreen_id_ofHole_'+flg_edithole+'" name="course['+coursesPopupTmp+'][hole]['+flg_orderHoleOfCourse+'][latlong][green_id_'+idGreen+']" value="'+$(this).val()+'" type="hidden">';
                });
                $('.alldataPopupGreen_version_ofHole').each(function(index){
                    versionGreen = $(this).attr('id').replace("alldataPopupGreen_version_ofHole", "");
                    str += '<input class="alldataHole_ofCourse_'+flg_edithole+' alldataGreen_version_ofHole_'+flg_edithole+'" name="course['+coursesPopupTmp+'][hole]['+flg_orderHoleOfCourse+'][latlong][version_'+versionGreen+']" value="'+$(this).val()+'" type="hidden">';
                });
                $('.alldataPopupGreen_delete_ofHole').each(function(index){
                    delGreen = $(this).attr('id').replace("alldataPopupGreen_delete_ofHole", "");
                    str += '<input class="alldataHole_ofCourse_'+flg_edithole+' alldataGreen_delete_ofHole_'+flg_edithole+'" name="course['+coursesPopupTmp+'][hole]['+flg_orderHoleOfCourse+'][latlong][delete_'+delGreen+']" value="'+$(this).val()+'" type="hidden">';
                });
                //green of hole
                $('.allGreenNew').each(function(){
                    str += '<input type="hidden" name="course['+flg_orderCourse+'][hole]['+flg_orderHoleOfCourse+'][latlong]['+$(this).attr('id')+']" class="alldataHole_ofCourse_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+' alldataGreen_ofHole_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" id="hdd_lat_long_hole_add_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'_'+$(this).attr('id')+'" value="'+$(this).val()+'"/>';
                });

                $(".box_edit_hole_"+flg_edithole).append(str);
            }else{
                //add course
                str += '<div class="col-sm-3 allHoleAllCourse allHoleOfCourse_'+flg_orderCourse+'" id ="onlyholeOfcourse_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'"><div class="box"><div class="box-header with-border bkChecked" onclick="openEditPopupHole(\''+flg_orderCourse+'_'+flg_orderHoleOfCourse+'\')" style="cursor: pointer;"><h4 class="text-center">ホールNo <span id="orderHole_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" class="orderHole_'+flg_orderCourse+'"></span></h4></div>';
                str += '<div class="box-body"><div class="text-center"><h5>パー：&#12288;<span id="parHole_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'">'+par_hole_add+'</span></h5>';
                str += '<h5>状態： <span id="service_status_hole_of_courses_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'">'+status_hole[parseInt(status_hole_add) - 1]+'</span></h5>';
                str += '<h5>グリーン数：&#12288;<span id="greenHole_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'">'+checkgreen+'</span></h5>';
                str += '</div></div><div class="box-footer"><button class="button-holedetail" formnovalidate="formnovalidate" type="button" style="background: red" onclick="deleteHoleOfcourse(\''+flg_orderCourse+'_'+flg_orderHoleOfCourse+'\')">削除</button></div></div>';
                //hidden
                str += '<div class="box_edit_hole_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'">';
                str += '<input type="hidden" name="course['+flg_orderCourse+'][hole]['+flg_orderHoleOfCourse+'][hole_name_add]" class="alldataHole_ofCourse_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" id="hdd_hole_name_add_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" value="'+hole_name_add+'"/>';
                str += '<input type="hidden" name="course['+flg_orderCourse+'][hole]['+flg_orderHoleOfCourse+'][status_hole_add]" class="alldataHole_ofCourse_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" id="hdd_status_hole_add_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" value="'+status_hole_add+'"/>';
                str += '<input type="hidden" name="course['+flg_orderCourse+'][hole]['+flg_orderHoleOfCourse+'][tee_lat_hole_add]" class="alldataHole_ofCourse_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" id="hdd_tee_lat_hole_add_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" value="'+tee_lat_hole_add+'"/>';
                str += '<input type="hidden" name="course['+flg_orderCourse+'][hole]['+flg_orderHoleOfCourse+'][par_hole_add]" class="alldataHole_ofCourse_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" id="hdd_par_hole_add_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" value="'+par_hole_add+'"/>';
                str += '<input type="hidden" name="course['+flg_orderCourse+'][hole]['+flg_orderHoleOfCourse+'][tee_long_hole_add]" class="alldataHole_ofCourse_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" id="hdd_tee_long_hole_add_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" value="'+tee_long_hole_add+'"/>';
                str += '<input type="hidden" name="course['+flg_orderCourse+'][hole]['+flg_orderHoleOfCourse+'][allGreenOfHole]" class="alldataHole_ofCourse_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+' allGreenOfOnlyHole" id="hdd_allgreenofhole_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" value="'+checkgreen+'"/>';
                str += '<input type="hidden" name="course['+flg_orderCourse+'][hole]['+flg_orderHoleOfCourse+'][allParOfHole]" class="alldataHole_ofCourse_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+' allParOfOnlyHole_'+flg_orderCourse+'"  id="hdd_allparofhole_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" value="'+par_hole_add+'"/>';
                str += '<input type="hidden" name="course['+flg_orderCourse+'][hole]['+flg_orderHoleOfCourse+'][delete]" class="alldataHole_ofCourse_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'"  id="hdd_delete_hole_add_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" value="0"/>';
                //lấy lại hidden data holes
                str += '<input id="hdd_hole_id_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" name="course['+flg_orderCourse+'][hole]['+flg_orderHoleOfCourse+'][hole_id]" class="" value="0" type="hidden"/>';
                str += '<input id="hdd_version_hole_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" name="course['+flg_orderCourse+'][hole]['+flg_orderHoleOfCourse+'][version]" class="" value="1" type="hidden"/>';
                str += '<input id="hdd_hole_id_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" name="course['+flg_orderCourse+'][hole]['+flg_orderHoleOfCourse+'][delete]" class="" value="0" type="hidden"/>';

                //green of hole
                $('.allGreenNew').each(function(){
                    str += '<input type="hidden" name="course['+flg_orderCourse+'][hole]['+flg_orderHoleOfCourse+'][latlong]['+$(this).attr('id')+']" class="alldataHole_ofCourse_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+' alldataGreen_ofHole_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" id="hdd_lat_long_hole_add_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'_'+$(this).attr('id')+'" value="'+$(this).val()+'"/>';
                });
                str += '</div>';
                str += '</div>';

                $(str).insertBefore( "#beforeHole");
                //
                $("#flg_orderHoleOfCourse").val(parseInt(flg_orderHoleOfCourse)+1);
            }
            $("#flg_edithole").val('default');
            $("#popupHole").css('display','none');
            countParHole();
        }
    }
}
function countParHole(){
    var flg_orderCourse = $("#flg_orderCourse").val();
    var allHoleOfCourse = 0;
    var allParOfOnlyHole = 0;
    $('.allHoleOfCourse_'+flg_orderCourse).each(function(){
        if (!$(this).hasClass("hole_deleted")) {
            allHoleOfCourse++;
        }
    });
    $('.allParOfOnlyHole_'+flg_orderCourse).each(function(){
        if (!$(this).hasClass("par_deleted")) {
            allParOfOnlyHole = (parseInt(allParOfOnlyHole) + parseInt($(this).val()));
        }
    });
    //order
    var i =1;
    $('.orderHole_'+flg_orderCourse).each(function(){
        if (!$(this).hasClass("orderHole_deleted")) {
            $(this).html(i);
            i++;
        }
    });
    $("#hdd_hole_num_course_"+flg_orderCourse).val(allHoleOfCourse);
    $("#hole_num_"+flg_orderCourse).html(allHoleOfCourse);
    $("#lbl_hole_of_course").html(allHoleOfCourse);
    $("#hdd_par_num_course_"+flg_orderCourse).val(allParOfOnlyHole);
    $("#par_num_"+flg_orderCourse).html(allParOfOnlyHole);
    $("#lbl_par_of_course").html(allParOfOnlyHole);
}

//open map add hole
function  openPopupHole(){
    $("#flgAdd").val('0');
    $("#nextHole").hide();
    $("#flgEdit").val('flgEdit');
    $("#orderGreen").val('1');
    var allRadioCourse = 0;
    $('.allRadioCourse').each(function(){
        if (!$(this).hasClass("delete")) {
            if ($(this).prop('checked')) {
                allRadioCourse = $(this).val();
            }
        }
    });
    if(allRadioCourse > 0){
        if($("#lat").val() > 0 && $("#long").val() > 0){
            $("#tee_lat_hole_add").val($("#lat").val());
            $("#tea_latitude").html($("#lat").val());
            $("#tee_long_hole_add").val($("#long").val());
            $("#tea_longitude").html($("#long").val());
            $("#hole_name_add").val('');
            $('#par_hole_add').val('');
            $('#status_hole_add').val('1');
            //green empty
            $("#appendGreen").html('');
            $("#flg_edithole").val('default');
            $('#course_name_of_hole_popup').html($("#course_name_"+$("#flg_orderCourse").val()).val());
            initMapFocusTee();
        }
    }
}

//open map edit hole
function  openEditPopupHole(id){
    if($("#lat").val() <= 0 || $("#long").val() <= 0){
        alert(LATLONG_EMPTY);
    }else{
        $("#flgAdd").val('0');
        $("#flgEdit").val('flgEdit');
        $("#popupHole").css('display','block');
        $("#flg_edithole").val(id);
        $("#orderGreen").val('1');
        $("#hole_name_add").val($("#hdd_hole_name_add_"+id).val());
        $("#par_hole_add").val($("#hdd_par_hole_add_"+id).val());
        $("#status_hole_add").val($("#hdd_status_hole_add_"+id).val());
        var hdd_tee_lat_hole_add = ($("#hdd_tee_lat_hole_add_"+id).val() != '' && $("#hdd_tee_lat_hole_add_"+id).val() != 0) ? $("#hdd_tee_lat_hole_add_"+id).val() : $("#lat").val()
        var hdd_tee_long_hole_add = ($("#hdd_tee_long_hole_add_"+id).val() != '' && $("#hdd_tee_long_hole_add_"+id).val() != 0) ? $("#hdd_tee_long_hole_add_"+id).val() : $("#long").val()
        $("#tee_lat_hole_add").val(hdd_tee_lat_hole_add);
        $("#tea_latitude").html(hdd_tee_lat_hole_add);
        $("#tee_long_hole_add").val(hdd_tee_long_hole_add);
        $("#tea_longitude").html(hdd_tee_long_hole_add);
        //green empty
        $("#appendGreen").html('');
        //lấy lại green
        var str = '';
        var y = 0;
        courses = id.split("_")[0];
        holes = id.split("_")[1];
        //show hide next hole
        var noAllHole = 0;
        var noHole = 0;
        $('#course_name_of_hole_popup').html($("#course_name_"+courses).val());
        $('.allHoleOfCourse_'+courses).each(function(){
            if (!$(this).hasClass("hole_deleted")) {
                noAllHole ++ ;
                if($(this).attr('id') == ('onlyholeOfcourse_'+id)){
                    noHole = noAllHole
                }
            }
        });
        $("#nextHole").show();
        if(noHole < noAllHole){
            $("#btnNext").show();
        }
        $("#noHoleNext").html(noHole);
        $("#noHoleNextAll").html(noAllHole);
        if(noHole == noAllHole){
            $("#btnNext").hide();
        }
        $("#orderNext").val(noHole);
        $("#allOrderNext").val(noAllHole);
        $("#holeNext").val(id);
        //end show hide next hole

        //array green_id
        $("#hdd_data_ofHole_openpopup").html('');
        $("#hdd_data_ofHole_openpopup").append('<input id="popup_hdd_hole_id_'+id+'" class="" value="'+$("#hdd_hole_id_"+id).val()+'" type="hidden">');
        $("#hdd_data_ofHole_openpopup").append('<input id="popup_hdd_version_hole_'+id+'" class="" value="'+$("#hdd_version_hole_"+id).val()+'" type="hidden">');
        $("#hdd_data_ofHole_openpopup").append('<input id="popup_hdd_delete_hole_add_'+id+'" class="" value="'+$("#hdd_delete_hole_add_"+id).val()+'" type="hidden">');

        var arr_greenId = [];
        var arr_greenVersion = [];
        var arr_greenDelete = [];
        $(".alldataGreen_id_ofHole_"+id).each(function( index ) {
            arr_greenId[index] = $(this).val();
        });
        $(".alldataGreen_version_ofHole_"+id).each(function( index ) {
            arr_greenVersion[index] = $(this).val();
        });
        $(".alldataGreen_delete_ofHole_"+id).each(function( index ) {
            arr_greenDelete[index] = $(this).val();
        });
        $(".alldataGreen_ofHole_"+id).each(function( index ) {
            var x = (index+1);
            if((x-1)%3 == 0){
                if(arr_greenDelete[y] == 1){
                    str += '<tr id="trGreen_'+y+'" class="countGreenOfhole delete" style="display: none">';
                }else{
                    str += '<tr id="trGreen_'+y+'" class="countGreenOfhole">';
                }

                str += '<td class="text-center" id="ichi_'+y+'">'+$(this).val();
                str += '<input id="ichi_hdd_'+y+'" class="allGreenNew" name="latlong[]" value="'+$(this).val()+'" type="hidden">';
                str += '</td>';
            }
            if((x+1)%3 == 0){
                str += '<td class="text-center" id="ni_'+y+'">'+$(this).val()+'<input id="ni_hdd_'+y+'" class="allGreenNew" name="latlong[]" value="'+$(this).val()+'" type="hidden"></td>';
            }
            if(x%3 == 0){
                str += '<td class="text-center" id="san_'+y+'">'+$(this).val();
                str += '<input id="san_hdd_'+y+'" class="allGreenNew" name="latlong[]" value="'+$(this).val()+'" type="hidden"></td>';
                str += '<input id="alldataPopupGreen_id_ofHole'+y+'" class="alldataPopupGreen_id_ofHole" value="'+arr_greenId[y]+'" type="hidden">';
                str += '<input id="alldataPopupGreen_version_ofHole'+y+'" class="alldataPopupGreen_version_ofHole" value="'+arr_greenVersion[y]+'" type="hidden">';
                str += '<input id="alldataPopupGreen_delete_ofHole'+y+'" class="alldataPopupGreen_delete_ofHole" value="'+arr_greenDelete[y]+'" type="hidden">';
                str += '<td class="text-center"><a href="javascript:void(0);" class="btn btn-info btn-sm" onclick="editGreen(\''+y+'\')"><i class="fa fa-fw fa-edit"></i>編集</a> <a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="deleteGreen(\''+y+'\',\'1\')"><i class="fa fa-fw fa-remove"></i>削除</a></td></tr>';
                y = (parseInt(y)+1) ;
            }
        });
        $("#appendGreen").append(str);
        initMapFocusTee('focus');
    }
}

function nextHole(){
    holeNext = $("#holeNext").val();
    orderNext = $("#orderNext").val();
    allOrderNext = $("#allOrderNext").val();
    courses = holeNext.split("_")[0];
    holes = holeNext.split("_")[1];
    //begin save hole old

    var status_hole = ["公開中","閉鎖中","破棄済"];
    var allGreenNew = $('.allGreenNew').length;
    var flg_orderCourse = $("#flg_orderCourse").val();
    var flg_orderHoleOfCourse = $("#flg_orderHoleOfCourse").val();
    //required form
    $(".error-message-hole").remove();
    flg = 0;
    //hole name
    if($("#hole_name_add").val() == ''){
        str = '<div class="error-message-hole">'+$("#hole_name_add").attr('title')+CHECK_NULL+'</div>';
        $("#hole_name_add").addClass('golfboderError');
        $(str).insertAfter("#"+$("#hole_name_add").attr('id')+"_required");
        flg = 1;
    }else{
        $("#hole_name_add").removeClass('golfboderError');
    }
    //par_hole_add
    if($("#par_hole_add").val() == ''){
        str = '<div class="error-message-hole">'+$("#par_hole_add").attr('title')+CHECK_NULL+'</div>';
        $("#par_hole_add").addClass('golfboderError');
        $(str).insertAfter("#"+$("#par_hole_add").attr('id')+"_required");
        flg = 1;
    }else{
        $("#par_hole_add").removeClass('golfboderError');
    }
    //tee_lat_hole_add
    if($("#tee_lat_hole_add").val() == ''){
        str = '<div class="error-message-hole">'+$("#tee_lat_hole_add").attr('title')+CHECK_NULL+'</div>';
        $("#tee_lat_hole_add").addClass('golfboderError');
        $(str).insertAfter("#"+$("#tee_lat_hole_add").attr('id')+"_required");
        flg = 1;
    }else{
        $("#tee_lat_hole_add").removeClass('golfboderError');
    }
    //tee_long_hole_add
    if($("#tee_long_hole_add").val() == ''){
        str = '<div class="error-message-hole">'+$("#tee_long_hole_add").attr('title')+CHECK_NULL+'</div>';
        $("#tee_long_hole_add").addClass('golfboderError');
        $(str).insertAfter("#"+$("#tee_long_hole_add").attr('id')+"_required");
        flg = 1;
    }else{
        $("#tee_long_hole_add").removeClass('golfboderError');
    }
    if(flg == 0){
        //required green
        var checkgreen = 0;
        $('.countGreenOfhole').each(function(){
            if (!$(this).hasClass("delete")) {
                checkgreen ++;
            }
        });
        if(checkgreen == 0){
            alert(GREEN_NOT_EXIT);
        }else{
            var flg_edithole = $("#flg_edithole").val();

            var hole_name_add = $("#hole_name_add").val();
            var status_hole_add = $("#status_hole_add").val();
            var tee_lat_hole_add = $("#tee_lat_hole_add").val();
            var par_hole_add = $("#par_hole_add").val();
            var tee_long_hole_add = $("#tee_long_hole_add").val();
            var countGreenOfhole = $('.countGreenOfhole').length;
            //edit course
            var str = '';

            //set view hole success edit
            $("#parHole_"+flg_edithole).html(par_hole_add);
            $("#greenHole_"+flg_edithole).html(checkgreen);
            $("#service_status_hole_of_courses_"+flg_edithole).html(status_hole[parseInt(status_hole_add) - 1]);
            coursesPopupTmp = flg_edithole.split("_")[0];
            flg_orderHoleOfCourse = flg_edithole.split("_")[1];
            //tinh lai green khi edit
            //set input hidden
            $(".box_edit_hole_"+flg_edithole).html('');

            str += '<input type="hidden" name="course['+flg_orderCourse+'][hole]['+flg_orderHoleOfCourse+'][hole_name_add]" class="alldataHole_ofCourse_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" id="hdd_hole_name_add_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" value="'+hole_name_add+'"/>';
            str += '<input type="hidden" name="course['+flg_orderCourse+'][hole]['+flg_orderHoleOfCourse+'][status_hole_add]" class="alldataHole_ofCourse_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" id="hdd_status_hole_add_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" value="'+status_hole_add+'"/>';
            str += '<input type="hidden" name="course['+flg_orderCourse+'][hole]['+flg_orderHoleOfCourse+'][tee_lat_hole_add]" class="alldataHole_ofCourse_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" id="hdd_tee_lat_hole_add_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" value="'+tee_lat_hole_add+'"/>';
            str += '<input type="hidden" name="course['+flg_orderCourse+'][hole]['+flg_orderHoleOfCourse+'][par_hole_add]" class="alldataHole_ofCourse_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" id="hdd_par_hole_add_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" value="'+par_hole_add+'"/>';
            str += '<input type="hidden" name="course['+flg_orderCourse+'][hole]['+flg_orderHoleOfCourse+'][tee_long_hole_add]" class="alldataHole_ofCourse_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" id="hdd_tee_long_hole_add_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" value="'+tee_long_hole_add+'"/>';
            str += '<input type="hidden" name="course['+flg_orderCourse+'][hole]['+flg_orderHoleOfCourse+'][allGreenOfHole]" class="alldataHole_ofCourse_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+' allGreenOfOnlyHole" id="hdd_allgreenofhole_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" value="'+checkgreen+'"/>';
            str += '<input type="hidden" name="course['+flg_orderCourse+'][hole]['+flg_orderHoleOfCourse+'][allParOfHole]" class="alldataHole_ofCourse_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+' allParOfOnlyHole_'+flg_orderCourse+'"  id="hdd_allparofhole_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" value="'+par_hole_add+'"/>';
            //lấy lại hidden data holes
            str += '<input id="hdd_hole_id_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" name="course['+flg_orderCourse+'][hole]['+flg_orderHoleOfCourse+'][hole_id]" class="" value="'+$("#popup_hdd_hole_id_"+flg_edithole).val()+'" type="hidden"/>';
            str += '<input id="hdd_version_hole_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" name="course['+flg_orderCourse+'][hole]['+flg_orderHoleOfCourse+'][version]" class="" value="'+$("#popup_hdd_version_hole_"+flg_edithole).val()+'" type="hidden"/>';
            str += '<input id="hdd_delete_hole_add_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" name="course['+flg_orderCourse+'][hole]['+flg_orderHoleOfCourse+'][delete]" class="" value="'+$("#popup_hdd_delete_hole_add_"+flg_edithole).val()+'" type="hidden"/>';
            //green_id, version, delete
            $('.alldataPopupGreen_id_ofHole').each(function(index){
                idGreen = $(this).attr('id').replace("alldataPopupGreen_id_ofHole", "");
                str += '<input class="alldataHole_ofCourse_'+flg_edithole+' alldataGreen_id_ofHole_'+flg_edithole+'" name="course['+coursesPopupTmp+'][hole]['+flg_orderHoleOfCourse+'][latlong][green_id_'+idGreen+']" value="'+$(this).val()+'" type="hidden">';
            });
            $('.alldataPopupGreen_version_ofHole').each(function(index){
                versionGreen = $(this).attr('id').replace("alldataPopupGreen_version_ofHole", "");
                str += '<input class="alldataHole_ofCourse_'+flg_edithole+' alldataGreen_version_ofHole_'+flg_edithole+'" name="course['+coursesPopupTmp+'][hole]['+flg_orderHoleOfCourse+'][latlong][version_'+versionGreen+']" value="'+$(this).val()+'" type="hidden">';
            });
            $('.alldataPopupGreen_delete_ofHole').each(function(index){
                delGreen = $(this).attr('id').replace("alldataPopupGreen_delete_ofHole", "");
                str += '<input class="alldataHole_ofCourse_'+flg_edithole+' alldataGreen_delete_ofHole_'+flg_edithole+'" name="course['+coursesPopupTmp+'][hole]['+flg_orderHoleOfCourse+'][latlong][delete_'+delGreen+']" value="'+$(this).val()+'" type="hidden">';
            });
            //green of hole
            $('.allGreenNew').each(function(){
                str += '<input type="hidden" name="course['+flg_orderCourse+'][hole]['+flg_orderHoleOfCourse+'][latlong]['+$(this).attr('id')+']" class="alldataHole_ofCourse_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+' alldataGreen_ofHole_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" id="hdd_lat_long_hole_add_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'_'+$(this).attr('id')+'" value="'+$(this).val()+'"/>';
            });

            $(".box_edit_hole_"+flg_edithole).append(str);
            countParHole();
            //success save hole edit

            //begin load hole next
            var newOrderNext = parseInt(orderNext)+1;
            var getOrderNext = 0;
            var id = '';
            $('.allHoleOfCourse_'+courses).each(function(){
                if (!$(this).hasClass("hole_deleted")) {
                    getOrderNext ++ ;
                    if(getOrderNext == newOrderNext){
                        id = $(this).attr('id').replace("onlyholeOfcourse_", "");
                    }
                }
            });
            //common ?
            $("#flgAdd").val('0');
            $("#flgEdit").val('flgEdit');
            $("#flg_edithole").val(id);
            $("#orderGreen").val('1');
            $("#hole_name_add").val($("#hdd_hole_name_add_"+id).val());
            $("#par_hole_add").val($("#hdd_par_hole_add_"+id).val());
            $("#status_hole_add").val($("#hdd_status_hole_add_"+id).val());
            var hdd_tee_lat_hole_add = ($("#hdd_tee_lat_hole_add_"+id).val() != '' && $("#hdd_tee_lat_hole_add_"+id).val() != 0) ? $("#hdd_tee_lat_hole_add_"+id).val() : $("#lat").val()
            var hdd_tee_long_hole_add = ($("#hdd_tee_long_hole_add_"+id).val() != '' && $("#hdd_tee_long_hole_add_"+id).val() != 0) ? $("#hdd_tee_long_hole_add_"+id).val() : $("#long").val()
            $("#tee_lat_hole_add").val(hdd_tee_lat_hole_add);
            $("#tea_latitude").html(hdd_tee_lat_hole_add);
            $("#tee_long_hole_add").val(hdd_tee_long_hole_add);
            $("#tea_longitude").html(hdd_tee_long_hole_add);
            //green empty
            $("#appendGreen").html('');
            //lấy lại green
            var str = '';
            var y = 0;
            courses = id.split("_")[0];
            holes = id.split("_")[1];
            //show hide next hole
            var noAllHole = 0;
            var noHole = 0;
            $('.allHoleOfCourse_'+courses).each(function(){
                if (!$(this).hasClass("hole_deleted")) {
                    noAllHole ++ ;
                    if($(this).attr('id') == ('onlyholeOfcourse_'+id)){
                        noHole = noAllHole
                    }
                }
            });
            $("#nextHole").show();
            if(noHole < noAllHole){
                $("#btnNext").show();
            }
            $("#noHoleNext").html(noHole);
            $("#noHoleNextAll").html(noAllHole);
            if(noHole == noAllHole){
                $("#btnNext").hide();
            }
            $("#orderNext").val(noHole);
            $("#allOrderNext").val(noAllHole);
            $("#holeNext").val(id);
            //end show hide next hole

            //array green_id
            $("#hdd_data_ofHole_openpopup").html('');
            $("#hdd_data_ofHole_openpopup").append('<input id="popup_hdd_hole_id_'+id+'" class="" value="'+$("#hdd_hole_id_"+id).val()+'" type="hidden">');
            $("#hdd_data_ofHole_openpopup").append('<input id="popup_hdd_version_hole_'+id+'" class="" value="'+$("#hdd_version_hole_"+id).val()+'" type="hidden">');
            $("#hdd_data_ofHole_openpopup").append('<input id="popup_hdd_delete_hole_add_'+id+'" class="" value="'+$("#hdd_delete_hole_add_"+id).val()+'" type="hidden">');

            var arr_greenId = [];
            var arr_greenVersion = [];
            var arr_greenDelete = [];
            $(".alldataGreen_id_ofHole_"+id).each(function( index ) {
                arr_greenId[index] = $(this).val();
            });
            $(".alldataGreen_version_ofHole_"+id).each(function( index ) {
                arr_greenVersion[index] = $(this).val();
            });
            $(".alldataGreen_delete_ofHole_"+id).each(function( index ) {
                arr_greenDelete[index] = $(this).val();
            });
            $(".alldataGreen_ofHole_"+id).each(function( index ) {
                var x = (index+1);
                if((x-1)%3 == 0){
                    if(arr_greenDelete[y] == 1){
                        str += '<tr id="trGreen_'+y+'" class="countGreenOfhole delete" style="display: none">';
                    }else{
                        str += '<tr id="trGreen_'+y+'" class="countGreenOfhole">';
                    }

                    str += '<td class="text-center" id="ichi_'+y+'">'+$(this).val();
                    str += '<input id="ichi_hdd_'+y+'" class="allGreenNew" name="latlong[]" value="'+$(this).val()+'" type="hidden">';
                    str += '</td>';
                }
                if((x+1)%3 == 0){
                    str += '<td class="text-center" id="ni_'+y+'">'+$(this).val()+'<input id="ni_hdd_'+y+'" class="allGreenNew" name="latlong[]" value="'+$(this).val()+'" type="hidden"></td>';
                }
                if(x%3 == 0){
                    str += '<td class="text-center" id="san_'+y+'">'+$(this).val();
                    str += '<input id="san_hdd_'+y+'" class="allGreenNew" name="latlong[]" value="'+$(this).val()+'" type="hidden"></td>';
                    str += '<input id="alldataPopupGreen_id_ofHole'+y+'" class="alldataPopupGreen_id_ofHole" value="'+arr_greenId[y]+'" type="hidden">';
                    str += '<input id="alldataPopupGreen_version_ofHole'+y+'" class="alldataPopupGreen_version_ofHole" value="'+arr_greenVersion[y]+'" type="hidden">';
                    str += '<input id="alldataPopupGreen_delete_ofHole'+y+'" class="alldataPopupGreen_delete_ofHole" value="'+arr_greenDelete[y]+'" type="hidden">';
                    str += '<td class="text-center"><a href="javascript:void(0);" class="btn btn-info btn-sm" onclick="editGreen(\''+y+'\')"><i class="fa fa-fw fa-edit"></i>編集</a> <a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="deleteGreen(\''+y+'\',\'1\')"><i class="fa fa-fw fa-remove"></i>削除</a></td></tr>';
                    y = (parseInt(y)+1) ;
                }
            });
            $("#appendGreen").append(str);
            initMapFocusTee('focus');
            //end load hole next
        }
    }
    //end save hole old
}
function submitFormGolf(){
    $(".error-message").remove();
    var flg = 0;
    $('.required').each(function(){
        if($(this).val() == ''){
            str = '<div class="error-message">'+$(this).attr('title')+CHECK_NULL+'</div>';
            $(this).addClass('golfboderError');
            $(str).insertAfter("#"+$(this).attr('id')+"_required");
            flg = 1;
        }else{
            $(this).removeClass('golfboderError');
        }
    });
    //validation course
    var allCourse = 0;
    $('.allCourse').each(function(){
        if (!$(this).hasClass("course_deleted")) {
            allCourse  ++;
        }
    });
    if(allCourse == 0){
        $('.error_course').show();
        $('.error_course').html(EMPTY_COURSE);
        flg = 1;
    }else{
        $('.error_course').hide();
        $('.allCourse').each(function(){
            if (!$(this).hasClass("course_deleted")) {
                var x = 0;
                var class_id = $(this).attr('id');
                var id = class_id.replace("course_", "");
                $('.allHoleOfCourse_'+id).each(function(){
                    //count hole of 1 course
                    if (!$(this).hasClass("hole_deleted")) {
                        x = 1;
                    }
                });
                if(x == 0){
                    //course id chưa tạo hole
                    $('.error_course').show();
                    var lblcourse_name = $('#lblcourse_name_'+id).html();
                    $('.error_course').html('コース'+'（'+lblcourse_name+'）'+NOT_HOLE_OF_COURSE);
                    flg = 1;
                    return false;
                }
            }
        });
    }
    //validation hole
    var allHoleAllCourse = $('.allHoleAllCourse').length;
    if(allHoleAllCourse == 0){
        $('.error_hole').show();
        $('.error_hole').html(EMPTY_HOLE);
        flg = 1;
    }else{
        $('.error_hole').hide();
    }
    if(flg ==0){
        //submit
        $("#submitForm").get(0).submit();
    }
}
