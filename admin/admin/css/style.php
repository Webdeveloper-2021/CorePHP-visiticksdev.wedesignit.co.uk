<?php
header('Content-Type: text/css');
?>

html,body {width:100%;overflow-x:hidden;}

* { margin: 0; padding: 0; }

ul {list-style:none;}
.urow {float:left;width:100%;}
.uimageh {width:100%;height:auto;}
img {border:0;}

a {text-decoration:none;-webkit-transition: color 0.5s ease;-o-transition: color 0.5s ease;transition: color 0.5s ease;}
a:link, a:visited {color:#02aadd;text-decoration:underline;}
a:hover, a:active {color:#02aadd;text-decoration:none;}

p { margin:0 0 25px 0;line-height: 180%;}
h1 {font-size: 22px;font-weight: 600;margin:0 0 25px 0;padding:6px 0 6px 10px;}
h2 {font-size: 18px;font-weight: 600;padding:15px 0 15px 0;color:#d9581d;float:left;} 

body {background-color:#1ca8df;font-family:'Heebo', sans-serif;font-size:14px;color:#404040;}
textarea {font-family:'Heebo', sans-serif;font-size:14px;}

/* ----- FULL WIDTH FLOATS AND BORDER BOXES */

.outer,.outer-top,.outer-bottom,.outer-middle,.outer-top-inner-inner,.outer-middle-login-inner,.outer-middle-inner-inner,.outer-bottom-inner-inner,.outer-middle-left-unit,.outer-middle-left-unit-header,.outer-middle-left-unit-body,.outer-middle-left-unit-body ul,.outer-middle-left-unit-body ul li,.formarea,.formarea-group,.formarea-row,.formarea-row-last,.formarea-row-right-input,.formarea-row-right-input ul.fontoptions li,.formarea-row-right-text,.formarea-row-right-notes,.formarea-row-right-error,.formarea-header,.formarea-row-right-scroll-input,.tabs,.table,.table-footer,.table-noresults,.table-header,.paging,.paging ul,.error,.success,.message-inner,.success-inner {width:100%;float:left;}

.formarea,.formarea-header,.formarea-row,.formarea-row-last,.textbox-small,.textbox-normal,.formarea-row-right-scroll,.table,.table-footer,.table-noresults,.message-inner,.success-inner {box-sizing:border-box;}

/* ----- OTHER */

.outer-top {background-color:#111425;}
.outer-middle {background-color:#FFFFFF;}
.outer-top-inner,.outer-middle-inner,.outer-bottom-inner {padding:0 10px 0 10px;margin:auto;}
.outer-top-inner,.outer-bottom-inner {color:#FFFFFF;}
.outer-top-inner-inner {padding:25px 0 25px 0;}
.outer-bottom-inner-inner {padding:15px 0 25px 0;}
.outer-top-inner-inner-left,.outer-top-inner-inner-right,.outer-middle-left,.outer-middle-right {float:left;}
.outer-top-inner-inner-right ul {}
.outer-top-inner-inner-right ul li {display:inline;float:right;margin:0 0 0 18px;}
.outer-top-inner-inner-right a {text-decoration:none;}
.outer-top-inner-inner-right a:link, .outer-top-inner-inner-right a:visited {color:#FFFFFF;}
.outer-top-inner-inner-right a:hover, .outer-top-inner-inner-right a:active {color:#DEDEDE;}

.footer-row-powered {text-align:right;font-weight:700;}
.footer-row-powered a:link, .footer-row-powered a:visited {color:#FFFFFF;}
.footer-row-powered a:hover, .footer-row-powered a:active {color:#DEDEDE;}

/* ----- RESPONSIVE */

.outer-middle-left,.outer-middle-right {padding:35px 0 35px 0;}
.outer-top-inner-inner-left {width:219px;}
.outer-top-inner-inner-right {padding:8px 0 0 0;font-weight:700;}
.outer-top-inner-inner-right i {color:#1ca8df;margin-right:7px;}



@media screen and (min-width:1000px)
{
.outer-top-inner,.outer-middle-inner,.outer-bottom-inner {width:980px;}

}

@media screen and (min-width:768px) and (max-width:999px)
{
.utabletrow {float:left;width:100%;margin-bottom:15px;}
}

@media screen and (min-width:768px)
{
.outer-middle-left {width:196px;border-right:1px solid #e6e6e6;}
.outer-middle-right {border-left:1px solid #e6e6e6;margin-left:-1px;padding-left:22px;}
.outer-middle-right,.outer-top-inner-inner-right {width:calc(100% - 219px);}
.outer-middle-right {}
.formarea-row-left {width:30%;}
.formarea-row-right,.formarea-row-right-scroll {width:70%;}
.outer-middle-login {width:50%;}
}

@media screen and (max-width:767px)
{
.unotmobile {display:none !important;visibility:hidden;}
.formarea-row-left {width:100%;margin-bottom:10px;}
.formarea-row-right,.formarea-row-right-scroll {width:100%;}
.outer-middle-left {display:none;visibility:hidden;}
.outer-middle-right {width:100%;}
.umobilerow {float:left;width:100%;margin-bottom:15px;}
.outer-middle-login {width:80%;}
}


.outer-middle-left-unit {margin:0 0 25px 0;color:#404040;}

.outer-middle-left-unit-header {margin:0 0 5px 0;text-transform:uppercase;font-size:12px;font-weight:600;color:#eb7134;}
.outer-middle-left-unit-body {font-size:15px;}
.outer-middle-left-unit-body ul li a {text-decoration:none;display:block;padding:6px 0 6px 7px;-webkit-transition: background-color 0.5s ease, color 0.5s ease;-o-transition: background-color 0.5s ease, color 0.5s ease;transition: background-color 0.5s ease, color 0.5s ease;}
.outer-middle-left-unit-body ul li a i {width:20px;padding-right:7px;}
.outer-middle-left-unit-body ul li a.active {font-weight:600;color:#1ca8df;background-color:#e6effc;}
.outer-middle-left-unit-body ul li a:link,.outer-middle-left-unit-body ul li a:visited {color:#404040;}
.outer-middle-left-unit-body ul li a:hover, .outer-middle-left-unit-body ul li a:active {color:#1ca8df;background-color:#e6effc;}


.button,.button-cancel,.button-search,a.button-search,.button-add,a.button-add,a.button {border:0;margin:0 10px 15px 0;padding:9px 19px 9px 19px;cursor: pointer;border-radius: 5px;-moz-border-radius: 5px;-webkit-border-radius: 5px;font-weight:600;}
.button,a.button {background-color:#ec6121;color:#FFFFFF;}
.button:hover,a.button:hover {background-color:#c9521c;color:#d8d2cf;}

a.button-search,a.button-add,a.button-cancel,a.button {display:block;text-decoration:none;}
.button-search,a.button-search,.button-add,a.button-add {background-color:#33aaff;color:#FFFFFF;}
.button-search:hover,a.button-search:hover,.button-add:hover,a.button-add:hover {background-color:#0077ce;}

.button-cancel,a.button-cancel {background-color:#DEDEDE;color:#444444;}
.button-cancel:hover,a.button-cancel:hover {background-color:#b6b6b6;}




.formarea {border:1px solid #DEDEDE;background-color:#FAFAFA;padding:25px 0 10px 0;border-radius: 5px;-moz-border-radius: 5px;-webkit-border-radius: 5px;margin-bottom:25px;}
.formarea-header {padding:10px 25px 10px 25px;font-weight:700;background-color:#dedede;margin:15px 0 15px 0;}
.formarea-header.topper {margin-top:-25px !important;border-top-left-radius: 5px;-moz-border-radius-topleft: 5px;-webkit-border-radius-topleft: 5px;border-top-right-radius: 5px;-moz-border-radius-topright: 5px;-webkit-border-radius-topright: 5px;}

.formarea-row {padding:10px 25px 10px 25px;}
.formarea-row-last {padding:25px 25px 10px 25px;}
.formarea-row-left {float:left;}
.formarea-row-right {float:left;}

.formarea-row-right-error {margin:5px 0 0 0;color:#e9477f;font-weight:600;}

.textbox-error {border: 1px solid #e9477f;}
.textbox-noerror,.textbox-search,.textbox-date {border: 1px solid #DEDEDE;}


.textbox-tiny,.textbox-small,.textbox-normal,.textbox-search,.textbox-date {padding:5px;border-radius: 4px;-moz-border-radius: 4px;-webkit-border-radius: 4px;}
.textbox-tiny {width:calc(25% - 10px);margin-right:20px;}
.textbox-small {width:50%;}
.textbox-normal {width:100%;}

.textbox-search {width:138px;}
.textbox-date {width:65px;}


.formarea-row-right-scroll {overflow-y: auto;overflow-x: none;height:120px;border:1px solid #dedede;padding:12px;background-color:#FFFFFF;}

input[type='checkbox'] {margin-right:12px;}

.event-unit {width:105px;float:left;margin:5px;padding:5px 15px 5px 15px;color:#FFFFFF;border-radius:4px;-webkit-border-radius:4px;-moz-border-radius:4px;}
.event-unit.time {background-color:#444444;}
.event-unit.add,a.event-unit.add {text-decoration:none;background-color:#33aaff;cursor: pointer;cursor: hand;color:#FFFFFF;}
.event-unit.add:hover,a.event-unit.add:hover {text-decoration:none;background-color:#0077ce;}


.tabs {margin-bottom:-1px;z-index:20;}
.tabs ul {float:left;list-style:none;z-index:20;}
.tabs ul li {margin:0 0 0 6px;display:inline;float:left;border-top-left-radius: 5px;-moz-border-radius-topleft: 5px;-webkit-border-radius-topleft: 5px;border-top-right-radius: 5px;-moz-border-radius-topright: 5px;-webkit-border-radius-topright: 5px;border-top:1px solid #e6e6e6;border-left:1px solid #e6e6e6;border-right:1px solid #e6e6e6;}
.tabs ul li a {text-decoration:none;display:block;padding:6px 8px 6px 8px;background-color:#e6e6e6;color:#404040;border-bottom:1px solid #e6e6e6;}
.tabs ul li a:hover,.tabs ul li a.active {background-color:#FAFAFA;border-bottom:1px solid #FAFAFA;}
.tabs ul li a.active {font-weight:600;margin-bottom:-1px;}

.tabs-orphan {margin-bottom:25px;border-bottom:1px solid #DEDEDE;}

.table {z-index:10;}
table {border-collapse:collapse;}
table th {text-align:left;font-weight:600;border:1px solid #DEDEDE;background-color:#FAFAFA;padding:8px 5px 8px 5px;}
table td {text-align:left;border:1px solid #DEDEDE;padding:8px 5px 8px 5px;}
table td.actions {font-size:18px;}
table td.actions ul {}
table td.actions ul li {display:inline-block;float:left;margin:0 3px 0 3px;}
table td.actions ul li a {text-decoration:none;color:#404040;}
table td.actions ul li a:hover {color:#1ca8df;}
table td.listimage img,img.urounded {border-radius:5px;-webkit-border-radius:5px;-moz-border-radius:5px;}
table td.listimage img,.editimage img {width:120px;height:auto;}

.table-footer {padding:10px 0 10px 0;}
.table-noresults {border:1px solid #DEDEDE;padding:30px 10px 30px 10px;}

.table-header {margin:0 0 20px 0;}
.table-header-left,.table-header-right {float:left;width:50%;}
.table-header-right {text-align:right;}

.buttonactions ul li {display:inline;float:left;}

.paging {}
.paging ul {margin-left:-5px;}
.paging ul li {float:left;display:inline;}
.paging ul li a {display:block;border:1px solid #DEDEDE;padding:8px 15px 8px 15px;margin:0 5px 0 5px;text-decoration:none;}
.paging ul li a:link,.paging ul li a:visited {color:#404040;}
.paging ul li a:hover, .paging ul li a:active {color:#1ca8df;background-color:#e6effc;}

.search {margin:0 0 20px 0;}
.search-unit {float:left;margin-right:10px;}
.search-unit ul {}
.search-unit ul li {display:inline-block;margin:-4px 2px 0 2px;}

.formarea-row-right-input ul.options {list-style:none;}
.formarea-row-right-input ul.options li {float:left;display:inline;margin-right:10px;}
.formarea-row-right-input ul.options li label {margin-left:5px;}

.formarea-row-right-input ul.fontoptions {list-style:none;font-size:28px;font-weight:700;}
.formarea-row-right-input ul.fontoptions li {margin:10px 0 10px 0;}
.formarea-row-right-input ul.fontoptions li label {margin-left:5px;}

.required {color:#e9477f;}

.message-inner,.success-inner {padding:5px 10px 5px 10px;}
.error,.success {margin:0 0 25px 0;}
.error {border:1px solid #E18B7C;background-color:#FCCAC1;}
.success {border:1px solid #C1D779;background-color:#EFFEB9;}


.outer-middle-login {margin:auto;}
.outer-middle-login-inner {padding:75px 0 75px 0;}

/* ----- TOOLTIP */


.tooltip {display:inline-block;position:relative;text-align:left;margin-left:7px;}
.tooltip .tooltip-left {top:50%;left:100%;transform:translate(0, -50%);margin-left:5px;color:#FFFFFF;background-color:#1ca8df;font-weight:normal;border-radius:8px;position:absolute;z-index:99999999;box-sizing:border-box;display:none;}
.tooltip:hover .tooltip-left {display:block;}
.tooltip .tooltip-left i {top:50%;right:100%;margin-top:-12px;width:12px;height:24px;position:absolute;overflow:hidden;}
.tooltip .tooltip-left i::after {top:50%;right:0;transform:translate(50%,-50%) rotate(-45deg);content:'';position:absolute;width:12px;height:12px;background-color:#1ca8df;}

@media screen and (min-width:500px) {
.tooltip .tooltip-left {min-width:300px;max-width:600px;padding:20px;}
}

@media screen and (max-width:499px) {
.tooltip .tooltip-left {min-width:150px;max-width:250px;padding:10px;}
}





































