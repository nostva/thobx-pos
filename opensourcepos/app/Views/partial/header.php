<?php
/**
 * @var object $user_info
 * @var array $allowed_modules
 * @var CodeIgniter\HTTP\IncomingRequest $request
 * @var array $config
 */

use Config\Services;

$request = Services::request();
?>

<!doctype html>
<html lang="<?= current_language_code() ?>">
<!-- <html lang="<?= current_language_code() ?>" dir="<?= (strpos(current_language_code(), 'ar') === 0) ? 'rtl' : 'ltr' ?>"> -->


<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="<?= base_url() ?>">
    <title>
        <?= esc($config['company']) ?>
    </title>
    <link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico">

    <script>
        // Modern UI Configuration
        window.tailwind = {
            config: {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Outfit', 'Inter', 'system-ui', 'sans-serif'],
                        },
                        fontSize: {
                            'sm': '0.9375rem', // ~15px
                            'xs': '0.8125rem', // ~13px
                        }
                    }
                }
            }
        };
    </script>

    <style>
        /* Modern Modal Overrides */
        .modal-content {
            border: none;
            box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
            border-radius: 0.75rem;
            /* rounded-xl */
            font-family: 'Inter', sans-serif;
        }

        .modal-header {
            border-bottom: 1px solid #f1f5f9;
            /* slate-100 */
            padding: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-start-start-radius: 0.75rem;
            border-start-end-radius: 0.75rem;
        }

        .modal-title {
            font-weight: 600;
            font-size: 1.125rem;
            /* text-lg */
            color: #0f172a;
            /* slate-900 */
        }

        .modal-body {
            padding: 1.5rem;
            color: #334155;
            /* slate-700 */
        }

        .modal-footer {
            border-top: 1px solid #f1f5f9;
            /* slate-100 */
            padding: 1.25rem 1.5rem;
            background-color: #f8fafc;
            /* slate-50 */
            border-end-start-radius: 0.75rem;
            border-end-end-radius: 0.75rem;
        }

        .modal-backdrop.in {
            opacity: 0.6;
            background-color: #0f172a;
            /* slate-900 */
        }

        /* Fix close button */
        .bootstrap-dialog-close-button {
            display: none !important;
        }

        /* Legacy Print Hacks - Bulletproof suppression */
        @media print {
            @page {
                margin: 0;
            }

            .no-print,
            .print_hide,
            #menubar,
            .sidebar,
            .top-nav,
            .topbar,
            #sidebar-toggle,
            #debug-icon,
            #debug-bar,
            #toolbarContainer {
                display: none !important;
                visibility: hidden !important;
                height: 0 !important;
                width: 0 !important;
                overflow: hidden !important;
            }

            .dashboard-container {
                display: block !important;
            }

            body {
                margin: 0 !important;
                padding: 0 !important;
                background: #fff !important;
            }

            .main-content {
                margin: 0 !important;
                padding: 5mm !important;
            }

            .page-container {
                padding: 0 !important;
                margin: 0 !important;
            }
        }
    </style>

    <link rel="stylesheet"
        href="<?= 'resources/bootswatch/' . (empty($config['theme']) ? 'flatly' : esc($config['theme'])) . '/bootstrap.min.css' ?>">

    <?php if (ENVIRONMENT == 'development' || get_cookie('debug') == 'true' || $request->getGet('debug') == 'true'): ?>
        <!-- inject:debug:css -->
        <link rel="stylesheet" href="resources/css/jquery-ui-fe010342cb.css">
        <link rel="stylesheet" href="resources/css/bootstrap-dialog-1716ef6e7c.css">
        <link rel="stylesheet" href="resources/css/jasny-bootstrap-40bf85f3ed.css">
        <link rel="stylesheet" href="resources/css/bootstrap-datetimepicker-66374fba71.css">
        <link rel="stylesheet" href="resources/css/bootstrap-select-66d5473b84.css">
        <link rel="stylesheet" href="resources/css/bootstrap-table-ed9d1a3360.css">
        <link rel="stylesheet" href="resources/css/bootstrap-table-sticky-header-07d65e7533.css">
        <link rel="stylesheet" href="resources/css/daterangepicker-85523b7dfe.css">
        <link rel="stylesheet" href="resources/css/chartist-c19aedb81a.css">
        <link rel="stylesheet" href="resources/css/chartist-plugin-tooltip-2e0ec92e60.css">
        <link rel="stylesheet" href="resources/css/bootstrap-tagsinput-5a6d46a06c.css">
        <link rel="stylesheet" href="resources/css/bootstrap-toggle-e12db6c1f3.css">
        <link rel="stylesheet" href="resources/css/bootstrap-4875cf7b0d.autocomplete.css">
        <link rel="stylesheet" href="resources/css/invoice-a99a4dfac3.css">
        <link rel="stylesheet" href="resources/css/ospos_print-bf10c1438b.css">
        <link rel="stylesheet" href="resources/css/ospos-28f7f540a3.css">
        <link rel="stylesheet" href="resources/css/popupbox-57d45cb822.css">
        <link rel="stylesheet" href="resources/css/receipt-0606f1c54e.css">
        <link rel="stylesheet" href="resources/css/register-a6a6cc948d.css">
        <link rel="stylesheet" href="resources/css/reports-ace7faf688.css">
        <!-- endinject -->
        <!-- inject:debug:js -->
        <script src="resources/js/jquery-12e87d2f3a.js"></script>
        <script src="resources/js/jquery-4fa896f615.form.js"></script>
        <script src="resources/js/jquery-a0350e8820.validate.js"></script>
        <script src="resources/js/jquery-ui-cbc65ff85e.js"></script>
        <script src="resources/js/bootstrap-894d79839f.js"></script>
        <script src="resources/js/bootstrap-dialog-27123abb65.js"></script>
        <script src="resources/js/jasny-bootstrap-7c6d7b8adf.js"></script>
        <script src="resources/js/bootstrap-datetimepicker-25e39b7ef8.js"></script>
        <script src="resources/js/bootstrap-select-b01896a67b.js"></script>
        <script src="resources/js/bootstrap-table-bdb06552ea.js"></script>
        <script src="resources/js/bootstrap-table-export-6389dc2aa5.js"></script>
        <script src="resources/js/bootstrap-table-mobile-fc655b68ab.js"></script>
        <script src="resources/js/bootstrap-table-sticky-header-cb4d83d172.js"></script>
        <script src="resources/js/moment-d65dc6d2e6.min.js"></script>
        <script src="resources/js/daterangepicker-048c56a690.js"></script>
        <script src="resources/js/es6-promise-855125e6f5.js"></script>
        <script src="resources/js/FileSaver-e73b1946e8.js"></script>
        <script src="resources/js/html2canvas-e1d3a8d7cd.js"></script>
        <script src="resources/js/jspdf-6eb90bf5a3.umd.js"></script>
        <script src="resources/js/jspdf-4f52bd767f.plugin.autotable.js"></script>
        <script src="resources/js/tableExport-0df60917ca.min.js"></script>
        <script src="resources/js/chartist-8a7ecb4445.js"></script>
        <script src="resources/js/chartist-plugin-pointlabels-0a1ab6aa4e.js"></script>
        <script src="resources/js/chartist-plugin-tooltip-116cb48831.js"></script>
        <script src="resources/js/chartist-plugin-axistitle-80a1198058.js"></script>
        <script src="resources/js/chartist-plugin-barlabels-4165273742.js"></script>
        <script src="resources/js/bootstrap-notify-376bc6eb87.js"></script>
        <script src="resources/js/js-fa93e8894e.cookie.js"></script>
        <script src="resources/js/bootstrap-tagsinput-855a7c7670.js"></script>
        <script src="resources/js/bootstrap-toggle-1c7a19a049.js"></script>
        <script src="resources/js/clipboard-908af414ab.js"></script>
        <script src="resources/js/imgpreview-1db063409f.full.jquery.js"></script>
        <script src="resources/js/manage_tables-9b98d5573a.js"></script>
        <script src="resources/js/nominatim-89be77a11a.autocomplete.js"></script>
        <!-- endinject -->
    <?php else: ?>
        <!--inject:prod:css -->
        <link rel="stylesheet" href="resources/opensourcepos-8e34d6a398.min.css">
        <!-- endinject -->

        <!-- Tweaks to the UI for a particular theme should drop here  -->
        <?php if ($config['theme'] != 'flatly' && file_exists($_SERVER['DOCUMENT_ROOT'] . '/public/css/' . esc($config['theme']) . '.css')) { ?>
            <link rel="stylesheet" href="<?= 'css/' . esc($config['theme']) . '.css' ?>">
        <?php } ?>
        <!-- inject:prod:js -->
        <script src="resources/jquery-2c872dbe60.min.js"></script>
        <script src="resources/opensourcepos-39c74204a5.min.js"></script>
        <!-- endinject -->
    <?php endif; ?>

    <?= view('partial/header_js') ?>
    <?= view('partial/lang_lines') ?>

    <!-- Modern UI Assets -->
    <!-- <script src="resources/js/turbo.es2017-umd.js"></script> -->
    <script src="resources/js/tailwind-cdn.js"></script>
    <script src="resources/js/lucide.min.js"></script>
    <link rel="stylesheet" href="resources/css/modern.css">

    <!-- <script>
    // Initialize Lucide icons on every page load (including Turbo navigation)
    function initModernUI() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }

    document.addEventListener("DOMContentLoaded", initModernUI);
    document.addEventListener("turbo:load", initModernUI);
    </script> -->

    <script>
        // BootstrapDialog Global Configuration
        $(document).ready(function () {
            if (typeof BootstrapDialog !== 'undefined') {
                BootstrapDialog.DEFAULT_TEXTS[BootstrapDialog.TYPE_DEFAULT] = 'Information';
                BootstrapDialog.defaultOptions.animate = true;
                BootstrapDialog.defaultOptions.closeIcon = ''; // Handled by CSS
                BootstrapDialog.defaultOptions.spinicon = 'animate-spin'; // Tailwind animate
            }
        });
    </script>
