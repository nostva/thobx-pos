<?php
/**
 * @var string $selected_printer
 * @var bool   $print_after_sale
 * @var array  $config
 */
?>
<script type="text/javascript">

    // ════════════════════════════════════════════════════════════════
    //  CONFIGURATION
    // ════════════════════════════════════════════════════════════════
    var PAPER_WIDTH_MM = 80;
    // ════════════════════════════════════════════════════════════════

    var PAPER_WIDTH_IN = (PAPER_WIDTH_MM === 80) ? 3.15 : 2.28;
    var PRINTER_NAME = '<?= $config['qz_printer_name'] ?? '' ?>';
    var QZ_ENABLE = <?= (int)($config['qz_enable'] ?? 0) ?>;
    // ────────────────────────────────────────────────────────────────
    //  QZ TRAY — connect on page load
    // ────────────────────────────────────────────────────────────────



    if (window.qz) {
        qz.security.setCertificatePromise(function(resolve, reject) {
            fetch('<?= site_url('qz/cert') ?>', { cache: 'no-store' })
            .then(res => res.text())
            .then(resolve, reject);
        });

        qz.security.setSignatureAlgorithm('SHA512');
        qz.security.setSignaturePromise(function(toSign) {
            return function(resolve, reject) {
                fetch('<?= site_url('qz/sign') ?>?request=' + encodeURIComponent(toSign), { cache: 'no-store' })
                    .then(res => res.text())
                    .then(resolve, reject);
            };
        });


        qz.websocket.connect().catch(function (err) {
            console.warn('[QZ] Not reachable:', err.message);
        });
    }

    // ════════════════════════════════════════════════════════════════
    //  CSS EXTRACTION
    // ════════════════════════════════════════════════════════════════
    function extractCSS() {
        var baseCSS = '';   // no media query  → foundation
        var printCSS = '';   // @media print    → promoted overrides
        var linkTags = '';   // cross-origin sheets  → re-linked

        Array.prototype.forEach.call(document.styleSheets, function (sheet) {
            try {
                var rules = sheet.cssRules || sheet.rules || [];

                Array.prototype.forEach.call(rules, function (rule) {
                    if (rule.type === 7 /* @keyframes */ ||
                        rule.type === 8 /* @keyframe step */) return;

                    if (rule.type === 4 /* @media */) {
                        var media = rule.conditionText || rule.media.mediaText || '';

                        if (/\bprint\b/.test(media)) {

                            Array.prototype.forEach.call(rule.cssRules || [], function (r) {
                                printCSS += r.cssText + '\n';
                            });
                        }
                        return;
                    }

                    baseCSS += rule.cssText + '\n';
                });

            } catch (e) {
                // Cross-origin stylesheet — re-link so the WebView fetches it
                if (sheet.href) {
                    linkTags += '<link rel="stylesheet" href="' + sheet.href + '">\n';
                }
            }
        });

        return { baseCSS: baseCSS, printCSS: printCSS, linkTags: linkTags };
    }

    // ════════════════════════════════════════════════════════════════
    //  DIRECTION DETECTION
    // ════════════════════════════════════════════════════════════════
    var RTL_LANGS = /^(ar|he|fa|ur|yi|dv|ckb|ks|ps|sd|ug|pnb|rhg|zgh)\b/i;

    function detectDir(el) {
        if (el && el.dir) return el.dir;
        if (document.documentElement.dir) return document.documentElement.dir;
        if (document.body && document.body.dir) return document.body.dir;
        var lang = document.documentElement.lang || '';
        return RTL_LANGS.test(lang) ? 'rtl' : 'ltr';
    }

    // ════════════════════════════════════════════════════════════════
    //  PIXEL HTML BUILDER
    // ════════════════════════════════════════════════════════════════
    function buildPixelHtml(receiptEl) {
        var maxWidth = (PAPER_WIDTH_MM === 80) ? '80mm' : '58mm';
        var dir = detectDir(receiptEl);
        var lang = document.documentElement.lang || (dir === 'rtl' ? 'ar' : 'en');

        var extracted = extractCSS();

        var paperCSS = [
            '*, *::before, *::after {',
            '  box-sizing: border-box !important;',
            '  -webkit-print-color-adjust: exact !important;',
            '  print-color-adjust: exact !important;',
            '}',
            'html, body { margin: 0 !important; padding: 0 !important; }',
            '#receipt_wrapper {',
            '  width: 100% !important;',
            '  max-width: ' + maxWidth + ' !important;',
            '  margin: 0 auto !important;',
            '}'
        ].join('\n');

        var cdnPromotionScript = extracted.linkTags
            ? '<script>\n'
            + 'document.addEventListener("DOMContentLoaded", function () {\n'
            + '  var s = document.createElement("style");\n'
            + '  var c = "";\n'
            + '  [].forEach.call(document.styleSheets, function (sheet) {\n'
            + '    try {\n'
            + '      [].forEach.call(sheet.cssRules, function (rule) {\n'
            + '        if (rule.type !== 4) return;\n'
            + '        var m = rule.conditionText || rule.media.mediaText || "";\n'
            + '        if (!/\\bprint\\b/.test(m)) return;\n'
            + '        [].forEach.call(rule.cssRules || [], function (r) {\n'
            + '          c += r.cssText + "\\n";\n'
            + '        });\n'
            + '      });\n'
            + '    } catch (e) {}\n'
            + '  });\n'
            + '  s.textContent = c;\n'
            + '  document.head.appendChild(s);\n'
            + '});\n'
            + '<\/script>\n'
            : '';

        var clone = receiptEl.cloneNode(true);
        Array.prototype.forEach.call(
            clone.querySelectorAll('.collapse:not(.show)'),
            function (el) { el.classList.add('show'); }
        );

        return '<!DOCTYPE html>\n'
            + '<html dir="' + dir + '" lang="' + lang + '">\n'
            + '<head>\n'
            + '<meta charset="UTF-8">\n'
            + extracted.linkTags
            + '<style>\n' + extracted.baseCSS + '\n</style>\n'
            + '<style>\n' + extracted.printCSS + '\n</style>\n'
            + '<style>\n' + paperCSS + '\n</style>\n'
            + cdnPromotionScript
            + '</head>\n'
            + '<body dir="' + dir + '">\n'
            + clone.outerHTML + '\n'
            + '</body>\n'
            + '</html>';
    }

    // ════════════════════════════════════════════════════════════════
    //  PRINT PATH — QZ Tray → named printer (pixel / HTML)
    // ════════════════════════════════════════════════════════════════
    async function printViaPixel(receiptEl) {
        if (!PRINTER_NAME) {
            throw new Error('No printer name — configure it in OSPOS Settings → Printing');
        }

        var config = qz.configs.create(PRINTER_NAME, {
            colorType: 'grayscale',
            margins: { top: 0, right: 0, bottom: 0, left: 0 }
        });

        var data = [{
            type: 'pixel',
            format: 'html',
            flavor: 'plain',
            data: buildPixelHtml(receiptEl),
            options: { pageWidth: PAPER_WIDTH_IN }
        }];

        await qz.print(config, data);
        console.log('[printdoc] Sent to printer:', PRINTER_NAME);
    }


    // ════════════════════════════════════════════════════════════════
    //  PREVIEW — opens the pixel HTML in a sized window
    // ════════════════════════════════════════════════════════════════
    function previewReceipt() {
        var receiptEl = document.getElementById('receipt_wrapper');
        if (!receiptEl) { alert('[preview] #receipt_wrapper not found'); return; }

        var html = buildPixelHtml(receiptEl);
        var blob = new Blob([html], { type: 'text/html; charset=utf-8' });
        var url = URL.createObjectURL(blob);
        var paperPx = Math.round(PAPER_WIDTH_MM / 25.4 * 96);  // mm → px @ 96 dpi

        var win = window.open(
            url, '_blank',
            'width=' + (paperPx + 32) + ',height=900,scrollbars=yes,resizable=yes'
        );
        if (win) {
            win.addEventListener('load', function () { URL.revokeObjectURL(url); });
        } else {
            // Popup blocker fallback
            window.open(url, '_blank');
        }
    }


    // ════════════════════════════════════════════════════════════════
    //  MAIN: printdoc()
    // ════════════════════════════════════════════════════════════════
    async function printdoc() {
        console.log("QZ_ENABLE", QZ_ENABLE);
        console.log("PRINTER_NAME", PRINTER_NAME);
        if (QZ_ENABLE) {
            var receiptEl = document.getElementById('receipt_wrapper');
            if (!receiptEl) {
                console.error('[printdoc] #receipt_wrapper not found — falling back to window.print()');
                window.print();
                return;
            }
            
            // ── QZ Tray path ──────────────────────────────────────────
            if (window.qz && qz.websocket.isActive()) {
                try {
                    await printViaPixel(receiptEl);
                    return;
                } catch (err) {
                    console.error('[printdoc] QZ print failed:', err.message);
                }
            } else {
                console.warn('[printdoc] QZ Tray not connected — using browser fallback');
            }
        }

        // ── Browser fallbacks ──────────────────────────────────────
        if (window.jsPrintSetup) {
            jsPrintSetup.setOption('marginTop', '<?= $config['print_top_margin'] ?>');
            jsPrintSetup.setOption('marginLeft', '<?= $config['print_left_margin'] ?>');
            jsPrintSetup.setOption('marginBottom', '<?= $config['print_bottom_margin'] ?>');
            jsPrintSetup.setOption('marginRight', '<?= $config['print_right_margin'] ?>');
        <?php if (!$config['print_header']) { ?>
                    jsPrintSetup.setOption('headerStrLeft', '');
                    jsPrintSetup.setOption('headerStrCenter', '');
                    jsPrintSetup.setOption('headerStrRight', '');
        <?php } ?>
        <?php if (!$config['print_footer']) { ?>
                    jsPrintSetup.setOption('footerStrLeft', '');
                    jsPrintSetup.setOption('footerStrCenter', '');
                    jsPrintSetup.setOption('footerStrRight', '');
        <?php } ?>
        var printers = jsPrintSetup.getPrintersList().split(',');
            for (var i = 0; i < printers.length; i++) {
                if (printers[i] === PRINTER_NAME) {
                    jsPrintSetup.setPrinter(printers[i]);
                    jsPrintSetup.clearSilentPrint();
                <?php if (!$config['print_silently']) { ?>
                            jsPrintSetup.setOption('printSilent', 1);
                <?php } ?>
                        jsPrintSetup.print();
                    break;
                }
            }
        } else {
            window.print();
        }
    }

<?php if ($print_after_sale) { ?>
            $(window).on('load', function () {
                printdoc();
                setTimeout(function () {
                    window.location.href = '<?= site_url('sales') ?>';
                }, <?= $config['print_delay_autoreturn'] * 1000 ?>);
            });
<?php } ?>
</script>