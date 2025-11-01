<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'MLM')</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.2 -->
    <link href="{{ asset('admin/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- FontAwesome 4.3.0 -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet"
        type="text/css" />
    <!-- Ionicons 2.0.0 -->
    <link href="http://code.ionicframework.com/ionicons/2.0.0/css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->

    <link href="{{ asset('admin/dist/css/AdminLTE.min.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('admin/dist/css/skins/_all-skins.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- DATA TABLES -->
    {{-- <link href="{{ asset('admin/plugins/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css" /> --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" />


    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
<style>
input[type="text"] {
  text-transform: uppercase;
}
</style>

    <style>
        .from-top-header {
            text-align: center;
            font-size: 20px;
            font-weight: 700;
            background: #3c8dbc;
            color: #fff;
            display: block;
            border-radius: 20px;
        }
    </style>


    <style>
        .flash-message {
            padding: 15px 20px;
            margin: 20px 0;
            border-radius: 6px;
            font-size: 16px;
            top: -65px;
            width: 100%;
            position: absolute;
            animation: fadeIn 0.5s ease-in-out;
        }

        .flash-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .flash-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .flash-message .close-btn {
            position: absolute;
            right: 10px;
            top: 10px;
            font-size: 20px;
            background: none;
            border: none;
            cursor: pointer;
            color: inherit;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>





    <style>
        html {
            overflow-y: scroll;
        }

        .color-change1 {
            animation: colorCycle 5s infinite linear;
        }

        @keyframes colorCycle {
            0% {
                color: #e74c3c;
            }

            25% {
                color: #f39c12;
            }

            50% {
                color: #2ecc71;
            }

            75% {
                color: #3498db;
            }

            100% {
                color: #9b59b6;
            }
        }
    </style>

    <style type="text/css">
        #mydiv {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 30em;
            height: 18em;
            margin-top: -9em;
            /*set to a negative number 1/2 of your height*/
            margin-left: -15em;
            /*set to a negative number 1/2 of your width*/
            border: 1px solid #ccc;
            background-color: #f3f3f3;
        }

        <style>.shadow {
            border: 5px solid #00a65a;
            /* Blue border */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            /* Shadow effect */
            padding: 20px;
            margin: 10px;
            transition: transform 0.3s;
        }
    </style>


    <style>
        .small-box {
            border-radius: 25px;
        }

        .widget-user-2 .widget-user-header {
            border-top-right-radius: 25px;
            border-top-left-radius: 25px;
        }

        .box-widget {
            border-radius: 30px 30px 0 0;
        }

        .small-box .icon .ion {
            margin-top: 12px;
            color: #fff;
            opacity: 0.5;
        }

        .sidebar-menu,
        .main-sidebar .user-panel,
        .sidebar-menu>li.header {

            font-weight: 700;
        }

        .small-box>.small-box-footer {
            background: unset;
        }

        .box-header .color-change {
            font-size: 28px;
            font-weight: 700;
        }



        .small-box {
            color: white;
            padding: 5px;
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
            animation: fadeInUp 0.6s ease both;
        }



        .card:nth-child(5) {
            background: linear-gradient(135deg, #7c3aed, #8b5cf6);
        }

        .card:nth-child(6) {
            background: linear-gradient(135deg, #0d9488, #14b8a6);
        }

        .card:nth-child(7) {
            background: linear-gradient(135deg, #92400e, #f97316);
        }

        .card:nth-child(8) {
            background: linear-gradient(135deg, #9d174d, #ec4899);
        }

        .bg-aqua {
            background: linear-gradient(135deg, #4f46e5, #3b82f6) !important;
        }

        .bg-green {
            background: linear-gradient(135deg, #059669, #10b981) !important;
        }

        .bg-yellow {
            background: background: linear-gradient(135deg, #d97706, #f59e0b) !important;
        }

        .bg-red {
            background: background: linear-gradient(135deg, #b91c1c, #ef4444) !important;
        }

        .label {
            font-size: 14px;
            text-transform: uppercase;
            opacity: 0.85;
        }

        .inner .color-change {
            font-size: 32px;
            font-weight: bold;
            margin: 8px 0;
            animation: pulse 8s infinite;
        }



        .card-bottom {
            font-size: 12px;
            opacity: 0.8;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.05);
                opacity: 0.95;
            }
        }

        @media (max-width: 600px) {
            .value {
                font-size: 24px;
            }
    </style>


    <style>
        .small-box::before {
            content: "";
            position: absolute;
            height: 2px;
            width: 100%;
            top: 0;
            left: -100%;
            background: linear-gradient(to right, white, cyan, white);
            animation: moveRight 2s linear infinite;
        }

        .small-box::after {
            content: "";
            position: absolute;
            width: 2px;
            height: 100%;
            right: 0;
            top: -100%;
            background: linear-gradient(to bottom, white, cyan, white);
            animation: moveDown 2s linear infinite 0.5s;
        }


        .small-box .bottom-line {
            content: "";
            position: absolute;
            height: 2px;
            width: 100%;
            bottom: 0;
            right: -100%;
            background: linear-gradient(to left, white, cyan, white);
            animation: moveLeft 2s linear infinite 1s;
        }

        .small-box .left-line {
            content: "";
            position: absolute;
            width: 2px;
            height: 100%;
            left: 0;
            bottom: -100%;
            background: linear-gradient(to top, white, cyan, white);
            animation: moveUp 2s linear infinite 1.5s;
        }

        /* Keyframes */
        @keyframes moveRight {
            0% {
                left: -100%;
            }

            50% {
                left: 0%;
            }

            100% {
                left: 100%;
            }
        }

        @keyframes moveDown {
            0% {
                top: -100%;
            }

            50% {
                top: 0%;
            }

            100% {
                top: 100%;
            }
        }

        @keyframes moveLeft {
            0% {
                right: -100%;
            }

            50% {
                right: 0%;
            }

            100% {
                right: 100%;
            }
        }

        @keyframes moveUp {
            0% {
                bottom: -100%;
            }

            50% {
                bottom: 0%;
            }

            100% {
                bottom: 100%;
            }
        }
    </style>

    <style>
        table.dataTable {
            border-radius: 12px;
            overflow: hidden;
            background-color: #fff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        table.dataTable thead th {
            background: #5b368e;
            color: white;
            font-weight: bold;
            text-align: center;
        }

        table.dataTable tbody td {
            text-align: center;
            font-size: 14px;
        }

        table.dataTable tbody tr:hover {
            background-color: #f3f3f3;
            transition: 0.3s ease-in-out;
        }

        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #ccc;
            border-radius: 6px;
            padding: 6px 10px;
        }

        .dataTables_wrapper .dataTables_length select {
            border-radius: 6px;
            padding: 4px;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 5px 10px;
            margin: 2px;
            border-radius: 6px;
            background-color: #5b368e;
            color: white !important;
            border: none;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background-color: #333 !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background-color: #333 !important;
            color: #fff !important;
        }
    </style>

    <style>
        .content-header {
            background-color: #ffffff !important;
            padding: 20px 30px;
            border-bottom: 2px solid #ccc;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 0 0 10px 10px;
        }

        ol.breadcrumb {
            border-radius: 10px !important;
        }

        .content-header h1 {
            font-size: 24px;
            color: #333;
            margin: 0;
            font-weight: 700;
        }

        .content-header h1 small {
            font-size: 16px;
            color: blue;
        }

        .content-header a.btn-view {
            background-color: #1e7e34;
            color: white;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .content-header a.btn-view:hover {
            background-color: #155d27;
        }

        .content {
            padding: 30px;
        }

        .content .box-default {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.08);
            padding: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: bold;
            color: #333;
        }

        .form-control {
            width: 100%;
            padding: 0px 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #1e7e34;
            box-shadow: 0 0 5px rgba(30, 126, 52, 0.4);
        }

        .btn-primary,
        .breadcrumb a {
            background-color: #1e7e34;
            border: none;
            color: white;
            padding: 10px 20px;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #155d27;
        }

        @media (max-width: 768px) {
            .form-group.row .col-md-4 {
                width: 100%;
                margin-bottom: 15px;
            }
        }
    </style>

    <style>
        .main-sidebar {
            width: 250px;
            height: 100vh;
            background-color: #1f2d3d;
            position: fixed;
            /* padding: unset; */
            top: 0;
            left: 0;
            overflow-y: auto;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.3);
        }

        .main-sidebar .sidebar {
            padding: 20px 0;
        }

        .main-sidebar .sidebar-menu {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu li {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-menu a {
            color: #cfd8dc;
            display: block;
            padding: 12px 20px;
            text-decoration: none;
            font-size: 16px;
            transition: background-color 0.3s;
            position: relative;
        }



        .sidebar-menu a i {
            margin-right: 10px;
        }

        .sidebar-menu .treeview-menu {
            display: none;
            background-color: #263238;
        }


        .label-primary {
            background-color: #42a5f5;
            border-radius: 10px;
            padding: 2px 8px;
            font-size: 12px;
            color: white;
            position: absolute;
            right: 15px;
            top: 12px;
        }

        .sidebar-header {
            background-color: #1976d2;
            padding: 20px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        @media (max-width: 768px) {
            .main-sidebar {
                width: 220px;
                box-shadow: unset;
            }

            .content-header {

                padding: 20px 15px;
                margin-right: 40px;
            }

            .content-header h1 {
                font-size: 16px;
                margin-right: 58px;
            }

            .content-header>.breadcrumb {
                padding-left: 0px;
            }

            .main-sidebar {
                overflow-y: unset;
            }

        }



        @media (max-width: 600px) {


            .day-box {
                padding: unset !important;
                min-width: 60px !important;

            }

            .day-box {
                width: 60px !important;
                height: 41px !important;
                font-size: 14px;
            }
        }
    </style>

    <style>
        .day-container {
            display: flex;
            flex-wrap: wrap;
            gap: 0px;
            padding: 18px;
            background: #fff;
            border-radius: 12px;
            justify-content: center;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        .day-box {
            padding: 14px 20px;
            border-radius: 10px;
            min-width: 90px;
            text-align: center;
            font-size: 12px;
            font-weight: bold;

            background: linear-gradient(145deg, #2980b9, #27ae60);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            position: relative;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .day-box:hover {
            transform: scale(1.06);
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.25);
        }

        .day-yellow {
            background: linear-gradient(145deg, #f39c12, #f1c40f);
        }

        .day-green {
            background: linear-gradient(145deg, #27ae60, #2ecc71);
        }

        .form-control {
            /* padding: unset; */
        }
    </style>


    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #667eea;
            --primary-dark: #5568d3;
            --primary-light: #818cf8;
            --secondary: #764ba2;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --info: #3b82f6;
            --dark: #1e293b;
            --light: #f8fafc;
            --sidebar-width: 280px;
            --header-height: 70px;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #f1f5f9;
            overflow-x: hidden;
        }

        /* ========== HEADER ========== */
        .modern-header {
            position: absolute;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: var(--header-height);
            background: white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            z-index: 999;
            transition: all 0.3s ease;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .menu-toggle {
            display: none;
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: var(--light);
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .menu-toggle:hover {
            background: #e2e8f0;
        }

        .header-search {
            position: relative;
        }

        .header-search input {
            width: 350px;
            padding: 12px 45px 12px 45px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .header-search input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        .header-search i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .header-icon-btn {
            position: relative;
            width: 45px;
            height: 45px;
            border-radius: 12px;
            background: var(--light);
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            color: #64748b;
        }

        .header-icon-btn:hover {
            background: #e2e8f0;
            color: var(--primary);
        }

        .header-icon-btn .badge {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 18px;
            height: 18px;
            background: var(--danger);
            color: white;
            border-radius: 50%;
            font-size: 10px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 15px;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .user-profile:hover {
            background: var(--light);
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 16px;
        }

        .user-info h4 {
            font-size: 14px;
            font-weight: 600;
            color: var(--dark);
        }

        .user-info p {
            font-size: 12px;
            color: #94a3b8;
        }

        /* ========== SIDEBAR ========== */
        .modern-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            padding: 25px 0;
            overflow-y: auto;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .modern-sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .modern-sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        .modern-sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
        }

        .sidebar-logo {
            padding: 0 25px 30px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 30px;
        }

        .sidebar-logo h2 {
            font-size: 24px;
            font-weight: 800;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-logo img {
            height: 35px;
            border-radius: 8px;
        }

        .sidebar-actions {
            padding: 0 25px 20px;
            display: flex;
            gap: 10px;
        }

        .sidebar-btn {
            flex: 1;
            padding: 10px;
            border: none;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }

        .sidebar-btn.logout {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
        }

        .sidebar-btn.logout:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
        }

        .sidebar-btn.user {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
        }

        .sidebar-btn.user:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        }

        .sidebar-menu {
            list-style: none;
            padding: 0 15px;
        }

        .menu-header {
            padding: 20px 10px 10px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #64748b;
        }

        .menu-item {
            margin-bottom: 5px;
        }

        .menu-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 15px;
            color: #cbd5e1;
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.3s ease;
            position: relative;
            font-weight: 500;
            font-size: 14px;
        }

        .menu-link i {
            width: 20px;
            font-size: 18px;
        }

        .menu-link:hover {
            background: rgba(255, 255, 255, 0.08);
            color: white;
            transform: translateX(5px);
        }

        .menu-item.active .menu-link {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .menu-badge {
            margin-left: auto;
            background: var(--danger);
            color: white;
            padding: 3px 8px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
        }

        .menu-arrow {
            margin-left: auto;
            transition: transform 0.3s ease;
        }

        .menu-item.open .menu-arrow {
            transform: rotate(90deg);
        }

        .submenu {
            display: none;
            padding-left: 35px;
            margin-top: 5px;
        }

        .menu-item.open .submenu {
            display: block;
        }

        .submenu-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 15px;
            color: #94a3b8;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-size: 13px;
            margin-bottom: 3px;
        }

        .submenu-link:hover {
            background: rgba(255, 255, 255, 0.05);
            color: #e2e8f0;
            transform: translateX(5px);
        }

        .submenu-link i {
            font-size: 6px;
        }

        /* ========== MAIN CONTENT ========== */
        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: var(--header-height);
            padding: 30px;
            min-height: calc(100vh - var(--header-height));
            transition: all 0.3s ease;
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 1024px) {
            .modern-sidebar {
                transform: translateX(-100%);
            }

            .modern-sidebar.active {
                transform: translateX(0);
            }

            .modern-header {
                left: 0;
            }

            .main-content {
                margin-left: 0;
            }

            .menu-toggle {
                display: flex;
                justify-content: center;
                align-items: center;
                font-size: 25px;
            }

            .header-search input {
                width: 200px;
            }
        }

        @media (max-width: 768px) {
            .header-search {
                display: none;
            }

            .user-info {
                display: none;
            }
        }

        /* ========== ANIMATIONS ========== */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .menu-item {
            animation: slideIn 0.3s ease forwards;
        }

        .menu-item:nth-child(1) {
            animation-delay: 0.05s;
        }

        .menu-item:nth-child(2) {
            animation-delay: 0.1s;
        }

        .menu-item:nth-child(3) {
            animation-delay: 0.15s;
        }

        .menu-item:nth-child(4) {
            animation-delay: 0.2s;
        }

        .menu-item:nth-child(5) {
            animation-delay: 0.25s;
        }
    </style>


    <style>
        .member-join-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Page Header */
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            padding: 30px 40px;
            margin-bottom: 30px;
            color: white;
            box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
        }

        .page-header h1 {
            font-size: 32px;
            font-weight: 800;
            margin: 0 0 10px;
        }

        .page-header p {
            margin: 0;
            opacity: 0.9;
            font-size: 16px;
        }

        /* Flash Messages */
        .flash-message {
            padding: 20px 25px;
            border-radius: 15px;
            margin-bottom: 25px;
            display: flex;
            align-items: start;
            gap: 15px;
            animation: slideDown 0.4s ease;
            position: relative;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .flash-success {
            background: linear-gradient(135deg, #d3f9d8 0%, #b2f2bb 100%);
            border-left: 4px solid #51cf66;
            color: #2b8a3e;
        }

        .flash-error {
            background: linear-gradient(135deg, #ffe3e3 0%, #ffc9c9 100%);
            border-left: 4px solid #ff6b6b;
            color: #c92a2a;
        }

        .flash-message ul {
            margin: 0;
            padding-left: 20px;
        }

        .close-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            opacity: 0.6;
            transition: opacity 0.3s ease;
        }

        .close-btn:hover {
            opacity: 1;
        }

        /* Form Card */
        .form-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 25px;
        }

        /* Section Headers */
        .section-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 3px solid #f1f5f9;
        }

        .section-icon {
            width: 50px;
            height: 50px;
            border-radius: 15px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 22px;
        }

        .section-header h3 {
            font-size: 22px;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }

        /* Form Groups */
        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #334155;
            font-size: 14px;
        }

        .form-group label sup {
            color: #ef4444;
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        .form-control:disabled,
        .form-control[readonly] {
            background: yellow;
            color: #64748b;
        }

        select.form-control {
            cursor: pointer;
            appearance: none;
            padding: 0 !important;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23667eea' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 18px center;
            padding-right: 45px;
        }

        /* Search Button */
        .input-with-button {
            display: flex;
            gap: 10px;
        }

        .input-with-button input {
            flex: 1;
        }

        .btn {
            padding: 14px 28px;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: #f1f5f9;
            color: #475569;
        }

        .btn-secondary:hover {
            background: #e2e8f0;
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
        }

        /* Radio Buttons */
        .radio-group {
            display: flex;
            gap: 30px;
            padding: 15px 0;
        }

        .radio-option {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }

        .radio-option input[type="radio"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
            accent-color: #667eea;
        }

        .radio-option label {
            margin: 0;
            cursor: pointer;
            font-weight: 500;
        }

        /* Member ID Display */
        .member-id-display {
            position: relative;
        }

        .member-id-badge {
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            pointer-events: none;
        }

        /* Info Box */
        .info-box {
            background: linear-gradient(135deg, #e0e7ff, #ddd6fe);
            border-left: 4px solid #667eea;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            display: flex;
            align-items: start;
            gap: 15px;
        }

        .info-box i {
            font-size: 24px;
            color: #667eea;
        }

        .info-box-content h4 {
            margin: 0 0 5px;
            font-size: 16px;
            font-weight: 700;
            color: #1e293b;
        }

        .info-box-content p {
            margin: 0;
            color: #475569;
            font-size: 14px;
        }

        /* Submit Section */
        .submit-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 30px;
            border-top: 2px solid #f1f5f9;
            margin-top: 40px;
        }

        .submit-section .btn {
            padding: 16px 40px;
            font-size: 16px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .form-card {
                padding: 25px;
            }

            .page-header {
                padding: 25px;
            }

            .section-header {
                flex-direction: column;
                align-items: start;
            }

            .submit-section {
                flex-direction: column;
                gap: 15px;
            }

            .submit-section .btn {
                width: 100%;
            }
        }

        /* Loading State */
        .btn.loading {
            position: relative;
            pointer-events: none;
            opacity: 0.7;
        }

        .btn.loading::after {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            top: 50%;
            left: 50%;
            margin-left: -8px;
            margin-top: -8px;
            border: 2px solid white;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 0.6s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        input[type=file] {
            padding: 0px 0 40px 0px;
        }
    </style>


<style>
    .menu-item.active > .menu-link {
        background-color: #007bff;
        color: #fff;
        border-radius: 5px;
    }

    .submenu-link.active {
        background-color: #e9ecef;
        color: #000;
        font-weight: bold;
    }

    .menu-arrow.rotate {
        transform: rotate(90deg);
        transition: 0.2s ease;
    }

    .submenu {
        margin-left: 15px;
        display: none;
    }

    .menu-item.open > .submenu {
        display: block;
    }

    .menu-search input {
        width: 100%;
        border-radius: 5px;
    }
</style>


</head>
