<style>
    .widget-handler {
        position: relative;
        /* margin-bottom: 20px; */
    }

    .widget-handler:not(:last-child) {
        margin-bottom: 20px;
    }

    .widget-handler .expand {
        position: absolute;
        right: 40px;
        width: 25px;
        height: 25px;
        line-height: 29px;
        background-color: #ffffff;
        border-radius: 50%;
        font-size: 12px;
        text-align: center;
        font-weight: 700;
        top: 10px;
        cursor: pointer;
    }

    .widget-handler .remove-widget {
        position: absolute;
        right: 10px;
        width: 25px;
        height: 25px;
        line-height: 29px;
        background-color: #dc3545;
        border-radius: 50%;
        font-size: 12px;
        text-align: center;
        font-weight: 700;
        top: 10px;
        cursor: pointer;
        color: #fff;
    }

    .widget-handler .top-part {
        font-size: 16px;
        line-height: 26px;
        padding: 10px;
    }

    .widget-handler .content-part.show {
        visibility: visible;
        opacity: 1;
        height: auto;
        padding: 20px 30px 20px 26px;
    }

    .widget-handler .content-part {
        visibility: hidden;
        opacity: 0;
        height: 0;
    }

    .widget-handler .content-part .nav-item {
        font-size: 14px;
        font-weight: 400;
    }

    .info-paragraph {
        font-size: 14px;
        line-height: 24px;
    }

    .info-paragraph .info-url {
        margin-top: 10px;
        display: block;
        width: calc(100% - 50%);
        border: none;
        padding: 8px 20px;
        background-color: #f2f2f2;
        color: #363636;
    }

    .widget-area-header {
        position: relative;
        padding: 20px 25px;
        background-color: #fff;
    }

    .widget-area-header .widget-area-expand {
        position: absolute;
        right: 20px;
        top: 20px;
        display: inline-block;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background-color: #eaeaea;
        text-align: center;
        line-height: 33px;
        box-shadow: 0 0 5px 0 rgba(0, 0, 0, .1);
        cursor: pointer;
    }

    .widget-area-header .header-title {
        margin-bottom: 0;
    }

    .widget-area-body.hide {
        height: 0;
        visibility: hidden;
        padding: 0;
    }

    .attribute-field-wrapper {
        position: relative;
        padding-right: 50px;
        margin-bottom: 20px;
    }

    .attribute-field-wrapper input {
        margin-bottom: 10px;
    }

    .attribute-field-wrapper .icon-wrapper {
        position: absolute;
        right: 0;
        top: 0;
        background-color: #f2f2f2;
        display: block;
        height: 100%;
        width: 45px;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        text-align: center;
    }

    .attribute-field-wrapper .icon-wrapper span {
        display: block;
        width: 30px;
        height: 30px;
        line-height: 30px;
        background-color: #000;
        margin-bottom: 5px;
        color: #fff;
        font-weight: 700;
        cursor: pointer;
    }

    .attribute-field-wrapper .icon-wrapper span.remove_attributes {
        background-color: #da3e4d;
    }

    .attribute-field-wrapper .icon-wrapper span.add_attributes {
        background-color: green;
    }

    .ratings {
        color: #fec42d;
    }

    .sortable_widget_location {
        min-height: 30px;
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .sortable_widget_location:not(:last-child) {
        margin-bottom: 20px;
    }

    .sidebar-list-wrap .card+.card {
        margin-top: 20px;
    }

    .widget.job_information .widget-title:before {
        display: none;
    }

    .bage-notification {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        right: 30px;
        font-size: 12px;
        background-color: #F86048;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 30px;
        width: 30px;
        color: #fff !important;
        border-radius: 50%;
    }


    /* new dashbox style */

    .dash-box {
        display: flex;
        padding: 50px;
        border-radius: 1px;
        position: relative;
        z-index: 0;
        overflow: hidden;
        background-color: #343942
    }

    .dash-box .dash-content {
        margin-left: 20px;
    }

    .dash-box:after {
        position: absolute;
        right: -40px;
        top: -40px;
        width: 140px;
        height: 140px;
        background-color: #656ccc;
        content: '';
        z-index: -1;
        border-radius: 50%;
        opacity: .5;
    }

    .dash-box:before {
        position: absolute;
        right: -47px;
        top: -50px;
        width: 170px;
        height: 170px;
        background-color: #6569cc;
        content: '';
        z-index: -1;
        border-radius: 50%;
        opacity: .5;
    }

    .dash-box .dash-content h5 {
        font-size: 40px;
        line-height: 50px;
    }

    .dash-box .dash-content small {
        font-size: 16px;
        text-align: left;
        line-height: 26px;
    }

    .dash-box .dash-icon {
        font-size: 60px;
        line-height: 90px;
    }

    .no-sort {
        max-width: 50px;
    }

    button.btn.btn-info.bt-xl.iconpicker-component {
        font-size: 20px;
    }

    button.icp.icp-dd.btn.bt-xl.btn-info.dropdown-toggle.iconpicker-element {
        font-size: 18px;
    }

    td.icon-view {
        font-size: 30px;
    }

    span.alert.alert-success {
        margin-top: 10px;
        display: inline-block;
    }

    span.alert.alert-danger {
        margin-top: 10px;
        display: inline-block;
    }

    span.alert.alert-warning {
        margin-top: 10px;
        display: inline-block;
    }

    span.alert.alert-info {
        margin-top: 10px;
        display: inline-block;
    }

    span.alert.alert-primary {
        margin-top: 10px;
        display: inline-block;
    }

    .iconpicker .iconpicker-item {
        width: 30px;
        height: 30px;
        font-size: 30px;
    }

    .lds-spinner div:after {
        background: #7190c1;
    }

    .iconbox-repeater-wrapper.dynamic-repeater {
        background-color: #ddd;
        padding: 20px;
        margin-bottom: 20px;
    }

    .iconbox-repeater-wrapper.dynamic-repeater label {
        font-size: 16px;
        font-weight: 600;
        color: #333;
    }

    .iconbox-repeater-wrapper.dynamic-repeater .all-field-wrap {
        padding: 10px;
        padding-right: 100px;
        margin-bottom: 10px;
    }

    .iconbox-repeater-wrapper.dynamic-repeater .all-field-wrap .action-wrap {
        width: 100px;
        flex-direction: row;
    }

    .iconbox-repeater-wrapper.dynamic-repeater .all-field-wrap .form-group {
        margin-bottom: 0px;
    }

    .iconbox-repeater-wrapper.dynamic-repeater .all-field-wrap .action-wrap .add {
        margin-right: 5px;
    }

    .time_slot {
        display: flex;
        flex-wrap: wrap;
    }

    .time_slot li.selected {
        background-color: #007bff;
        color: #fff;
    }

    .max-width-100 {
        max-width: 100px;
    }

    .time_slot li {
        display: inline-block;
        background-color: #f2f2f2;
        padding: 5px 10px;
        border-radius: 5px;
        margin: 5px;
        cursor: pointer;
    }

    .max-width-200 {
        max-width: 200px;
    }

    .max-width-100 {
        max-width: 100px;
    }

    .lds-spinner div:after {
        background: #7190c1;
    }

    .iconbox-repeater-wrapper.dynamic-repeater {
        background-color: #ddd;
        padding: 20px;
        margin-bottom: 20px;
    }

    .iconbox-repeater-wrapper.dynamic-repeater label {
        font-size: 16px;
        font-weight: 600;
        color: #333;
    }

    .iconbox-repeater-wrapper.dynamic-repeater .all-field-wrap {
        padding: 10px;
        padding-right: 100px;
        margin-bottom: 10px;
    }

    .iconbox-repeater-wrapper.dynamic-repeater .all-field-wrap .action-wrap {
        width: 100px;
        flex-direction: row;
    }

    .iconbox-repeater-wrapper.dynamic-repeater .all-field-wrap .form-group {
        margin-bottom: 0px;
    }

    .iconbox-repeater-wrapper.dynamic-repeater .all-field-wrap .action-wrap .add {
        margin-right: 5px;
    }

    .booking-details-info ul {
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .booking-details-info ul li+li {
        margin-top: 0;
        font-size: 16px;
        border-top: 1px solid #e2e2e2;
    }

    .booking-details-info ul li {
        padding: 10px;
    }

    .booking-details-info ul {
        border: 1px solid #e2e2e2;
    }

    .all-field-wrap {
        position: relative;
        background-color: #f2f2f2;
        padding: 30px;
        padding-right: 70px;
        margin-bottom: 30px;
    }

    .all-field-wrap .action-wrap {
        position: absolute;
        right: 0;
        top: 0;
        background-color: #f2f2f2;
        height: 100%;
        width: 60px;
        text-align: center;
        display: flex;
        justify-content: center;
        flex-direction: column;
        align-items: center;
    }

    .all-field-wrap .action-wrap .add,
    .all-field-wrap .action-wrap .remove {
        display: inline-block;
        height: 30px;
        width: 30px;
        background-color: #339e4b;
        line-height: 30px;
        text-align: center;
        border-radius: 2px;
        font-weight: 700;
        color: #fff;
        margin-bottom: 5px;
        cursor: pointer;
    }

    .all-field-wrap .action-wrap .remove {
        background-color: #bb4b4c;
    }

    .select-box-wrap select {
        border: 1px solid #e2e2e2;
    }

    .all-field-wrap ul li a {
        padding: 5px 15px;
        font-size: 14px;
    }

    .all-field-wrap input {
        font-size: 14px;
        height: 35px;
    }

    .all-field-wrap .tab-content {
        margin-top: 15px;
    }

    .all-field-wrap textarea {
        max-height: 60px;
    }

    .footer-wrap {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-left: 20px;
    }

    p.version {
        padding-right: 20px;
    }

    body>.alert {
        margin-left: 280px;
        transition: all 300ms;
    }

    body.sbar_collapsed>.alert {
        margin-left: 0px;
        transition: all 300ms;
    }

    .comma-items+.comma-items:before {
        position: static;
        content: ',';
        padding: 2px 2px;
    }


    /*//for Form Builder COlor and border*/

    .ui-state-default,
    .ui-widget-content .ui-state-default,
    .ui-widget-header .ui-state-default,
    .ui-button,
    html .ui-button.ui-state-disabled:hover,
    html .ui-button.ui-state-disabled:active {
        border: 1px solid #c5c5c5;
        background: #f6f6f6;
        font-weight: normal;
        color: #454545;
    }

    @media only screen and (max-width: 414px) {
        .user-profile.pull-right {
            float: none;
        }

        .notification-area.pull-right {
            margin-top: 0;
        }

        .sbar_collapsed .nav-btn.pull-left {
            position: absolute;
            top: 0;
            left: 0;
        }

        .main-menu {
            height: calc(100% - 250px) !important;
        }
    }

    body>.alert.alert-warning {
        margin-left: 280px !important;
        margin-bottom: 0;
    }

    body.sidebar_collapsed>.alert.alert-warning {
        margin-left: 0px !important;
    }

    @media only screen and (max-width: 1366px) {
        body>.alert.alert-warning {
            margin-left: 0px !important;
        }
    }

    input::-webkit-calendar-picker-indicator {
        display: none;
    }

    input[type="date"]::-webkit-input-placeholder {
        visibility: hidden !important;
    }


    /* page builder style */

    .page-builder-area-wrapper .content-part select.form-control:not([size]):not([multiple]) {
        height: 0;
        margin-bottom: 0;
    }

    .page-builder-area-wrapper .content-part .nice-select.wide.form-control {
        margin-bottom: 0;
        height: 0;
    }

    .page-builder-area-wrapper .content-part.show .nice-select.wide.form-control {
        margin-bottom: 20px;
        height: auto;
    }

    .page-builder-area-wrapper .content-part.show select.form-control:not([size]):not([multiple]) {
        height: 40px;
        margin-bottom: 20px;
    }

    .page-builder-area-wrapper .content-part.show .nice-select.wide.form-control {
        display: block;
    }

    .page-builder-area-wrapper .content-part .nice-select.wide.form-control {
        display: none;
    }

    .page-builder-area-wrapper .sortable {
        border: 1px dashed #e2e2e2;
        padding: 30px;
    }

    .all-addons-wrapper ul {
        margin: 0;
        padding: 0;
        list-style: none;
        display: flex;
    }

    .all-addons-wrapper ul li {
        background-color: #f3f3f3;
    }

    .all-addons-wrapper ul li .top-part {
        font-size: 14px;
        line-height: 20px;
    }

    .sidebar-list-wrap .card {
        margin-bottom: 20px;
    }

    .all-widgets.available-form-field {
        border: 1px dashed #e2e2e2;
        padding: 10px;
        list-style: none;
        margin: 0;
    }

    .available-form-field li span.text-success {
        display: block;
        font-size: 12px;
        background-color: #d5ecd5;
        width: max-content;
        padding: 2px 10px;
        text-transform: capitalize;
        margin-top: 20px;
    }

    .all-addons-wrapper ul li .top-part .preview-image {
        position: absolute;
        right: 10px;
        top: 10px;
        width: 20px;
        height: 20px;
        background-color: #8880f9;
        text-align: center;
        color: #fff;
        font-size: 12px;
        cursor: pointer;
    }

    .sortable li div .form-group label {
        font-weight: 600;
        color: #333;
        font-size: 14px;
    }

    .sortable li .all-field-wrap .nav-item {
        padding: 5px 10px;
    }

    .available-form-field .range-wrap {
        display: flex;
    }

    .available-form-field .range-wrap .range-val {
        display: inline-block;
        width: 60px;
        height: 30px;
        background-color: #eee;
        margin-right: 0;
        margin-left: 10px;
        border-radius: 2px;
        color: #333;
        text-align: center;
        line-height: 30px;
        font-size: 14px;
        font-weight: 700;
    }

    .color_picker {
        display: inline-block;
        width: 35px;
        height: 35px;
        background-color: transparent;
        border: 5px solid #9b9b9b;
        box-shadow: 0 0 5px 0 rgba(0, 0, 0, 0.05);
        cursor: pointer;
    }

    .ui-sortable .ui-sortable-placeholder {
        min-height: 40px;
        border: 1px dashed #e2e2e2;
        margin-bottom: 10px;
        visibility: visible !important;
        background-color: transparent;
    }

    .sortable li div .color_picker {
        background-color: white;
        display: block;
    }

    .page-builder-area-wrapper.extra-title {
        margin-bottom: 40px;
    }

    .page-builder-area-wrapper.extra-title .main-title {
        font-size: 18px;
        line-height: 28px;
        font-weight: 600;
        margin-bottom: 11px;
        background-color: #f3f3f3;
        padding: 10px;
    }

    .available-form-field li span.page-builder-info-text {
        display: block;
        font-size: 12px;
        line-height: 16px;
        font-weight: 400;
    }


    /* page builder */

    .all-addons-wrapper ul.ui-sortable li.widget-handler {
        position: relative;
    }

    .all-addons-wrapper ul.ui-sortable li.widget-handler .imageupshow {
        position: absolute;
        left: 0;
        max-width: 250px;
        height: auto;
        content: '';
        z-index: 9;
        bottom: 40px;
        border: 5px solid #fbfbfb;
        box-shadow: 0 0 15px 0 rgba(0, 0, 0, 0.02);
        visibility: hidden;
        opacity: 0;
        transition: all 500ms;
    }

    .all-addons-wrapper ul.ui-sortable li.widget-handler:hover .imageupshow {
        visibility: visible;
        opacity: 1;
    }

    button.medium {
        display: inline-block;
        background-color: #70b9ae;
        padding: 3px 10px;
        border-radius: 4px;
        color: #fff;
        text-transform: capitalize;
        border: none;
        font-weight: 600;
    }

    button.high,
    button.status-close {
        display: inline-block;
        background-color: #c66060;
        padding: 3px 10px;
        border-radius: 4px;
        color: #fff;
        text-transform: capitalize;
        border: none;
        font-weight: 600;
    }

    button.urgent {
        display: inline-block;
        background-color: #bfb55a;
        padding: 3px 10px;
        border-radius: 4px;
        color: #fff;
        text-transform: capitalize;
        border: none;
        font-weight: 600;
    }

    button.low,
    button.status-open {
        display: inline-block;
        background-color: #6bb17b;
        padding: 3px 10px;
        border-radius: 4px;
        color: #fff;
        text-transform: capitalize;
        border: none;
        font-weight: 600;
    }

    .gig-message-start-wrap {
        margin-top: 60px;
        margin-bottom: 60px;
        background-color: #fbf9f9;
        padding: 40px;
    }

    .reply-message-wrap {
        padding: 40px;
        background-color: #fbf9f9;
    }

    .sidebar-menu .logo img {
        max-height: 70px;
    }


    /* language translate */

    .language-word-translate-box .middle-part {
        height: 300px;
        background-color: #e2e2e2;
        padding: 20px;
        overflow-y: auto;
    }

    .language-word-translate-box .top-part .single-string-wrap,
    .language-word-translate-box .middle-part .single-string-wrap {
        display: flex;
        justify-content: space-between;
        font-size: 14px;
        padding: 5px 20px;
        cursor: pointer;
    }

    .language-word-translate-box .top-part .single-string-wrap div,
    .language-word-translate-box .middle-part .single-string-wrap div {
        width: 50%;
        display: inline-block;
    }

    .language-word-translate-box .middle-part .single-string-wrap:nth-child(odd) {
        background-color: #d6d6d6;
    }

    .language-word-translate-box .top-part {
        background-color: #333;
        color: #fff;
    }

    .language-word-translate-box .top-part .single-string-wrap {
        font-size: 16px;
        font-weight: 700;
    }

    .language-word-translate-box .footer-part {
        margin-top: 30px;
    }

    .language-word-translate-box .footer-part h6 {
        margin: 10px 0 20px;
        font-size: 14px;
    }

    .language-word-translate-box .search-box-wrapper input {
        display: block;
        width: 100%;
        padding: 10px;
        border: 1px solid #333;
        margin-bottom: 20px;
    }

    @media screen and (min-width:992px) and (max-width: 1199px) {
        .page-container .main-content .card-body {
            padding: 10px;
        }
    }

    @media screen and (min-width:300px) and (max-width: 991px) {
        .page-container .main-content .card-body {
            padding: 10px;
        }
    }

    @media screen and (min-width:300px) and (max-width: 375px) {
        .page-container .main-content .card-body {
            padding: 5px;
        }
    }

    div#DataTables_Table_0_wrapper {
        overflow-x: auto;
    }

    table.dataTable thead th,
    table.dataTable thead td {
        padding: 10px 18px;
        border-bottom: 1px solid #111;
        min-width: 50px;
    }

    div.dataTables_wrapper div.dataTables_paginate ul.pagination {
        flex-wrap: wrap;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        margin-top: 10px;
    }

    .table-wrap.table-responsive .table thead th {
        min-width: 50px;
    }

    .dataTables_wrapper {
        overflow-x: auto;
    }

    .table-wrap {
        overflow-x: auto;
    }

    .page-container .main-content .card-body {
        overflow-x: auto;
    }

    @media screen and (max-width: 400px) {
        .all-widgets.available-form-field {
            display: grid;
        }

        .all-widgets.available-form-field li {
            width: calc(100% / 1 - 0px);
        }
    }

    .max-width-100 {
        max-width: 100px;
    }

    .widget-handler .remove-widget {
        position: absolute;
        right: 10px;
        width: 25px;
        height: 25px;
        line-height: 29px;
        background-color: #dc3545;
        border-radius: 50%;
        font-size: 12px;
        text-align: center;
        font-weight: 700;
        top: 10px;
        cursor: pointer;
        color: #fff;
    }
</style>
