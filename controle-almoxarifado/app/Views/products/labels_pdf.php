<!DOCTYPE html>
<html>
<head>
    <style>
        @page {
            margin: 10mm;
        }
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            width: 100%;
        }
        .label-box {
            width: 48%; /* 2 columns */
            height: 50mm;
            float: left;
            margin-right: 2%; /* Gap */
            margin-bottom: 5mm;
            border: 1px dotted #ccc;
            box-sizing: border-box;
            padding: 2mm;
            text-align: center;
            overflow: hidden;
            page-break-inside: avoid;
        }
        .label-box:nth-child(2n) {
            margin-right: 0;
        }
        .product-name {
            font-size: 12pt;
            font-weight: bold;
            margin-bottom: 1mm;
            height: 14mm;
            overflow: hidden;
        }
        .barcode-img {
            height: 10mm;
            max-width: 90%;
        }
        .meta {
            font-size: 8pt;
            margin-top: 1mm;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php foreach ($labels as $label): ?>
            <div class="label-box">
                <div class="product-name"><?= htmlspecialchars($label['nome']) ?></div>
                <div style="font-size: 9pt;"><?= htmlspecialchars($label['codigo']) ?></div>
                <img class="barcode-img" src="data:image/png;base64,<?= $label['barcode'] ?>" alt="Barcode">
                <div class="meta"><?= htmlspecialchars($label['categoria_nome']) ?></div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