</head>

<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside id="menubar" class="sidebar no-print print_hide">
            <div class="sidebar-header flex items-center justify-center py-6">
                <a href="<?= site_url() ?>" class="flex items-center gap-2 no-underline group">
                    <div
                        class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center text-white shadow-lg shadow-emerald-200 group-hover:scale-110 transition-transform">
                        <i data-lucide="layers" class="w-6 h-6"></i>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-bold text-xl tracking-tight text-slate-900 leading-none">Anis <span
                                class="text-emerald-500">Rose</span></span>
                    </div>
                </a>
            </div>

            <nav class="sidebar-nav">
                <?php
                $icon_map = [
                    'items' => 'box',
                    'sales' => 'shopping-cart',
                    'receivings' => 'truck',
                    'customers' => 'users',
                    'suppliers' => 'briefcase',
                    'employees' => 'user-check',
                    'reports' => 'bar-chart-2',
                    'config' => 'settings',
                    'giftcards' => 'credit-card',
                    'messages' => 'mail',
                    'expenses' => 'dollar-sign',
                    'taxes' => 'percent',
                    'home' => 'home',
                    'office' => 'building'
                ];
                ?>

                <?php foreach ($allowed_modules as $module): ?>
                    <a href="<?= base_url($module->module_id) ?>" title="<?= lang("Module.$module->module_id") ?>"
                        class="sidebar-link <?= $module->module_id == $request->getUri()->getSegment(1) ? 'active' : '' ?>">
                        <i data-lucide="<?= $icon_map[$module->module_id] ?? 'circle' ?>"></i>
                        <span><?= lang('Module.' . $module->module_id) ?></span>
                    </a>
                <?php endforeach; ?>
            </nav>

            <!-- Sidebar Footer Removed/Minimal -->
            <div class="p-4 text-center">
                <div id="liveclock" class="text-[10px] text-slate-300 font-medium">
                    <?= date($config['dateformat'] . ' ' . $config['timeformat']) ?>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="main-content">
            <!-- Top Navigation -->
            <header class="top-nav no-print print_hide">
                <div class="flex items-center gap-4">
                    <button id="sidebar-toggle" class="lg:hidden p-2 hover:bg-slate-100 rounded-md">
                        <i data-lucide="menu" class="w-5 h-5"></i>
                    </button>
                    <nav class="flex items-center text-sm font-medium text-slate-500" aria-label="Breadcrumb">
                        <ol class="flex items-center gap-2">
                            <li>
                                <a href="<?= site_url() ?>" class="hover:text-slate-900 transition-colors">
                                    <i data-lucide="home" class="w-4 h-4"></i>
                                </a>
                            </li>
                            <?php
                            $uri = $request->getUri();
                            $segments = $uri->getSegments();
                            $accumulatedPath = '';

                            // Only show the first segment if it exists
                            if (!empty($segments)) {
                                $segment = $segments[0];
                                $accumulatedPath = $segment . '/';
                                $label = lang('Module.' . $segment);

                                // Fallback if no translation or it's an ID/Action
                                if (strpos($label, 'Module.') === 0 || is_numeric($segment)) {
                                    $label = ucfirst($segment);
                                }
                                ?>
                                <li>
                                    <i data-lucide="chevron-right" class="w-4 h-4 text-slate-400 rtl:rotate-180"></i>
                                </li>
                                <li>
                                    <span class="text-slate-900 font-semibold"><?= $label ?></span>
                                </li>
                            <?php } ?>
                        </ol>
                    </nav>
                </div>

                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-4">
                        <!-- User Dropdown / Profile -->
                        <div class="dropdown">
                            <div class="flex items-center gap-3 bg-white border border-slate-200 rounded-full pl-1 pr-4 py-1 shadow-sm hover:shadow-md transition-shadow cursor-pointer dropdown-toggle"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <div
                                    class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs font-bold border border-emerald-200">
                                    <?= substr($user_info->first_name, 0, 1) . substr($user_info->last_name, 0, 1) ?>
                                </div>
                                <div class="flex flex-col">
                                    <span
                                        class="text-xs font-bold text-slate-800 leading-tight"><?= "$user_info->first_name $user_info->last_name" ?></span>
                                    <span
                                        class="text-[10px] text-slate-500 leading-tight"><?= lang('Common.employee') ?></span>
                                </div>
                                <i data-lucide="chevron-down" class="w-3 h-3 text-slate-400 ml-2"></i>
                            </div>
                            <ul
                                class="dropdown-menu dropdown-menu-right mt-2 min-w-[200px] border-0 shadow-xl rounded-xl p-2 animated fadeIn">
                                <li>
                                    <?= anchor(
                                        "home/changePassword/$user_info->person_id",
                                        '<i data-lucide="key" class="w-4 h-4 mr-2"></i> ' . lang('Employees.change_password'),
                                        ['class' => 'modal-dlg flex items-center px-3 py-2 text-sm text-slate-700 hover:bg-emerald-50 hover:text-emerald-600 rounded-lg transition-colors', 'data-btn-submit' => lang('Common.submit'), 'title' => lang('Employees.change_password')]
                                    )
                                        ?>
                                </li>
                                <li class="divider my-1 border-slate-100"></li>
                                <li>
                                    <?= anchor(
                                        'home/logout',
                                        '<i data-lucide="log-out" class="w-4 h-4 mr-2"></i> ' . lang('Login.logout'),
                                        ['class' => 'flex items-center px-3 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700 rounded-lg transition-colors']
                                    )
                                        ?>
                                </li>
                            </ul>
                        </div>
                    </div>
            </header>

            <!-- Page Container -->
            <main class="page-container animate-page-entry">