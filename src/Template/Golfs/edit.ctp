<?php $this->assign('title', $title); ?>

<?php $this->start('content_header'); ?>
<?= $this->element('content_header', ['controlName' => __('Golf course management'), 'title' => $title, 'icon' => 'fa fa-table']) ?>
<?php $this->end(); ?>
<div class="row">
    <?= $this->Form->create('golfs', ['class' => 'form-horizontal', 'id' => 'submitForm']); ?>
    <?= $this->Form->hidden('golf[field_id]', ['id' => 'field_id', 'value' => $golf['field_id'], 'class' => '']) ?>
    <?= $this->Form->hidden('golf[version]', ['id' => 'golf_version', 'value' => $golf['version'], 'class' => '']) ?>
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"><?= __('Basic information') ?></h3>
            </div>
            <div class="box-body">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <?= $this->Form->label('field_name', __('field_name'), ['class' => 'col-sm-2 control-label']) ?>
                            <div class="col-sm-7">
                                <div class="required-field-block">
                                    <?= $this->Form->text('golf[field_name]', ['id' => 'field_name', 'title' => 'ゴルフ場名', 'value' => $golf['field_name'], 'class' => 'form-control input-sm required']) ?>
                                    <div class="required-icon" data-original-title="" title="" id="field_name_required">
                                        <div class="text">*</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <?= $this->Form->label('field_name_kana', __('field_name_kana'), ['class' => 'col-sm-2 control-label']) ?>
                            <div class="col-sm-7">
                                <?= $this->Form->text('golf[field_name_kana]', ['id' => 'field_name_kana', 'value' => $golf['field_name_kana'], 'class' => 'form-control input-sm']) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <?= $this->Form->label('field_name_en', __('field_name_en'), ['class' => 'col-sm-2 control-label']) ?>
                            <div class="col-sm-7">
                                <?= $this->Form->text('golf[field_name_en]', ['id' => 'field_name_en', 'value' => $golf['field_name_en'], 'class' => 'form-control input-sm', 'maxlength' => 128]) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <?= $this->Form->label('version', __('version'), ['class' => 'col-sm-2 control-label']) ?>
                            <div class="col-sm-7">
                                <div class="required-field-block">
                                    <?= $this->Form->text('golf[version]', ['id' => 'version', 'value' => $golf['version'],'title' => 'バージョン', 'class' => 'form-control input-sm required', 'maxlength' => 2, 'onkeypress' => 'return inputNumber(event)']) ?>
                                    <div class="required-icon" data-original-title="" title="" id="version_required">
                                        <div class="text">*</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <?= $this->Form->label('service_status', __('service_status'), ['class' => 'col-sm-2 control-label']) ?>
                            <div class="col-sm-7">
                                <?= $this->Form->select('golf[service_status]', $service_status, ['class' => 'form-control input-sm', 'value' => $golf['service_status'], 'maxlength' => 255]) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <?= $this->Form->label('shotnavi_url', __('Shot Navi URL'), ['class' => 'col-sm-2 control-label']) ?>
                            <div class="col-sm-7">
                                <?= $this->Form->text('golf[shotnavi_url]', ['id' => 'shotnavi_url', 'value' => $golf['shotnavi_url'], 'class' => 'form-control input-sm', 'style' => 'float: left; ']) ?>
                            </div>
                            <?php if($golf['shotnavi_url'] != ''){ ?>
                                <a href="<?php echo $golf['shotnavi_url'];?>" class="btn input-sm btn-primary" target="_blank" style="padding-top: 4px !important;">LINK</a>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <?= $this->Form->label('nation', __('nation'), ['class' => 'col-sm-2 control-label']) ?>
                            <div class="col-sm-7">
                                <div class="required-field-block">
                                    <?= $this->Form->select('nation', $list_nation, ['class' => 'form-control input-sm required', 'value' => $nationold, 'title' => '国', 'empty' => '---', 'id' => 'nation']) ?>
                                    <div class="required-icon" data-original-title="" title="" id="nation_required">
                                        <div class="text">*</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <?= $this->Form->label('prefecture_id', __('prefecture'), ['class' => 'col-sm-2 control-label']) ?>
                            <div class="col-sm-7">
                                <div class="required-field-block">
                                    <?= $this->Form->select('golf[prefecture_id]', $list_pre, ['class' => 'form-control input-sm required', 'value' => $golf['prefecture_id'], 'title'=>'エリア', 'empty' => '---', 'id' => 'prefecture']) ?>
                                    <div class="required-icon" data-original-title="" title="" id="prefecture_required">
                                        <div class="text">*</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <?= $this->Form->label('address', __('address'), ['class' => 'col-sm-2 control-label']) ?>
                            <div class="col-sm-7">
                                <div class="required-field-block">
                                    <?= $this->Form->text('golf[address]', ['id' => 'address', 'value' => $golf['address'], 'title' => '住所', 'class' => 'form-control input-sm required', 'maxlength' => 255, 'onblur' => 'getlatlongByaddress(this)']) ?>
                                    <div class="required-icon" data-original-title="" title="" id="address_required">
                                        <div class="text">*</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <?= $this->Form->label('phone', __('phone'), ['class' => 'col-sm-2 control-label']) ?>
                            <div class="col-sm-7">
                                <?= $this->Form->text('golf[phone]', ['id' => 'phone', 'value' => $golf['phone'], 'class' => 'form-control input-sm', 'maxlength' => 128, 'onkeypress' => 'return inputPhone(event)']) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <?= $this->Form->label('field_lat', __('field_lat'), ['class' => 'col-sm-2 control-label']) ?>
                            <div class="col-sm-7">
                                <div class="required-field-block">
                                    <?= $this->Form->text('golf[field_lat]', ['id' => 'lat', 'value' => $golf['field_lat'], 'title' => '位置LAT', 'class' => 'form-control input-sm required', 'maxlength' => 25, 'onkeypress' => 'return inputLatlong(event)']) ?>
                                    <div class="required-icon" data-original-title="" title="" id="lat_required">
                                        <div class="text">*</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <?= $this->Form->label('field_long', __('field_long'), ['class' => 'col-sm-2 control-label']) ?>
                            <div class="col-sm-7">
                                <div class="required-field-block">
                                    <?= $this->Form->text('golf[field_long]', ['id' => 'long', 'title' => '位置LONG', 'value' => $golf['field_long'], 'class' => 'form-control input-sm required', 'maxlength' => 25, 'onkeypress' => 'return inputLatlong(event)']) ?>
                                    <div class="required-icon" data-original-title="" title="" id="long_required">
                                        <div class="text">*</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <?= __('Course list') ?>
                </h3>
                <h3 class="box-title error_course" style="margin-left: 100px; color: #dd4b39; display: none;"></h3>
            </div>
            <div class="box-body">
                <!-- list courses -->
                <?php if(count($coursesListData) > 0 ){ ?>
                    <?php foreach($coursesListData as $key => $value) { ?>
                        <div class="box allCourse" id="course_<?php echo $value['course_id']; ?>">
                            <div class="box-body <?php if($key == 0){?>bkChecked<?php } ?>">
                                <label class="control-label" id="lblcourse_name_<?php echo $value['course_id']; ?>"><?php echo $value['course_name']; ?></label><div class="form-group"></div>
                                <div class="col-sm-1" style="width: 2%;"><input value="<?php echo $value['course_id']; ?>" class="allRadioCourse" name="allRadioCourse" <?php if($key == 0){?>checked="checked"<?php } ?> onclick="clRadioCourse('<?php echo $value["course_id"]; ?>')" type="radio"></div>
                                <div class="col-sm-3 "><span>ホール数：&#12288;<span id="hole_num_<?php echo $value['course_id']; ?>"><?php echo $value['hole_num']; ?></span></span></div>
                                <div class="col-sm-3"><span><?= __('par_count') ?>：&#12288;<span id="par_num_<?php echo $value['course_id']; ?>"><?php echo $value['par_num']; ?></span></span></div>
                                <div class="col-sm-3"><span>状態：&#12288;<span id="status_course_<?php echo $value['course_id']; ?>"><?php echo $service_status[$value["service_status"]]; ?></span></span></div>
                                <div class="col-sm-2">
                                    <a href="javascript:void(0);" class="btn btn-info btn-sm" onclick="editCourse('<?php echo $value["course_id"]; ?>')">
                                    <i class="fa fa-fw fa-edit"></i>編集</a>
                                    <a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="deleteCourse('<?php echo $value["course_id"]; ?>')">
                                    <i class="fa fa-fw fa-remove"></i>削除</a>
                                </div>
                            </div>
                            <input name="course[<?php echo $value['course_id']; ?>][course_id]" id="course_id_<?php echo $value['course_id']; ?>" value="<?php echo $value['course_id']; ?>" type="hidden">
                            <input name="course[<?php echo $value['course_id']; ?>][course_name]" id="course_name_<?php echo $value['course_id']; ?>" value="<?php echo $value['course_name']; ?>" type="hidden">
                            <input name="course[<?php echo $value['course_id']; ?>][course_name_kana]" id="course_name_kana_<?php echo $value['course_id']; ?>" value="<?php echo $value['course_name_kana']; ?>" type="hidden">
                            <input name="course[<?php echo $value['course_id']; ?>][course_name_en]" id="course_name_en_<?php echo $value['course_id']; ?>" value="<?php echo $value['course_name_en']; ?>" type="hidden">
                            <input name="course[<?php echo $value['course_id']; ?>][service_status]" id="hdd_status_course<?php echo $value['course_id']; ?>" value="<?php echo $value['service_status']; ?>" type="hidden">
                            <input name="course[<?php echo $value['course_id']; ?>][hole_num]" id="hdd_hole_num_course_<?php echo $value['course_id']; ?>" value="<?php echo $value['hole_num']; ?>" type="hidden">
                            <input name="course[<?php echo $value['course_id']; ?>][par_num]" id="hdd_par_num_course_<?php echo $value['course_id']; ?>" value="<?php echo $value['par_num']; ?>" type="hidden">
                            <input name="course[<?php echo $value['course_id']; ?>][version]" id="hdd_version_course_<?php echo $value['course_id']; ?>" value="<?php echo $value['version']; ?>" type="hidden">
                            <input name="course[<?php echo $value['course_id']; ?>][delete]" id="hdd_delete_course_<?php echo $value['course_id']; ?>" value="0" type="hidden">
                        </div>

                        <?php if($key == (count($coursesListData) - 1)){?>
                            <?= $this->Form->hidden('count_course', ['class' => 'form-control input-sm', 'maxlength' => 255, 'id' => 'count_course', 'value' => ($value['course_id'] + 1)]) ?>
                        <?php } ?>
                    <?php } ?>
                <?php }else{ ?>
                    <?= $this->Form->hidden('count_course', ['class' => 'form-control input-sm', 'maxlength' => 255, 'id' => 'count_course', 'value' => 0]) ?>
                <?php } ?>
                <?= $this->Form->button(__('Add course'), ['class' => 'btn btn-primary', 'id' => 'openCourse' ,'data-popup-open' => 'popup-1', 'type' => 'button', 'formnovalidate' => true, 'onclick' => 'openPopupCourse()']) ?>
                <input type="hidden" id="flg_editcourse" name="flg_editcourse" value="default" >
                <input type="hidden" id="flg_edithole" name="flg_edithole" value="default" >
                <input type="hidden" id="flg_orderCourse" name="flg_orderCourse" value="<?php if(isset($coursesListData[0]['course_id'])){ echo $coursesListData[0]['course_id'];} ?>" >
                <input type="hidden" id="flg_orderHole" name="flg_orderHole" value="0" >
                <input type="hidden" id="url_pre" name="" value="<?php echo $url_pre; ?>" >
                <input type="hidden" id="url_latlong" name="" value="<?php echo $url_latlong; ?>" >
            </div>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <?= __('Hole list') ?>
                </h3>
                <h3 class="box-title error_hole" style="margin-left: 100px; color: #dd4b39; display: none;"></h3>
            </div>
            <div class="box-body" id="box-body-hole-all">
                <div id="hole-header">
                    <div class="col-sm-2"></div>
                    <div class="col-sm-3">
                        <h4><?= __('course_name') ?>: <span id="course_name_of_hole"><?php echo isset($coursesListData[0]["course_name"]) ? $coursesListData[0]["course_name"] : '' ; ?></span></h4>
                    </div>
                    <div class="col-sm-3">
                        <h4><?= __('hole_count') ?>:  <span id="lbl_hole_of_course"><?php echo isset($coursesListData[0]["hole_num"]) ? $coursesListData[0]["hole_num"] : '' ; ?></span></h4>
                    </div>
                    <div class="col-sm-4">
                        <h4><?= __('par_count') ?>:  <span id="lbl_par_of_course"><?php echo isset($coursesListData[0]["par_num"]) ? $coursesListData[0]["par_num"] : '' ;?></span></h4>
                    </div>
                </div>
                <div id="hole-body">
                    <!-- list holes -->
                    <?php $countGreenEdit = 0; ?>
                    <?php $countHoleEdit = 0; ?>
                    <?php if(count($coursesListData) > 0 ){ ?>

                        <?php foreach($coursesListData as $key => $value) { ?>
                            <?php foreach($value['holes'] as $h => $vh) { ?>
                                <?php $countHoleEdit ++; ?>
                                <div class="col-sm-3 allHoleAllCourse allHoleOfCourse_<?php echo $value['course_id'];?>" id="onlyholeOfcourse_<?php echo $value['course_id'];?>_<?php echo $countHoleEdit;?>" style="<?php if($key == 0){?>display: block<?php }else{?> display: none<?php } ?>">
                                    <div class="box">
                                        <div class="box-header with-border bkChecked" onclick="openEditPopupHole('<?php echo $value["course_id"];?>_<?php echo $countHoleEdit;?>')" style="cursor: pointer;">
                                            <h4 class="text-center">ホールNo <span id="orderHole_<?php echo $value["course_id"];?>_<?php echo $countHoleEdit;?>" class="orderHole_<?php echo $value["course_id"];?>"><?php echo ($h+1);?></span></h4>
                                        </div>
                                        <div class="box-body">
                                            <div class="text-center">
                                                <h5>パー：&#12288;<span id="parHole_<?php echo $value["course_id"];?>_<?php echo $countHoleEdit;?>"><?php echo $vh["par_num"];?></span></h5>
                                                <h5>状態： <span id="service_status_hole_of_courses_<?php echo $value["course_id"];?>_<?php echo $countHoleEdit;?>"><?php echo $service_status[$vh["service_status"]];?></span></h5>
                                                <h5>グリーン数：&#12288;<span id="greenHole_<?php echo $value["course_id"];?>_<?php echo $countHoleEdit;?>"><?php echo count($vh["greens"]);?></span></h5>
                                            </div>
                                        </div>
                                        <div class="box-footer">
                                            <button class="button-holedetail" formnovalidate="formnovalidate" type="button" style="background: red" onclick="deleteHoleOfcourse('<?php echo $value["course_id"];?>_<?php echo $countHoleEdit;?>')">削除</button>
                                        </div>
                                    </div>
                                    <div class="box_edit_hole_<?php echo $value["course_id"];?>_<?php echo $countHoleEdit;?>">
                                        <input name="course[<?php echo $value["course_id"];?>][hole][<?php echo $countHoleEdit;?>][hole_name_add]" class="alldataHole_ofCourse_<?php echo $value["course_id"];?>_<?php echo $countHoleEdit;?>" id="hdd_hole_name_add_<?php echo $value["course_id"];?>_<?php echo $countHoleEdit;?>" value="<?php echo $vh["hole_name"];?>" type="hidden">
                                        <input name="course[<?php echo $value["course_id"];?>][hole][<?php echo $countHoleEdit;?>][status_hole_add]" class="alldataHole_ofCourse_<?php echo $value["course_id"];?>_<?php echo $countHoleEdit;?>" id="hdd_status_hole_add_<?php echo $value["course_id"];?>_<?php echo $countHoleEdit;?>" value="<?php echo $vh["service_status"];?>" type="hidden">
                                        <input name="course[<?php echo $value["course_id"];?>][hole][<?php echo $countHoleEdit;?>][tee_lat_hole_add]" class="alldataHole_ofCourse_<?php echo $value["course_id"];?>_<?php echo $countHoleEdit;?>" id="hdd_tee_lat_hole_add_<?php echo $value["course_id"];?>_<?php echo $countHoleEdit;?>" value="<?php echo $vh["tee_lat"];?>" type="hidden">
                                        <input name="course[<?php echo $value["course_id"];?>][hole][<?php echo $countHoleEdit;?>][par_hole_add]" class="alldataHole_ofCourse_<?php echo $value["course_id"];?>_<?php echo $countHoleEdit;?>" id="hdd_par_hole_add_<?php echo $value["course_id"];?>_<?php echo $countHoleEdit;?>" value="<?php echo $vh["par_num"];?>" type="hidden">
                                        <input name="course[<?php echo $value["course_id"];?>][hole][<?php echo $countHoleEdit;?>][tee_long_hole_add]" class="alldataHole_ofCourse_<?php echo $value["course_id"];?>_<?php echo $countHoleEdit;?>" id="hdd_tee_long_hole_add_<?php echo $value["course_id"];?>_<?php echo $countHoleEdit;?>" value="<?php echo $vh["tee_long"];?>" type="hidden">
                                        <input name="course[<?php echo $value["course_id"];?>][hole][<?php echo $countHoleEdit;?>][allGreenOfHole]" class="alldataHole_ofCourse_<?php echo $value["course_id"];?>_<?php echo $countHoleEdit;?> allGreenOfOnlyHole" id="hdd_allgreenofhole_<?php echo $value["course_id"];?>_<?php echo $countHoleEdit;?>" value="<?php echo count($vh["greens"]);?>" type="hidden">
                                        <input name="course[<?php echo $value["course_id"];?>][hole][<?php echo $countHoleEdit;?>][allParOfHole]" class="alldataHole_ofCourse_<?php echo $value["course_id"];?>_<?php echo $countHoleEdit;?> allParOfOnlyHole_<?php echo $value["course_id"];?>" id="hdd_allparofhole_<?php echo $value["course_id"];?>_<?php echo $countHoleEdit;?>" value="<?php echo $vh["par_num"];?>" type="hidden">
                                        <input name="course[<?php echo $value["course_id"];?>][hole][<?php echo $countHoleEdit;?>][hole_id]" class="" id="hdd_hole_id_<?php echo $value["course_id"];?>_<?php echo $countHoleEdit;?>" value="<?php echo $vh["hole_id"];?>" type="hidden">
                                        <input name="course[<?php echo $value["course_id"];?>][hole][<?php echo $countHoleEdit;?>][version]" class="" id="hdd_version_hole_<?php echo $value["course_id"];?>_<?php echo $countHoleEdit;?>" value="<?php echo $vh["version"];?>" type="hidden">
                                        <input name="course[<?php echo $value["course_id"];?>][hole][<?php echo $countHoleEdit;?>][delete]" class="" id="hdd_delete_hole_add_<?php echo $value["course_id"];?>_<?php echo $countHoleEdit;?>" value="0" type="hidden">
                                        <!-- list greens of hole -->
                                        <?php foreach($vh['greens'] as $g => $vg) { ?>
                                            <?php $countGreenEdit ++; ?>
                                            <input name="course[<?php echo $value["course_id"];?>][hole][<?php echo $countHoleEdit;?>][latlong][ichi_hdd_<?php echo $countGreenEdit;?>]" class="alldataHole_ofCourse_<?php echo $value["course_id"];?>_<?php echo $countHoleEdit;?> alldataGreen_ofHole_<?php echo $value["course_id"];?>_<?php echo $countHoleEdit;?>" id="hdd_lat_long_hole_add_<?php echo $value["course_id"];?>_<?php echo $countHoleEdit;?>_ichi_hdd_<?php echo $countGreenEdit;?>" value="<?php echo $vg["front_lat"];?>, <?php echo $vg["front_long"];?>" type="hidden">
                                            <input name="course[<?php echo $value["course_id"];?>][hole][<?php echo $countHoleEdit;?>][latlong][ni_hdd_<?php echo $countGreenEdit;?>]" class="alldataHole_ofCourse_<?php echo $value["course_id"];?>_<?php echo $countHoleEdit;?> alldataGreen_ofHole_<?php echo $value["course_id"];?>_<?php echo $countHoleEdit;?>" id="hdd_lat_long_hole_add_<?php echo $value["course_id"];?>_<?php echo $countHoleEdit;?>_ni_hdd_<?php echo $countGreenEdit;?>" value="<?php echo $vg["centre_lat"];?>, <?php echo $vg["centre_long"];?>" type="hidden">
                                            <input name="course[<?php echo $value["course_id"];?>][hole][<?php echo $countHoleEdit;?>][latlong][san_hdd_<?php echo $countGreenEdit;?>]" class="alldataHole_ofCourse_<?php echo $value["course_id"];?>_<?php echo $countHoleEdit;?> alldataGreen_ofHole_<?php echo $value["course_id"];?>_<?php echo $countHoleEdit;?>" id="hdd_lat_long_hole_add_<?php echo $value["course_id"];?>_<?php echo $countHoleEdit;?>_san_hdd_<?php echo $countGreenEdit;?>" value="<?php echo $vg["back_lat"];?>, <?php echo $vg["back_long"];?>" type="hidden">
                                            <input name="course[<?php echo $value["course_id"];?>][hole][<?php echo $countHoleEdit;?>][latlong][green_id_<?php echo $countGreenEdit;?>]" class="alldataHole_ofCourse_<?php echo $value["course_id"];?>_<?php echo $countHoleEdit;?> alldataGreen_id_ofHole_<?php echo $value["course_id"];?>_<?php echo $countHoleEdit;?>" id="hdd_green_id_add_<?php echo $value["course_id"];?>_<?php echo $countHoleEdit;?>_<?php echo $countGreenEdit;?>" value="<?php echo $vg["green_id"];?>" type="hidden">
                                            <input name="course[<?php echo $value["course_id"];?>][hole][<?php echo $countHoleEdit;?>][latlong][version_<?php echo $countGreenEdit;?>]" class="alldataHole_ofCourse_<?php echo $value["course_id"];?>_<?php echo $countHoleEdit;?> alldataGreen_version_ofHole_<?php echo $value["course_id"];?>_<?php echo $countHoleEdit;?>" id="hdd_green_version_add_<?php echo $value["course_id"];?>_<?php echo $countHoleEdit;?>_<?php echo $countGreenEdit;?>" value="<?php echo $vg["version"];?>" type="hidden">
                                            <input name="course[<?php echo $value["course_id"];?>][hole][<?php echo $countHoleEdit;?>][latlong][delete_<?php echo $countGreenEdit;?>]" class="alldataHole_ofCourse_<?php echo $value["course_id"];?>_<?php echo $countHoleEdit;?> alldataGreen_delete_ofHole_<?php echo $value["course_id"];?>_<?php echo $countHoleEdit;?>" id="hdd_green_delete_add_<?php echo $value["course_id"];?>_<?php echo $countHoleEdit;?>_<?php echo $countGreenEdit;?>" value="0" type="hidden">
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php } ?>

                    <?php } ?>
                    <!--controll so luong hole-->
                    <input type="hidden" id="flg_orderHoleOfCourse" name="flg_orderHoleOfCourse" value="<?php echo ($countHoleEdit+1);?>" >
                    <input type="hidden" id="countGreen" name="countGreen" value="<?php echo ($countGreenEdit+1);?>" >
                    <div class="col-sm-3" id="beforeHole">
                        <?= $this->Form->button('<i class="fa fa-plus"></i> ', ['class' => 'button-addhole','data-popup-open-golf' => 'popup-2', 'type' => 'button', 'formnovalidate' => true, 'onclick' => 'openPopupHole()']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- start hole -->
    <div class="popup" id="popupHole" data-popup="popup-2" style="z-index: 9999">
        <div class="popup-inner-2">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header with-border">
                        <a class="popup-close" data-popup-close="popup-2" href="#">x</a>
                        <h3 class="box-title"><?= __('Basic information') ?></h3>
                    </div>
                    <div class="box-body">
                        <div class="form-group <?= $this->Form->isFieldError(null) ? 'has-error' : '' ?>">
                            <label class="col-sm-2 control-label" for=""><?= __('course_name') ?>： <span id="course_name_of_hole_popup"><?php echo isset($coursesListData[0]["course_name"]) ? $coursesListData[0]["course_name"] : '' ; ?></span></label>
                            <?= $this->Form->label(null, '', ['class' => 'col-sm-2 control-label', 'id' => 'lbl_coursename_pop_hole']) ?>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <?= $this->Form->label(null, __('hole_name'), ['class' => 'col-sm-2 control-label']) ?>
                                <div class="col-sm-7">
                                    <div class="required-field-block">
                                        <?= $this->Form->text(null, ['id' => 'hole_name_add', 'title' => __('hole_name'), 'class' => 'form-control input-sm required_hole', 'maxlength' => 255]) ?>
                                        <div class="required-icon" data-original-title="" title="" id="hole_name_add_required">
                                            <div class="text">*</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <?= $this->Form->label(null, __('state'), ['class' => 'col-sm-2 control-label']) ?>
                                <div class="col-sm-7">
                                    <?= $this->Form->select('status_hole_add', $service_status, ['class' => 'form-control input-sm', 'id' => 'status_hole_add', 'maxlength' => 255]) ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <?= $this->Form->label(null, __('tee_lat'), ['class' => 'col-sm-2 control-label']) ?>
                                <div class="col-sm-7">
                                    <div class="required-field-block">
                                        <?= $this->Form->text(null, ['id' => 'tee_lat_hole_add', 'title' => __('tee_lat'), 'class' => 'form-control input-sm required_hole', 'maxlength' => 25, 'onkeypress' => 'return inputLatlong(event)']) ?>
                                        <div class="required-icon" data-original-title="" title="" id="tee_lat_hole_add_required">
                                            <div class="text">*</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <?= $this->Form->label(null, __(''), ['class' => 'col-sm-2 control-label']) ?>
                                <div class="col-sm-7">
                                    <?= $this->Form->button(__('フォーカスティー'), ['class' => 'btn btn-primary', 'type' => 'button', 'formnovalidate' => true, 'onclick' => 'initMapFocusTee(\'focus\')']) ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= $this->Form->label(null, __('par_count'), ['class' => 'col-sm-2 control-label']) ?>
                                <div class="col-sm-7">
                                    <div class="required-field-block">
                                        <?= $this->Form->text(null, ['id' => 'par_hole_add','title' => 'パー数', 'class' => 'form-control input-sm required_hole', 'maxlength' => 1, 'onkeypress' => 'return inputNumber(event)']) ?>
                                        <div class="required-icon" data-original-title="" title="" id="par_hole_add_required">
                                            <div class="text">*</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <div class="col-sm-7">
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                </div>
                            </div>
                            <div class="form-group">
                                <?= $this->Form->label('field_long', __('tee_long'), ['class' => 'col-sm-2 control-label']) ?>
                                <div class="col-sm-7">
                                    <div class="required-field-block">
                                        <?= $this->Form->text('field_long', ['id' => 'tee_long_hole_add', 'title' => __('tee_long'), 'class' => 'form-control input-sm required_hole', 'maxlength' => 25, 'onkeypress' => 'return inputLatlong(event)']) ?>
                                        <div class="required-icon" data-original-title="" title="" id="tee_long_hole_add_required">
                                            <div class="text">*</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



                    </div>
                </div>
            </div>
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?= __('Green list') ?></h3>
                    </div>
                    <div class="box-body">
                        <div class="dataTables_wrapper form-inline dt-bootstrap">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="box-body table-responsive no-padding">
                                        <div id="hdd_data_ofHole_openpopup"></div>
                                        <table class="table table-bordered table-striped dataTable">
                                            <thead>
                                            <tr>
                                                <!--<th class="text-center">GNo</th>-->
                                                <th class="text-center"><?= __('front_position') ?></th>
                                                <th class="text-center"><?= __('center_position') ?></th>
                                                <th class="text-center"><?= __('back_position') ?></th>
                                                <!--<th class="text-center"><?= $this->Form->button('<i class="fa fa-fw fa-plus"></i>' . __('追加'), ['class' => 'btn btn-primary btn-xs', 'type' => 'button', 'onclick' => 'newGreen();', 'escape' => false]) ?></th>-->

                                            </tr>
                                            </thead>
                                            <tbody id="appendGreen">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12">
                <div class="box">
                    <!--<div class="box-header with-border">
                        <div class="col-md-4">
                            <h3 class="box-title"><?= __('Setting object') ?>:　<span onclick="teaCenter();"><?= __('tee') ?></span></h3>
                        </div>
                        <div class="col-md-4">
                            <h3 class="box-title"><?= __('tee_lat') ?>:　<span name=""  value=""  class="w100" id="tea_latitude"  title="緯度" >42.68812316124555</span></h3>
                        </div>
                        <div class="col-md-4">
                            <h3 class="box-title"><?= __('tee_long') ?>: <span name=""  value=""  class="w100" id="tea_longitude"   title="緯度" >141.65568531282804</span></h3>
                        </div>
                        <div class="col-md-8">
                            <h3 class="box-title" style="color :red">指示：　Công sửa message thành: Click chuột phải để đánh dấu green nhe </h3>
                        </div>
                        <div class="col-md-4">
                            <?= $this->Form->button(__('Position determination'), ['class' => 'btn btn-primary col-sm-6','type' => 'button', 'onclick' => 'getGreen();']) ?>
                        </div>
                    </div>-->
                    <div class="box-body">
                        <div id="googleMaps" style="height: 700px; width: 78%; float: left">
                            <!-- start maps -->
                            <input type="hidden" id="maxgreen" name="maxgreen" value="0" >
                            <input type="hidden" id="flgAdd" name="flgAdd" value="0" >
                            <input type="hidden" id="flgEdit" name="flgEdit" value="flgEdit" >
                            <input type="hidden" id="zoom_level" name="zoom_level" value="17"/>
                            <input type="hidden" id="orderGreen" name="orderGreen" value="1"/>
                            <!--<input type="hidden" id="tee_lat_hole_add" name="tee_lat_hole_add" value="42.68812316124555" />
                            <input type="hidden" id="tee_long_hole_add" name="tee_long_hole_add" value="141.65568531282804"/>-->
                            <div id="floating-panel" style="margin-bottom: 50px; display: none">
                                <input onclick="deleteMarkers();" type="button" value="Delete Markers">
                                <input onclick="editmap();" type="button" value="Edit maps">
                                <input onclick="addMap()" type="button" value="add new green">
                                <input type="text" id="test_green_latitude" name="test_green_latitude" value="xxx" />
                                <input type="text" id="test_green_longitude" name="test_green_longitude" value="xxx"/>
                            </div>
                            <div id="map" style="height: 100%;"></div>
                            <script>

                                var map;
                                var markers = [];
                                function initMap() {

                                    var tee_lat_hole_add = ($("#lat").val() == '' || $("#lat").val() == 0) ? '42.68812316124555' : $("#lat").val();
                                    var tee_long_hole_add = ($("#long").val() == '' || $("#long").val() == 0) ? '141.65568531282804' : $("#long").val();
                                    var haightAshbury = {lat: parseFloat(tee_lat_hole_add), lng: parseFloat(tee_long_hole_add)};
                                    //var haightAshbury = {lat: 42.68812316124555, lng: 141.65568531282804};
                                    map = new google.maps.Map(document.getElementById('map'), {
                                        zoom: 19,
                                        maxZoom: 19,
                                        center: haightAshbury,
                                        mapTypeId: 'satellite',
                                        mapTypeControlOptions: {
                                            mapTypeIds: ['satellite'],
                                        }
                                    });
                                    urlTee = 'http://maps.google.com/mapfiles/kml/paddle/T.png';
                                    marker = new google.maps.Marker({
                                        position: haightAshbury,
                                        map: map,
                                        icon: urlTee,
                                        draggable: true,
                                        title: 'ティ-'
                                    });
                                    //move marker
                                    google.maps.event.addListener(marker, 'dragend', function(a) {
                                        document.getElementById("tea_latitude").innerHTML = a.latLng.lat().toFixed(14);
                                        document.getElementById("tee_lat_hole_add").value = a.latLng.lat().toFixed(14);
                                        document.getElementById("tea_longitude").innerHTML = a.latLng.lng().toFixed(14);
                                        document.getElementById("tee_long_hole_add").value = a.latLng.lng().toFixed(14);
                                    });
                                    map.addListener('rightclick', function(event) {
                                        var maxgreen = document.getElementById("maxgreen").value;
                                        if(parseInt(maxgreen) < 3){
                                            // get lat/lon of click
                                            var clickLat = event.latLng.lat();
                                            var clickLon = event.latLng.lng();
                                            // show in input box
                                            document.getElementById("test_green_latitude").value = clickLat;
                                            document.getElementById("test_green_longitude").value = clickLon;
                                            addMarker(event.latLng,(parseInt(maxgreen)+1));
                                            document.getElementById("maxgreen").value = (parseInt(maxgreen)+1);

                                        }else{
                                            alert(MAX_GREEN);
                                        }
                                    });
                                    google.maps.event.addListener(map, 'zoom_changed', function () {
                                        zoomLevel = map.getZoom();
                                        document.getElementById("zoom_level").value = zoomLevel;
                                    });
                                    google.maps.event.addListener(marker, 'dblclick', function () {
                                        zoomLevel = map.getZoom() + 1;
                                        if (zoomLevel == 20) {
                                            zoomLevel = 10;
                                        }
                                        document.getElementById("zoom_level").value = zoomLevel;
                                        map.setZoom(zoomLevel);
                                    });

                                }
                                //focus Tee
                                function initMapFocusTee(focus) {
                                    if(focus == 'focus'){
                                        var tee_lat_hole_add = $("#tee_lat_hole_add").val();
                                        var tee_long_hole_add = $("#tee_long_hole_add").val();
                                    }else{
                                        var tee_lat_hole_add = $("#lat").val();
                                        var tee_long_hole_add = $("#long").val();
                                    }
                                    var haightAshbury = {lat: parseFloat(tee_lat_hole_add), lng: parseFloat(tee_long_hole_add)};
                                    //var haightAshbury = {lat: 42.68812316124555, lng: 141.65568531282804};
                                    map = new google.maps.Map(document.getElementById('map'), {
                                        zoom: 19,
                                        maxZoom: 19,
                                        center: haightAshbury,
                                        mapTypeId: 'satellite',
                                        mapTypeControlOptions: {
                                            mapTypeIds: ['satellite'],
                                        }
                                    });
                                    urlTee = 'http://maps.google.com/mapfiles/kml/paddle/T.png';
                                    marker = new google.maps.Marker({
                                        position: haightAshbury,
                                        map: map,
                                        icon: urlTee,
                                        draggable: true,
                                        title: 'ティ-'
                                    });
                                    //move marker
                                    google.maps.event.addListener(marker, 'dragend', function(a) {
                                        document.getElementById("tea_latitude").innerHTML = a.latLng.lat().toFixed(14);
                                        document.getElementById("tee_lat_hole_add").value = a.latLng.lat().toFixed(14);
                                        document.getElementById("tea_longitude").innerHTML = a.latLng.lng().toFixed(14);
                                        document.getElementById("tee_long_hole_add").value = a.latLng.lng().toFixed(14);
                                    });
                                    map.addListener('rightclick', function(event) {
                                        var maxgreen = document.getElementById("maxgreen").value;
                                        if(parseInt(maxgreen) < 3){
                                            // get lat/lon of click
                                            var clickLat = event.latLng.lat();
                                            var clickLon = event.latLng.lng();
                                            // show in input box
                                            document.getElementById("test_green_latitude").value = clickLat;
                                            document.getElementById("test_green_longitude").value = clickLon;
                                            addMarker(event.latLng,(parseInt(maxgreen)+1));
                                            document.getElementById("maxgreen").value = (parseInt(maxgreen)+1);

                                        }else{
                                            alert(MAX_GREEN);
                                        }
                                    });
                                    google.maps.event.addListener(map, 'zoom_changed', function () {
                                        zoomLevel = map.getZoom();
                                        document.getElementById("zoom_level").value = zoomLevel;
                                    });
                                    google.maps.event.addListener(marker, 'dblclick', function () {
                                        zoomLevel = map.getZoom() + 1;
                                        if (zoomLevel == 20) {
                                            zoomLevel = 10;
                                        }
                                        document.getElementById("zoom_level").value = zoomLevel;
                                        map.setZoom(zoomLevel);
                                    });

                                    clearMarkers();
                                    markers = [];
                                    //set  maxgreen null
                                    $("#maxgreen").val('0');
                                    $("#flgEdit").val('flgEdit');
                                    $("#orderGreen").val('1');
                                }
                                // Adds a marker to the map and push to the array.
                                function addMarker(location, title) {
                                    var orderGreen = $("#orderGreen").val();
                                    if(orderGreen == 1){
                                        title = 'フロント';
                                        url = 'http://maps.google.com/mapfiles/kml/paddle/F.png';
                                    }else if(orderGreen == 2){
                                        title = 'センター';
                                        url = 'http://maps.google.com/mapfiles/kml/paddle/C.png';
                                    }else if(orderGreen == 3){
                                        title = 'バック';
                                        url = 'http://maps.google.com/mapfiles/kml/paddle/B.png';
                                    }
                                    var marker = new google.maps.Marker({
                                        position: location,
                                        map: map,
                                        icon: url,
                                        draggable: true,
                                        title: title,
                                    });
                                    //move marker
                                    google.maps.event.addListener(marker, 'dragend', function(a) {
                                        document.getElementById("test_green_latitude").value = a.latLng.lat().toFixed(14);
                                        document.getElementById("test_green_longitude").value = a.latLng.lng().toFixed(14);
                                    });
                                    markers.push(marker);
                                    $("#orderGreen").val(parseInt(orderGreen) + 1);
                                    if(orderGreen == 3) $("#orderGreen").val('1');
                                }

                                // Sets the map on all markers in the array.
                                function setMapOnAll(map) {
                                    for (var i = 0; i < markers.length; i++) {
                                        markers[i].setMap(map);
                                    }
                                }

                                // Removes the markers from the map, but keeps them in the array.
                                function clearMarkers() {
                                    setMapOnAll(null);
                                }

                                // Shows any markers currently in the array.
                                function showMarkers() {
                                    setMapOnAll(map);
                                }

                                // Deletes all markers in the array by removing references to them.
                                function deleteMarkers() {
                                    document.getElementById("maxgreen").value = 0
                                    clearMarkers();
                                    markers = [];
                                }

                                function editmap() {
                                    document.getElementById("maxgreen").value = 3;

                                    clearMarkers();
                                    markers = [];

                                    var clickLat = '42.68819659082778';
                                    var clickLon = '141.65262132883072';
                                    // show in input box
                                    document.getElementById("test_green_latitude").value = clickLat;
                                    document.getElementById("test_green_longitude").value = clickLon;
                                    var items = [
                                        [42.68819659082778, 141.65262132883072],
                                        [42.688553443833825, 141.65286540985107],
                                        [42.688310941738024, 141.65340721607208]
                                    ];
                                    addMarkerEdit(items);
                                }
                                function addMarkerEdit(item) {
                                    for (var i = 0; i < item.length; i++) {
                                        if(i == 0){
                                            title = 'フロント';
                                            url = 'http://maps.google.com/mapfiles/kml/paddle/F.png';
                                        }else if(i == 1){
                                            title = 'センター';
                                            url = 'http://maps.google.com/mapfiles/kml/paddle/C.png';
                                        }else if(i == 2){
                                            title = 'バック';
                                            url = 'http://maps.google.com/mapfiles/kml/paddle/B.png';
                                        }
                                        var result = item[i][0].split(', ');
                                        var lati = result[0] > 0 ? result[0] : (parseFloat($("#tee_lat_hole_add").val()) + (i == 0 ? 0.00008 :(i == 1 ? 0.00016 : 0.00024)));
                                        var longi = result[1] > 0 ? result[1] : (parseFloat($("#tee_long_hole_add").val()) + (i == 0 ? 0.00008 :(i == 1 ? 0.00016 : 0.00024)));
                                        var marker = new google.maps.Marker({
                                            position: new google.maps.LatLng(lati, longi),
                                            map: map,
                                            icon: url,
                                            draggable: true,
                                            title: title,
                                        });
                                        //move marker
                                        google.maps.event.addListener(marker, 'dragend', function(a) {
                                            document.getElementById("test_green_latitude").value = a.latLng.lat().toFixed(14);
                                            document.getElementById("test_green_longitude").value = a.latLng.lng().toFixed(14);
                                        });
                                        markers.push(marker);
                                        if(i == 1){
                                            map.setCenter(marker.getPosition());
                                        }
                                    }
                                }
                                //js controll
                                function getGreen(){
                                    //edit green
                                    if($("#flgEdit").val() != 'flgEdit'){
                                        var countGreen = parseInt($("#flgEdit").val());
                                        for(k in markers){
                                            if(k==0){
                                                var ichi = markers[k].position.lat().toFixed(14) + ', ' + markers[k].position.lng().toFixed(14);
                                                $("#ichi_"+countGreen).html(ichi+'<input type="hidden" id="ichi_hdd_'+countGreen+'" class="allGreenNew" name="latlong[]" value="'+ichi+'" >');
                                            }
                                            if(k==1) {
                                                var ni = markers[k].position.lat().toFixed(14) + ', ' + markers[k].position.lng().toFixed(14);
                                                $("#ni_"+countGreen).html(ni+'<input type="hidden" id="ni_hdd_'+countGreen+'" class="allGreenNew" name="latlong[]" value="'+ni+'" >');
                                            }
                                            if(k==2) {
                                                var san = markers[k].position.lat().toFixed(14) + ', ' + markers[k].position.lng().toFixed(14);
                                                $("#san_"+countGreen).html(san+'<input type="hidden" id="san_hdd_'+countGreen+'" class="allGreenNew" name="latlong[]" value="'+san+'" >');
                                            }
                                        }
                                        $("#flgAdd").val('0');
                                        //delete green when add new success
                                        clearMarkers();
                                        markers = [];
                                        //set  maxgreen null
                                        $("#maxgreen").val('0');
                                        $("#flgEdit").val('flgEdit');
                                    }else{
                                        //add green
                                        //if($("#flgAdd").val() == 1){
                                        if($("#maxgreen").val() == 3){
                                            //if check added
                                            if($("#flgAdd").val() == 0){
                                                newGreen();
                                            }
                                            var countGreen = (parseInt($("#countGreen").val())-1);
                                            for(k in markers){
                                                if(k==0){
                                                    var ichi = markers[k].position.lat().toFixed(14) + ', ' + markers[k].position.lng().toFixed(14);
                                                    $("#ichi_"+countGreen).html(ichi+'<input type="hidden" id="ichi_hdd_'+countGreen+'" class="allGreenNew" name="latlong[]" value="'+ichi+'" >');
                                                }
                                                if(k==1) {
                                                    var ni = markers[k].position.lat().toFixed(14) + ', ' + markers[k].position.lng().toFixed(14);
                                                    $("#ni_"+countGreen).html(ni+'<input type="hidden" id="ni_hdd_'+countGreen+'" class="allGreenNew" name="latlong[]" value="'+ni+'" >');
                                                }
                                                if(k==2) {
                                                    var san = markers[k].position.lat().toFixed(14) + ', ' + markers[k].position.lng().toFixed(14);
                                                    $("#san_"+countGreen).html(san+'<input type="hidden" id="san_hdd_'+countGreen+'" class="allGreenNew" name="latlong[]" value="'+san+'" >');
                                                }
                                            }
                                            $("#flgAdd").val('0');
                                            //delete green when add new success
                                            clearMarkers();
                                            markers = [];
                                            //set  maxgreen null
                                            $("#maxgreen").val('0');
                                        }else{
                                            alert(MIN_GREEN);
                                        }
                                    }
                                    $("#orderGreen").val('1');
                                }
                                function newGreen(){
                                    if($("#flgEdit").val() != 'flgEdit'){
                                        $("#flgEdit").val('flgEdit');
                                        $("#maxgreen").val('0');
                                        clearMarkers();
                                        markers = [];
                                    }
                                    if($("#flgAdd").val() == 0){
                                        var countGreen = $("#countGreen").val();
                                        var str = '<tr id="trGreen_'+countGreen+'" class="countGreenOfhole">';
                                        str += '<td class="text-center" id="ichi_'+countGreen+'">設定してください</td>';
                                        str += '<td class="text-center" id="ni_'+countGreen+'">設定してください</td>';
                                        str += '<td class="text-center" id="san_'+countGreen+'">設定してください</td>';
                                        //fix bug 24-1-2018
                                        str += '<input id="alldataPopupGreen_id_ofHole'+countGreen+'" class="alldataPopupGreen_id_ofHole" value="0" type="hidden">';
                                        str += '<input id="alldataPopupGreen_version_ofHole'+countGreen+'" class="alldataPopupGreen_version_ofHole" value="1" type="hidden">';
                                        str += '<input id="alldataPopupGreen_delete_ofHole'+countGreen+'" class="alldataPopupGreen_delete_ofHole" value="0" type="hidden">';
                                        //end fix bug 24-1-2018
                                        str += '<td class="text-center"><a href="javascript:void(0);" class="btn btn-info btn-sm" onclick="editGreen('+"'" +countGreen+"'" +')"><i class="fa fa-fw fa-edit"></i>編集</a> <a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="deleteGreen('+"'" +countGreen+"'" +')"><i class="fa fa-fw fa-remove"></i>削除</a>';
                                        str += '</td></tr>';
                                        $("#appendGreen").append(str);
                                        $("#flgAdd").val('1');
                                        $("#countGreen").val(parseInt(countGreen)+1);
                                    }
                                }
                                function deleteGreen(x,y){
                                    if (confirm(CONFIRM_DELETE_GREEN)) {
                                        if (typeof y == 'undefined') y = 'default';
                                        if (y != 'default') {
                                            $("#trGreen_" + x).hide();
                                            $("#trGreen_" + x).addClass('delete');
                                            $("#alldataPopupGreen_delete_ofHole" + x).val('1');
                                        } else {
                                            $("#trGreen_" + x).remove();
                                        }
                                        var countGreen = $("#countGreen").val();
                                        if (parseInt(countGreen) == (parseInt(x) + 1)) {
                                            $("#flgAdd").val('0');
                                        }
                                        //$("#countGreen").val(parseInt(countGreen)-1);
                                        //delete when doing edit
                                        if ($("#flgEdit").val() == x) {
                                            $("#flgEdit").val('flgEdit');
                                            $("#maxgreen").val('0');
                                            clearMarkers();
                                            markers = [];
                                        }
                                    }
                                }
                                function editGreen(x){
                                    clearMarkers();
                                    markers = [];
                                    var items = [
                                        [$("#ichi_hdd_"+x).val()],
                                        [$("#ni_hdd_"+x).val()],
                                        [$("#san_hdd_"+x).val()]
                                    ];
                                    addMarkerEdit(items);
                                    $("#flgEdit").val(x);
                                    $("#maxgreen").val('3');
                                }
                                //center tea
                                function teaCenter(){
                                    var lat = $("#tee_lat_hole_add").val();
                                    var lng = $("#tee_long_hole_add").val();
                                    var haightAshbury = {lat: parseFloat(tee_lat_hole_add), lng: parseFloat(tee_long_hole_add)};

                                    var myLatLng=new google.maps.LatLng(lat, lng);
                                    map.setCenter(myLatLng);

                                }
                            </script>
                            <script async defer
                                    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCY2983uvNZSyCPHZTX3kKdzCX3_zK9xW8&callback=initMap">
                                // src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBsaEpF2pWu1imMGNvEIsbME1tvZq2wNnY&callback=initMap">
                            </script>
                            <!-- end maps -->
                        </div>
                        <div class="" style="width: 20%; float: left; padding-left: 10px;">
                            <div class="" style="margin-bottom: 60px">
                                <?= $this->Form->button(__('Position determination'), ['class' => 'btn btn-primary col-sm-6','style' => 'margin-left: 100px; ','type' => 'button', 'onclick' => 'getGreen();']) ?>
                            </div>
                            <div class="">
                                <h4 class="box-title"><?= __('Setting object') ?>:　<span onclick="teaCenter();"><?= __('tee') ?></span></h4>
                            </div>
                            <div class="">
                                <h4 class="box-title"><?= __('tee_lat') ?>:　<span name=""  value=""  class="w100" id="tea_latitude"  title="緯度" ></span></h4>
                            </div>
                            <div class="">
                                <h4 class="box-title"><?= __('tee_long') ?>: <span name=""  value=""  class="w100" id="tea_longitude"   title="緯度" ></span></h4>
                            </div>
                            <div class="">
                                <button class="btn btn-primary col-sm-6 btn-info" type="button" onclick="addHoleToCourse()" style="margin-left: 100px; margin-top: 20px"><?= __('Save') ?></button>
                            </div>
                            <input id="orderNext" value="" type="hidden">
                            <input id="allOrderNext" value="" type="hidden">
                            <input id="holeNext" value="" type="hidden">
                            <div class="" id="nextHole" style="margin-top: 120px; display: none">
                                <p style="width: 100%; ">ホールNo <span id="noHoleNext"></span> / <?= __('hole_count') ?> <span id="noHoleNextAll"></span></p>
                                <button class="btn btn-primary col-sm-6" type="button" onclick="nextHole()" id="btnNext" style="margin-left: 100px; margin-top: 20px">次ホールへ</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end hole-->
        </div>
    </div>
    <div class="col-xs-1">
        <?= $this->Form->button(__('Save'), ['type' => 'button', 'class' => 'btn btn-primary', 'formnovalidate' => true, 'onclick' =>'submitFormGolf();']) ?>
    </div>
    <?= $this->Form->end() ?>
    <div class="popup" id="popupCourse" data-popup="popup-1" style="z-index: 100">
        <div class="popup-inner">
            <!--<h2 class="text-center"><?= __('Add / edit course') ?></h2>-->
            <h2 class="text-center">コース<span id="editOraddCourse"></span></h2>
            <div class="form-group">
                <?= $this->Form->label(null, __('course_name'), ['class' => 'col-sm-4 control-label']) ?>
                <div class="col-sm-7">
                    <div class="required-field-block">
                        <?= $this->Form->text(null, ['class' => 'form-control input-sm requiredCourse', 'title' => __('course_name'), 'maxlength' => 255,'id' => 'course_name_add']) ?>
                        <div class="required-icon" data-original-title="" title="" id="course_name_add_required">
                            <div class="text">*</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <?= $this->Form->label(null, __('course_name_kana'), ['class' => 'col-sm-4 control-label']) ?>
                <div class="col-sm-7">
                    <?= $this->Form->text(null, ['class' => 'form-control input-sm', 'maxlength' => 255, 'id' => 'course_name_kana_add']) ?>
                </div>
            </div>
            <div class="form-group">
                <?= $this->Form->label(null, __('course_name_en'), ['class' => 'col-sm-4 control-label']) ?>
                <div class="col-sm-7">
                    <?= $this->Form->text(null, ['class' => 'form-control input-sm', 'maxlength' => 255, 'id' => 'course_name_en_add']) ?>
                </div>
            </div>
            <div class="form-group">
                <?= $this->Form->label(null, __('state'), ['class' => 'col-sm-4 control-label']) ?>
                <div class="col-sm-7">
                    <?= $this->Form->select('status_course', $service_status, ['class' => 'form-control input-sm', 'id' => 'sl_status_course']) ?>
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-2"></div>
                <?= $this->Form->button('<i class="fa fa-remove"></i> ' . __('Cancel'), ['class' => 'btn btn-primary col-sm-3','formnovalidate' => true, "data-popup-close" => "popup-1"]) ?>
                <div class="col-xs-2"></div>
                <?= $this->Form->button('<i class="fa fa-save"></i> ' . __('Save'), ['class' => 'btn btn-primary col-sm-3', 'type' => 'button','formnovalidate' => true, 'onclick' => 'addNewCourse()']) ?>
            </div>
            <a class="popup-close" data-popup-close="popup-1" href="#">x</a>
        </div>
    </div>
</div>
<style>
    .box {
        border : 1px solid #d2d6de;
    }
</style>

<?=
    $this->Html->script([
'dist/golf_edit'
])
?>