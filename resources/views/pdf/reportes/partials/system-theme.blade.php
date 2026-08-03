<style>
    /* Documento corporativo reutilizable inspirado en las notas carta. */
    @page {
        margin: 1.05cm 1cm 1.35cm !important;
    }

    html, body {
        color: #17362b !important;
        background: #ffffff !important;
        font-family: DejaVu Sans, Arial, sans-serif !important;
        font-size: 9px !important;
        line-height: 1.42 !important;
    }

    header {
        margin: 0 0 17px !important;
        padding: 0 0 12px !important;
        background: #ffffff !important;
        border: 0 !important;
        border-bottom: 3px solid #1f9254 !important;
        page-break-inside: avoid !important;
    }

    header table,
    .table-head,
    .header-table {
        width: 100% !important;
        margin: 0 !important;
        border: 0 !important;
        border-collapse: collapse !important;
        background: #ffffff !important;
    }

    header th,
    header td,
    .table-head th,
    .table-head td,
    .header-table th,
    .header-table td {
        color: #587067 !important;
        background: #ffffff !important;
        border: 0 !important;
        vertical-align: middle !important;
    }

    header img,
    .table-head img,
    .header-table img {
        max-width: 190px !important;
        max-height: 60px !important;
        width: auto !important;
        height: auto !important;
    }

    .header-title,
    header h1,
    header h2,
    header h3,
    header div[style*="font-size: 18px"],
    header div[style*="font-size:18px"] {
        color: #173f32 !important;
        font-family: DejaVu Sans, Arial, sans-serif !important;
        font-size: 18px !important;
        font-weight: 700 !important;
        line-height: 1.2 !important;
        letter-spacing: .35px !important;
        text-transform: uppercase !important;
    }

    header small,
    header font,
    .report-period {
        display: inline-block !important;
        margin-top: 5px !important;
        padding: 4px 9px !important;
        color: #ffffff !important;
        background: #1f9254 !important;
        border-radius: 10px !important;
        font-family: DejaVu Sans, Arial, sans-serif !important;
        font-size: 7px !important;
        font-weight: 700 !important;
        letter-spacing: .25px !important;
        text-transform: uppercase !important;
    }

    table {
        border-collapse: collapse !important;
        border-spacing: 0 !important;
    }

    main > table,
    body > table:not(.table-head):not(.header-table),
    .table-body,
    .table,
    .table-description,
    .table-pago {
        width: 100% !important;
        max-width: 100% !important;
        margin-bottom: 15px !important;
        border: 1px solid #d4e2dc !important;
    }

    thead th,
    .table-body thead th,
    .table > tbody > tr:first-child > th {
        padding: 8px 6px !important;
        color: #ffffff !important;
        background: #173f32 !important;
        border: 1px solid #173f32 !important;
        font-size: 7px !important;
        font-weight: 700 !important;
        line-height: 1.2 !important;
        text-transform: uppercase !important;
        vertical-align: middle !important;
    }

    tbody td,
    .table-body td,
    .table td,
    .table-description td,
    .table-pago td {
        padding: 7px 6px !important;
        color: #29483d !important;
        background: #ffffff !important;
        border: 0 !important;
        border-bottom: 1px solid #d9e5e0 !important;
        font-size: 8px !important;
        vertical-align: middle !important;
    }

    tbody tr:nth-child(even) td,
    .table-body tr:nth-child(even) td {
        background: #f7faf8 !important;
    }

    tbody tr { page-break-inside: avoid; }

    .totales-table,
    .resumen-section table,
    .table-footer,
    .table-saldo {
        margin: 8px 0 16px !important;
        border: 1px solid #d7e7e0 !important;
        background: #f2f8f5 !important;
    }

    .totales-table th,
    .totales-table td,
    .resumen-section th,
    .resumen-section td,
    .table-footer th,
    .table-footer td,
    .table-saldo th,
    .table-saldo td {
        padding: 8px !important;
        color: #17362b !important;
        background: #f2f8f5 !important;
        border: 1px solid #d7e7e0 !important;
        font-weight: 700 !important;
    }

    .no-border { border: 0 !important; }
    .text-right, .table-saldo { text-align: right !important; }

    footer,
    .footer-inferior {
        position: fixed !important;
        right: 0 !important;
        bottom: -.82cm !important;
        left: 0 !important;
        height: auto !important;
        padding-top: 7px !important;
        color: #71847c !important;
        background: #ffffff !important;
        border: 0 !important;
        border-top: 1px solid #dbe7e2 !important;
        font-size: 7px !important;
        text-align: left !important;
    }

    .page_break, .page-break { page-break-before: always; }
</style>
