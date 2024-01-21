<style>
    /*
    Common invoice styles. These styles will work in a browser or using the HTML
    to PDF anvil endpoint.
    */

    body {
        font-size: 16px;
        color: rgb(54, 54, 54);
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    table tr td {
        padding: 0;
    }

    table tr td:last-child {
        text-align: right;
    }

    .bold {
        font-weight: bold;
    }

    .right {
        text-align: right;
    }

    .large {
        font-size: 1.75em;
    }

    .total {
        font-weight: bold;
        color: #fb7578;
    }

    .logo-container {
        margin: 20px 0 20px 0;
    }

    .invoice-info-container {
        font-size: 0.875em;
    }

    .invoice-info-container td {
        padding: 4px 0;
    }

    .client-name {
        font-size: 1.5em;
        vertical-align: top;
    }

    .line-items-container {
        margin: 60px 0;
        font-size: 0.875em;
    }

    .line-items-container th {
        text-align: left;
        color: rgb(0, 0, 0);
        border-bottom: 2px solid rgb(0, 0, 0);
        padding: 10px 0 15px 0;
        font-size: 0.75em;
        text-transform: uppercase;
    }

    .line-items-container th:last-child {
        text-align: right;
    }

    .line-items-container td {
        padding: 15px 0;
    }

    .line-items-container tbody tr:first-child td {
        padding-top: 25px;
    }

    .line-items-container.has-bottom-border tbody tr:last-child td {
        padding-bottom: 25px;
        border-bottom: 2px solid rgb(0, 0, 0);
    }

    .line-items-container.has-bottom-border {
        margin-bottom: 0;
    }

    .line-items-container th.heading-quantity {
        width: 70px;
        text-align: center;
    }

    .line-items-container th.heading-unity {
        width: 90px;
        text-align: center;
    }

    .line-items-container th.heading-price {
        text-align: right;
        width: 100px;
    }

    .line-items-container th.heading-subtotal {
        width: 100px;
    }

    .payment-info {
        width: 38%;
        font-size: 0.75em;
        line-height: 1.5;
    }

    .footer {
        margin-top: 100px;
    }

    .footer-thanks {
        font-size: 1.125em;
    }

    .footer-thanks img {
        display: inline-block;
        position: relative;
        top: 1px;
        width: 16px;
        margin-right: 4px;
    }

    .footer-info {
        float: right;
        margin-top: 5px;
        font-size: 0.75em;
        color: #ccc;
    }

    .footer-info span {
        padding: 0 5px;
        color: black;
    }

    .footer-info span:last-child {
        padding-right: 0;
    }

    .page-container {
        display: none;
    }

    /*
    The styles here for use when generating a PDF invoice with the HTML code.

    * Set up a repeating page counter
    * Place the .footer-info in the last page's footer
    */d

    .footer {
        margin-top: 50px;
    }

    .footer-info {
        float: none;
        position: running(footer);
        margin-top: -25px;
    }

    .page-container {
        display: block;
        position: running(pageContainer);
        margin-top: -40px;
        font-size: 12px;
        text-align: right;
        color: #999;
    }

    .page-container .page::after {
        content: counter(page);
    }

    .page-container .pages::after {
        content: counter(pages);
    }


    @page {
        @bottom-right {
            content: element(pageContainer);
        }

        @bottom-left {
            content: element(footer);
        }
    }

</style>
