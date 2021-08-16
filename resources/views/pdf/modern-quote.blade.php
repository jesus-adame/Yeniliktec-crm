<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <title>Quotation {{ $quote->folio }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="/templates/web/modern-normalize.css">
    <link rel="stylesheet" href="/templates/web/web-base.css">
    <link rel="stylesheet" href="/templates/invoice.css">
    <script type="text/javascript" src="/templates/web/scripts.js"></script>
    @include('pdf.styles.styles')

    <style>
        /* These styles are only for rendering in a browser */

        .web-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 50px;
        }
    </style>
</head>

<body>
    <div class="web-container">
        <div class="logo-container" style="margin-top: -40px;">
            <img style="height: 120px; margin-bottom: -30px; margin-left: -10px" src="templates/img/logo_yeniliktec2.png">
        </div>

        <table class="invoice-info-container">
            <tr>
                <td rowspan="2" class="client-name">
                    Cliente <br> {{ $quote->contact->name }} {{ $quote->contact->last_name }}
                </td>
                <td>
                    YENILIKTEC SA DE CV
                </td>
            </tr>
            <tr>
                <td>
                    Pradera 62170
                </td>
            </tr>
            <tr>
                <td>
                    Fecha de cotización: <strong>{{ $quote->created_at->format('d/m/Y') }}</strong>
                </td>
                <td>
                    Cuernavaca, Morelos
                </td>
            </tr>
            <tr>
                <td>
                    Folio: <strong>{{ $quote->folio }}</strong>
                </td>
                <td>
                    ventas@yeniliktec.com
                </td>
            </tr>
        </table>


        <table class="line-items-container">
            <thead>
                <tr>
                    <th class="heading-description">Descripción</th>
                    <th class="heading-unity">Unidad</th>
                    <th class="heading-quantity">Cantidad</th>
                    <th class="heading-price">Precio</th>
                    <th class="heading-subtotal">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($quote->items as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td style="text-transform: capitalize; text-align: center">{{ $item->product->unity }}</td>
                        <td style="text-align: center">{{ $item->quantity }}</td>
                        <td class="right">$ {{ number_format($item->price, 2) }}</td>
                        <td class="bold">$ {{ number_format($item->price * $item->quantity, 2) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td style="text-align: right" colspan="4">IVA</td>
                    <td class="bold">$ {{ number_format($taxes, 2) }}</td>
                </tr>
            </tbody>
        </table>


        <table class="line-items-container has-bottom-border">
            <thead>
                <tr>
                    <th>Información de pago</th>
                    <th style="text-align: center">Válido hasta</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="payment-info">
                        <div>Banco: BBVA</div>
                        <div>
                            Account No: <strong>012 180 0151 0033 2076</strong>
                        </div>
                        <div>
                            Routing No: <strong>120000547</strong>
                        </div>
                    </td>
                    <td class="large" style="text-align: center">{{ $quote->created_at->format('M d, Y') }}</td>
                    <td class="large total">$ {{ number_format($total, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="footer">
            <div class="footer-info">
                <span class="bold" style="display: block">
                    Información extra
                </span>
                <span style="display: block; margin-top: 15px">
                    Prestador de servicios:
                </span>
                <span style="display: block; margin-top: 10px">Adame Sandoval Rubén Eduardo</span>
                <span style="display: block; margin-top: 10px">
                    RFC: AASR991021MG1
                </span>
                <span style="display: block; margin-top: 10px">
                    PayPal: https://www.paypal.com/paypalme/yeniliktec
                </span>
                <span style="display: block; margin-top: 10px">
                    Tel: 7773447577 <br>
                        7774290884
                </span>
            </div>
        </div>
    </div>
</body>
</html>
