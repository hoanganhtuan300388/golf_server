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
        //edit course
        if(flg_editcourse != 'default'){
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
            str += '<div class="col-sm-2"><a href="javascript:void(0);" class="btn btn-info btn-sm" onclick="editCourse(\''+count_course+'\')"><i class="fa fa-fw fa-edit"></i>編集</a> <a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="deleteCourse(\''+count_course+'\')"><i class="fa fa-fw fa-remove"></i>削除</a></div></div>';
            str += '<input name="course['+count_course+'][course_name]" id="course_name_'+count_course+'" value="'+$("#course_name_add").val()+'" type="hidden">';
            str += '<input name="course['+count_course+'][course_name_kana]" id="course_name_kana_'+count_course+'" value="'+$("#course_name_kana_add").val()+'" type="hidden">';
            str += '<input name="course['+count_course+'][course_name_en]" id="course_name_en_'+count_course+'" value="'+$("#course_name_en_add").val()+'" type="hidden">';
            str += '<input name="course['+count_course+'][service_status]" id="hdd_status_course'+count_course+'" value="'+$("#sl_status_course").val()+'" type="hidden">';
            str += '<input name="course['+count_course+'][hole_num]" id="hdd_hole_num_course_'+count_course+'" value="0" type="hidden">';
            str += '<input name="course['+count_course+'][par_num]" id="hdd_par_num_course_'+count_course+'" value="0" type="hidden">';
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
function deleteCourse(id){
    $("#course_"+id).remove();
    var allCourse = $('.allCourse').length;
    if(allCourse == 0) $("#count_course").val('0');
    //Khi xoa đúng course đang checked
    $('.allRadioCourse').each(function(){
        if ($(this).prop('checked')) {
            if($(this).val() == id){
                $("#flg_orderCourse").val('0');
            }
        }
    });
    //delete hole of course
    $('.allHoleOfCourse_'+id).remove();

}
//deleteHole
function deleteHoleOfcourse(id){
    $("#onlyholeOfcourse_"+id).remove();


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
        if(allGreenNew == 0){
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
                $("#greenHole_"+flg_edithole).html(countGreenOfhole);
                $("#service_status_hole_of_courses_"+flg_edithole).html(status_hole[parseInt(status_hole_add) - 1]);
                flg_orderHoleOfCourse = flg_edithole.split("_")[1];
                //set input hidden
                $(".box_edit_hole_"+flg_edithole).html('');

                str += '<input type="hidden" name="course['+flg_orderCourse+'][hole]['+flg_orderHoleOfCourse+'][hole_name_add]" class="alldataHole_ofCourse_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" id="hdd_hole_name_add_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" value="'+hole_name_add+'"/>';
                str += '<input type="hidden" name="course['+flg_orderCourse+'][hole]['+flg_orderHoleOfCourse+'][status_hole_add]" class="alldataHole_ofCourse_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" id="hdd_status_hole_add_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" value="'+status_hole_add+'"/>';
                str += '<input type="hidden" name="course['+flg_orderCourse+'][hole]['+flg_orderHoleOfCourse+'][tee_lat_hole_add]" class="alldataHole_ofCourse_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" id="hdd_tee_lat_hole_add_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" value="'+tee_lat_hole_add+'"/>';
                str += '<input type="hidden" name="course['+flg_orderCourse+'][hole]['+flg_orderHoleOfCourse+'][par_hole_add]" class="alldataHole_ofCourse_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" id="hdd_par_hole_add_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" value="'+par_hole_add+'"/>';
                str += '<input type="hidden" name="course['+flg_orderCourse+'][hole]['+flg_orderHoleOfCourse+'][tee_long_hole_add]" class="alldataHole_ofCourse_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" id="hdd_tee_long_hole_add_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" value="'+tee_long_hole_add+'"/>';
                str += '<input type="hidden" name="course['+flg_orderCourse+'][hole]['+flg_orderHoleOfCourse+'][allGreenOfHole]" class="alldataHole_ofCourse_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+' allGreenOfOnlyHole" id="hdd_allgreenofhole_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" value="'+countGreenOfhole+'"/>';
                str += '<input type="hidden" name="course['+flg_orderCourse+'][hole]['+flg_orderHoleOfCourse+'][allParOfHole]" class="alldataHole_ofCourse_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+' allParOfOnlyHole_'+flg_orderCourse+'"  id="hdd_allparofhole_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" value="'+par_hole_add+'"/>';
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
                str += '<h5>グリーン数：&#12288;<span id="greenHole_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'">'+countGreenOfhole+'</span></h5>';
                str += '</div></div><div class="box-footer"><button class="button-holedetail" formnovalidate="formnovalidate" type="button" style="background: red" onclick="deleteHoleOfcourse(\''+flg_orderCourse+'_'+flg_orderHoleOfCourse+'\')">削除</button></div></div>';
                //hidden
                str += '<div class="box_edit_hole_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'">';
                str += '<input type="hidden" name="course['+flg_orderCourse+'][hole]['+flg_orderHoleOfCourse+'][hole_name_add]" class="alldataHole_ofCourse_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" id="hdd_hole_name_add_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" value="'+hole_name_add+'"/>';
                str += '<input type="hidden" name="course['+flg_orderCourse+'][hole]['+flg_orderHoleOfCourse+'][status_hole_add]" class="alldataHole_ofCourse_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" id="hdd_status_hole_add_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" value="'+status_hole_add+'"/>';
                str += '<input type="hidden" name="course['+flg_orderCourse+'][hole]['+flg_orderHoleOfCourse+'][tee_lat_hole_add]" class="alldataHole_ofCourse_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" id="hdd_tee_lat_hole_add_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" value="'+tee_lat_hole_add+'"/>';
                str += '<input type="hidden" name="course['+flg_orderCourse+'][hole]['+flg_orderHoleOfCourse+'][par_hole_add]" class="alldataHole_ofCourse_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" id="hdd_par_hole_add_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" value="'+par_hole_add+'"/>';
                str += '<input type="hidden" name="course['+flg_orderCourse+'][hole]['+flg_orderHoleOfCourse+'][tee_long_hole_add]" class="alldataHole_ofCourse_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" id="hdd_tee_long_hole_add_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" value="'+tee_long_hole_add+'"/>';
                str += '<input type="hidden" name="course['+flg_orderCourse+'][hole]['+flg_orderHoleOfCourse+'][allGreenOfHole]" class="alldataHole_ofCourse_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+' allGreenOfOnlyHole" id="hdd_allgreenofhole_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" value="'+countGreenOfhole+'"/>';
                str += '<input type="hidden" name="course['+flg_orderCourse+'][hole]['+flg_orderHoleOfCourse+'][allParOfHole]" class="alldataHole_ofCourse_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+' allParOfOnlyHole_'+flg_orderCourse+'"  id="hdd_allparofhole_'+flg_orderCourse+'_'+flg_orderHoleOfCourse+'" value="'+par_hole_add+'"/>';
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
        allHoleOfCourse ++;
    });
    $('.allParOfOnlyHole_'+flg_orderCourse).each(function(){
        allParOfOnlyHole = (parseInt(allParOfOnlyHole) + parseInt($(this).val()));
    });
    //order
    var i =1;
    $('.orderHole_'+flg_orderCourse).each(function(){
        $(this).html(i); i ++;
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
    $("#flgEdit").val('flgEdit');
    $("#orderGreen").val('1');
    var allRadioCourse = 0;
    $('.allRadioCourse').each(function(){
        if ($(this).prop('checked')) {
            allRadioCourse = $(this).val();
        }
    });
    if(allRadioCourse > 0){
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

//open map edit hole
function  openEditPopupHole(id){
    $("#flgAdd").val('0');
    $("#flgEdit").val('flgEdit');
    $("#popupHole").css('display','block');
    $("#flg_edithole").val(id);
    $("#orderGreen").val('1');
    $("#hole_name_add").val($("#hdd_hole_name_add_"+id).val());
    $("#par_hole_add").val($("#hdd_par_hole_add_"+id).val());
    $("#status_hole_add").val($("#hdd_status_hole_add_"+id).val());
    $("#tee_lat_hole_add").val($("#hdd_tee_lat_hole_add_"+id).val());
    $("#tea_latitude").html($("#hdd_tee_lat_hole_add_"+id).val());
    $("#tee_long_hole_add").val($("#hdd_tee_long_hole_add_"+id).val());
    $("#tea_longitude").html($("#hdd_tee_long_hole_add_"+id).val());
    //green empty
    $("#appendGreen").html('');
    //lấy lại green
    var str = '';
    var y = 0;
    $(".alldataGreen_ofHole_"+id).each(function( index ) {
        var x = (index+1);
        if((x-1)%3 == 0){
            str += '<tr id="trGreen_'+y+'" class="countGreenOfhole">';
            str += '<td class="text-center" id="ichi_'+y+'">'+$(this).val()+'<input id="ichi_hdd_'+y+'" class="allGreenNew" name="latlong[]" value="'+$(this).val()+'" type="hidden"></td>';
        }
        if((x+1)%3 == 0){
            str += '<td class="text-center" id="ni_'+y+'">'+$(this).val()+'<input id="ni_hdd_'+y+'" class="allGreenNew" name="latlong[]" value="'+$(this).val()+'" type="hidden"></td>';
        }
        if(x%3 == 0){
            str += '<td class="text-center" id="san_'+y+'">'+$(this).val()+'<input id="san_hdd_'+y+'" class="allGreenNew" name="latlong[]" value="'+$(this).val()+'" type="hidden"></td>';
            str += '<td class="text-center"><a href="javascript:void(0);" class="btn btn-info btn-sm" onclick="editGreen(\''+y+'\')"><i class="fa fa-fw fa-edit"></i>編集</a> <a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="deleteGreen(\''+y+'\')"><i class="fa fa-fw fa-remove"></i>削除</a></td></tr>';
            y = (parseInt(y)+1) ;
        }
    });

    courses = id.split("_")[0];
    holes = id.split("_")[1];
    $('#course_name_of_hole_popup').html($("#course_name_"+courses).val());

    $("#appendGreen").append(str);
    initMapFocusTee('focus');
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
    var allCourse = $('.allCourse').length;
    if(allCourse == 0){
        $('.error_course').show();
        $('.error_course').html(EMPTY_COURSE);
        flg = 1;
    }else{
        $('.error_course').hide();
        $('.allCourse').each(function(){
            var class_id = $(this).attr('id');
            var id = class_id.replace("course_", "");
            //count hole of 1 course
            if($(".allHoleOfCourse_"+id).length == 0){
                //course id chưa tạo hole
                $('.error_course').show();
                var lblcourse_name = $('#lblcourse_name_'+id).html();
                $('.error_course').html('コース'+'（'+lblcourse_name+'）'+NOT_HOLE_OF_COURSE);
                flg = 1;
                return false;
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
