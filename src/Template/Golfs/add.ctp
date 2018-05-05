<?php $this->assign('title', $title); ?>

<?php $this->start('content_header'); ?>
    <?= $this->element('content_header', ['controlName' => __('Golf course management'), 'title' => $title, 'icon' => 'fa fa-table']) ?>
<?php $this->end(); ?>
<div class="row">
    <?= $this->Form->create('golfs', ['class' => 'form-horizontal', 'id' => 'submitForm']); ?>
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
                                        <?= $this->Form->text('golf[field_name]', ['id' => 'field_name', 'title' => __('field_name'), 'class' => 'form-control input-sm required']) ?>
                                        <div class="required-icon" data-original-title="" title="" id="field_name_required">
                                            <div class="text">*</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <?= $this->Form->label('field_name_kana', __('field_name_kana'), ['class' => 'col-sm-2 control-label']) ?>
                                <div class="col-sm-7">
                                    <?= $this->Form->text('golf[field_name_kana]', ['id' => 'field_name_kana', 'class' => 'form-control input-sm']) ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <?= $this->Form->label('field_name_en', __('field_name_en'), ['class' => 'col-sm-2 control-label']) ?>
                                <div class="col-sm-7">
                                    <?= $this->Form->text('golf[field_name_en]', ['id' => 'field_name_en', 'class' => 'form-control input-sm', 'maxlength' => 128]) ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <?= $this->Form->label('version', __('version'), ['class' => 'col-sm-2 control-label']) ?>
                                <div class="col-sm-7">
                                    <div class="required-field-block">
                                        <?= $this->Form->text('golf[version]', ['id' => 'version','title' => 'バージョン', 'class' => 'form-control input-sm required', 'maxlength' => 2, 'onkeypress' => 'return inputNumber(event)']) ?>
                                        <div class="required-icon" data-original-title="" title="" id="version_required">
                                            <div class="text">*</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <?= $this->Form->label('service_status', __('service_status'), ['class' => 'col-sm-2 control-label']) ?>
                                <div class="col-sm-7">
                                    <?= $this->Form->select('golf[service_status]', $service_status, ['class' => 'form-control input-sm', 'maxlength' => 255]) ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= $this->Form->label('nation', __('nation'), ['class' => 'col-sm-2 control-label']) ?>
                                <div class="col-sm-7">
                                    <div class="required-field-block">
                                        <?= $this->Form->select('nation', $list_nation, ['class' => 'form-control input-sm required', 'title' => __('nation'), 'empty' => '---', 'id' => 'nation']) ?>
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
                                        <?= $this->Form->select('golf[prefecture_id]', $list_pre, ['class' => 'form-control input-sm required', 'title' => __('prefecture'), 'empty' => '---', 'id' => 'prefecture']) ?>
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
                                        <?= $this->Form->text('golf[address]', ['id' => 'address', 'title' => __('address'), 'class' => 'form-control input-sm required', 'maxlength' => 255, 'onblur' => 'getlatlongByaddress(this)']) ?>
                                        <div class="required-icon" data-original-title="" title="" id="address_required">
                                            <div class="text">*</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <?= $this->Form->label('phone', __('phone'), ['class' => 'col-sm-2 control-label']) ?>
                                <div class="col-sm-7">
                                    <?= $this->Form->text('golf[phone]', ['id' => 'phone', 'class' => 'form-control input-sm', 'maxlength' => 128, 'onkeypress' => 'return inputPhone(event)']) ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <?= $this->Form->label('field_lat', __('field_lat'), ['class' => 'col-sm-2 control-label']) ?>
                                <div class="col-sm-7">
                                    <div class="required-field-block">
                                        <?= $this->Form->text('golf[field_lat]', ['id' => 'lat', 'title' => __('field_lat'), 'class' => 'form-control input-sm required', 'maxlength' => 25, 'onkeypress' => 'return inputLatlong(event)']) ?>
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
                                        <?= $this->Form->text('golf[field_long]', ['id' => 'long', 'title' => __('field_long'), 'class' => 'form-control input-sm required', 'maxlength' => 25, 'onkeypress' => 'return inputLatlong(event)']) ?>
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
                <?= $this->Form->button(__('コース追加'), ['class' => 'btn btn-primary', 'id' => 'openCourse' ,'data-popup-open' => 'popup-1', 'type' => 'button', 'formnovalidate' => true, 'onclick' => 'openPopupCourse()']) ?>
                <input type="hidden" id="flg_editcourse" name="flg_editcourse" value="default" >
                <input type="hidden" id="flg_edithole" name="flg_edithole" value="default" >
                <input type="hidden" id="flg_orderCourse" name="flg_orderCourse" value="0" >
                <input type="hidden" id="flg_orderHole" name="flg_orderHole" value="0" >
                <input type="hidden" id="flg_orderHoleOfCourse" name="flg_orderHoleOfCourse" value="0" >
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
                       <h4><?= __('course_name') ?>: <span id="course_name_of_hole"></span></h4>
                    </div>
                    <div class="col-sm-3">
                       <h4><?= __('hole_count') ?>:  <span id="lbl_hole_of_course"></span></h4>
                    </div>
                    <div class="col-sm-4">
                       <h4><?= __('par_count') ?>:  <span id="lbl_par_of_course"></span></h4>
                    </div>
                </div>
                <div id="hole-body">

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
                            <label class="col-sm-2 control-label" for=""><?= __('course_name') ?>： <span id="course_name_of_hole_popup"></span></label>
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
                                        <?= $this->Form->text(null, ['id' => 'par_hole_add', 'title' => __('par_count'), 'class' => 'form-control input-sm required_hole', 'maxlength' => 1, 'onkeypress' => 'return inputNumber(event)']) ?>
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
                                        <table class="table table-bordered table-striped dataTable">
                                            <thead>
                                            <tr>
                                                <!--<th class="text-center">GNo</th>-->
                                                <th class="text-center"><?= __('front_position') ?></th>
                                                <th class="text-center"><?= __('center_position') ?></th>
                                                <th class="text-center"><?= __('back_position') ?></th>
                                                <!--<th class="text-center"><?= $this->Form->button('<i class="fa fa-fw fa-plus"></i>' . __('Add new'), ['class' => 'btn btn-primary btn-xs', 'type' => 'button', 'onclick' => 'newGreen();', 'escape' => false]) ?></th>-->

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
                    <div class="box-body">
                        <div id="googleMaps" style="height: 700px; width: 78%; float: left">
                            <!-- start maps -->
                            <input type="hidden" id="maxgreen" name="maxgreen" value="0" >
                            <input type="hidden" id="flgAdd" name="flgAdd" value="0" >
                            <input type="hidden" id="flgEdit" name="flgEdit" value="flgEdit" >
                            <input type="hidden" id="countGreen" name="countGreen" value="0" >
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
                                        var marker = new google.maps.Marker({
                                            position: new google.maps.LatLng(result[0], result[1]),
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
                                        /*}else{
                                         alert('Chưa ấn button 追加');
                                         }*/
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
                                        str += '<td class="text-center"><a href="javascript:void(0);"  class="btn btn-info btn-sm" onclick="editGreen('+"'" +countGreen+"'" +')"><i class="fa fa-fw fa-edit"></i>編集</a> <a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="deleteGreen('+"'" +countGreen+"'" +')"><i class="fa fa-fw fa-remove"></i>削除</a>';
                                        str += '</td></tr>';
                                        $("#appendGreen").append(str);
                                        $("#flgAdd").val('1');
                                        $("#countGreen").val(parseInt(countGreen)+1);
                                    }/*else{
                                        alert('Có cần hiển thị message khi chưa chọn location của green ko ?');
                                    }*/
                                }
                                function deleteGreen(x){
                                    if (confirm(CONFIRM_DELETE_GREEN)) {
                                        $("#trGreen_" + x).remove();
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
                        </div>

                    </div>
                </div>
            </div>
    <!-- end hole-->
        </div>
    </div>
    <div class="col-xs-1">
        <?= $this->Form->button(__('Add'), ['type' => 'button', 'class' => 'btn btn-primary', 'formnovalidate' => true, 'onclick' =>'submitFormGolf();']) ?>
    </div>
    <?= $this->Form->end() ?>
    <?= $this->Form->hidden('count_course', ['class' => 'form-control input-sm', 'maxlength' => 255, 'id' => 'count_course', 'value' => 0]) ?>
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
        'dist/golf_add'
    ])
?>