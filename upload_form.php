<?php
require_once __DIR__. '/bootstrap.php';

requireAuth();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Rowhess Upload</title>
    <link rel="stylesheet" href="public/css/semantic.min.css">
</head>
<body>
<div class="ui container">
    <div class="ui menu">
        <a class="item" href="index.php">
            Home
        </a>
        <a class="active item" href="upload_form.php">
            Upload
        </a>
        <?php include 'admin_nav.php'?>
    </div>
    <?php include 'alert.php'; ?>
    <h2>Upload</h2>
    <form enctype="multipart/form-data" class="ui form" method="post"
          action="<?php echo htmlspecialchars('add_upload.php'); ?>">
        <div class="field" id="campus-div">
            <label>Campus</label>
            <select name="campus_id" id="campus" class="ui selection dropdown">

            </select>
        </div>
        <div class="field" id="school-div">
            <label>School</label>
            <select name="school_id" id="school" class="ui selection dropdown">

            </select>
        </div>
        <div class="field" id="dept-div">
            <label>Department</label>
            <select name="dept_id" id="dept" class="ui selection dropdown">

            </select>
        </div>
        <div class="field">
            <label>Unit Name</label>
            <input name="unit_name" type="text"/>
        </div>
        <div class="field">
            <label>Unit Code</label>
            <input name="unit_code" type="text"/>
        </div>
        <div class="field" id="upload-div">
            <label>File</label>
            <input type="file" name="upload">
        </div>
        <div class="field">
            <input type="hidden" name="MAX_FILE_SIZE" value="300000">
        </div>
        <button class="ui button" type="submit">Submit</button>
    </form>
</div>
<script src="public/js/jquery.min.js"></script>
<script src="public/js/semantic.min.js"></script>
<script>
    $('.ui.dropdown').dropdown();

    $('.message .close')
        .on('click', function () {
            $(this)
                .closest('.message')
                .transition('fade');
        });

    const campusSelect = $('#campus');
    const schoolSelect = $('#school');
    const deptSelect = $('#dept');


    $(document).ready(() => {
        $('#campus-div').append("<button class=\"ui inverted massive button loading\" id=\"campus-loader\"></button>");
        $('#school-div').append("<button class=\"ui inverted massive button loading\" id=\"school-loader\"></button>");
        $('#dept-div').append("<button class=\"ui inverted massive button loading\" id=\"dept-loader\"></button>");

        campusSelect.empty();
        schoolSelect.empty();
        deptSelect.empty();

        campusSelect.prop('disabled', true);
        schoolSelect.prop('disabled', true);
        deptSelect.prop('disabled', true);

        $.get("upload_util.php", (data, status) => {
            if (status == 'success') {
                console.log("Campuses success");
                data = JSON.parse(data);
                for (const campus of data) {
                    let campOption = '<option value="' + campus['id'] + '">' + campus['name'] + '</option>';
                    campusSelect.append(campOption);
                }
                $('#campus-loader').remove();
                campusSelect.prop('disabled', false);

                if (data.length > 0) {
                    let currentCampus = data[0];
                    $.get("upload_util.php?campus_id=" + currentCampus['id'], (data, status) => {
                        if (status == 'success') {
                            console.log("Schools success");
                            data = JSON.parse(data);
                            for (const school of data) {
                                let schoolOption = '<option value="' + school['id'] + '">' + school['name'] + '</option>';
                                schoolSelect.append(schoolOption);
                            }
                            $('#school-loader').remove();
                            schoolSelect.prop('disabled', false);

                            if (data.length > 0) {
                                let currentSchool = data[0];
                                $.get("upload_util.php?school_id=" + currentSchool['id'], (data, status) => {
                                    if (status == 'success') {
                                        console.log("Depts success");
                                        data = JSON.parse(data);
                                        for (const dept of data) {
                                            let deptOption = '<option value="' + dept['id'] + '">' + dept['name'] + '</option>';
                                            deptSelect.append(deptOption);
                                        }
                                        $('#dept-loader').remove();
                                        deptSelect.prop('disabled', false);
                                    }
                                });
                            } else {
                                $('#dept-loader').remove();
                            }
                        }
                    });
                } else {
                    $('#school-loader').remove();
                    $('#dept-loader').remove();
                }
            }
        });
    });

    campusSelect.change(() => {
        $('#school-div').append("<button class=\"ui inverted massive button loading\" id=\"school-loader\"></button>");
        $('#dept-div').append("<button class=\"ui inverted massive button loading\" id=\"dept-loader\"></button>");

        schoolSelect.empty();
        deptSelect.empty();

        schoolSelect.prop('disabled', true);
        deptSelect.prop('disabled', true);

        let campus_id = campusSelect.val();
        $.get("upload_util.php?campus_id=" + campus_id, (data, status) => {
            if (status == 'success') {
                console.log("Schools success");
                data = JSON.parse(data);
                for (const school of data) {
                    let schoolOption = '<option value="' + school['id'] + '">' + school['name'] + '</option>';
                    schoolSelect.append(schoolOption);
                }
                $('#school-loader').remove();
                schoolSelect.prop('disabled', false);

                if (data.length > 0) {
                    let currentSchool = data[0];
                    $.get("upload_util.php?school_id=" + currentSchool['id'], (data, status) => {
                        if (status == 'success') {
                            console.log("Depts success");
                            data = JSON.parse(data);
                            for (const dept of data) {
                                let deptOption = '<option value="' + dept['id'] + '">' + dept['name'] + '</option>';
                                deptSelect.append(deptOption);
                            }
                            $('#dept-loader').remove();
                            deptSelect.prop('disabled', false);
                        }
                    });
                } else {
                    $('#dept-loader').remove();
                }
            }
        });
    });

    schoolSelect.change(() => {
        $('#dept-div').append("<button class=\"ui inverted massive button loading\" id=\"dept-loader\"></button>");

        deptSelect.empty();

        deptSelect.prop('disabled', true);

        let school_id = schoolSelect.val();
        $.get("upload_util.php?school_id=" + school_id, (data, status) => {
            if (status == 'success') {
                console.log("Depts success");
                data = JSON.parse(data);
                for (const dept of data) {
                    let deptOption = '<option value="' + dept['id'] + '">' + dept['name'] + '</option>';
                    deptSelect.append(deptOption);
                }
                $('#dept-loader').remove();
                deptSelect.prop('disabled', false);
            }
        });
    });

</script>
</body>
</html>