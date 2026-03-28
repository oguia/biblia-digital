<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Imprimir Etiquetas</title>
    <style>
        @media print {
            @page {
                size: 100mm 50mm;
                margin: 0;
            }
            body {
                margin: 0;
            }
            .label-container {
                page-break-after: always;
            }
            .no-print {
                display: none;
            }
        }

        body {
            font-family: Arial, sans-serif;
            background: #eee;
        }

        .label-container {
            width: 100mm;
            height: 50mm;
            background: white;
            padding: 2mm;
            box-sizing: border-box;
            border: 1px dashed #ccc; /* Border for screen view */
            margin: 10px auto;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center; /* Vertical center */
            align-items: center; /* Horizontal center */
            text-align: center;
        }

        @media print {
            .label-container {
                border: none;
                margin: 0;
            }
        }

        .product-name {
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 2mm;
            max-height: 18mm;
            overflow: hidden;
            line-height: 1.1;
        }

        .product-code {
            font-size: 10pt;
            margin-bottom: 1mm;
        }

        .barcode-img {
            max-width: 90%;
            height: 12mm;
        }

        .product-meta {
            font-size: 9pt;
            margin-top: 1mm;
        }

        .print-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<button class="print-btn no-print" onclick="window.print()">Imprimir</button>

<?php foreach ($labels as $label): ?>
    <div class="label-container">
        <div class="product-name"><?= htmlspecialchars($label['nome']) ?></div>
        <div class="product-code"><?= htmlspecialchars($label['codigo']) ?></div>
        <img class="barcode-img" src="data:image/png;base64,<?= $label['barcode'] ?>" alt="Barcode">
        <div class="product-meta">
            <?= htmlspecialchars($label['categoria_nome']) ?> | <?= htmlspecialchars($label['unidade']) ?>
        </div>
    </div>
<?php endforeach; ?>

</body>
</html>
