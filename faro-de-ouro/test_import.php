<?php
$separator = ';';
$handle = fopen('test_csv.csv', 'r');

$firstLine = fgets($handle);
$separator = strpos($firstLine, ';') !== false ? ';' : ',';
rewind($handle);

$isGpcFormat = false;

while (($data = fgetcsv($handle, 4000, $separator)) !== FALSE) {
    $rowStr = implode("", $data);
    echo "LENDO: " . substr($rowStr, 0, 50) . "\n";
    // Check all columns in this row just in case
    foreach($data as $col) {
        $cleanCol = trim($col);
        // Clean BOM if exists
        if (substr($cleanCol, 0, 3) == "\xEF\xBB\xBF") {
            $cleanCol = substr($cleanCol, 3);
        }

        if (stripos($cleanCol, 'SEQU') !== false) {
            $isGpcFormat = true;
            // Skip the second header row in GPC format
            fgetcsv($handle, 4000, $separator);
            break 2;
        } elseif (stripos($cleanCol, 'Nome') !== false || stripos($cleanCol, 'Name') !== false) {
            break 2;
        }
    }
}

echo "Format is GPC: " . ($isGpcFormat ? 'YES' : 'NO') . "\n";

while (($data = fgetcsv($handle, 4000, $separator)) !== FALSE) {
    if (empty(trim(implode("", $data)))) continue;

    $name = '';
    $code = '';
    $price = 0;
    $min_stock = 0;

    if ($isGpcFormat) {
        $name = trim($data[2] ?? '');
        $code = trim($data[5] ?? '');
        if (empty($code) || $code === 'NÃO POSSUI') {
            $code = trim($data[0] ?? ''); // Fallback to Sequence as SKU
        }

        $priceStr = trim($data[10] ?? '0');
        $priceStr = str_replace(['R$', ' ', '.'], '', $priceStr);
        $priceStr = str_replace(',', '.', $priceStr);
        $price = floatval($priceStr);

        $min_stock = (int)($data[11] ?? 0);
    }
    echo "Parsed: $code - $name (R$ $price) - Min: $min_stock\n";
}
fclose($handle);
