export const exportToCSV = (filename, rows) => {
    if (!rows || !rows.length) return;

    const separator = ';';
    const keys = Object.keys(rows[0]);

    const csvContent =
        keys.join(separator) +
        '\n' +
        rows.map(row => {
            return keys.map(k => {
                let cell = row[k] === null || row[k] === undefined ? '' : row[k];
                cell = cell instanceof Date
                    ? cell.toLocaleString()
                    : cell.toString().replace(/"/g, '""');
                if (cell.search(new RegExp(`("|${separator}|\n)`, 'g')) >= 0) {
                    cell = `"${cell}"`;
                }
                return cell;
            }).join(separator);
        }).join('\n');

    // Adiciona o BOM (\uFEFF) para garantir que o Excel brasileiro leia os acentos em UTF-8 corretamente.
    const BOM = '\uFEFF';
    const blob = new Blob([BOM + csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    if (link.download !== undefined) {
        const url = URL.createObjectURL(blob);
        link.setAttribute('href', url);
        link.setAttribute('download', filename);
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
};

export const exportToWord = (filename, rows) => {
    if (!rows || !rows.length) return;

    // Filter out some heavy/less critical columns for better Word layout fit
    const ignoredKeys = ['ID', 'Localização', 'Observação'];
    const keys = Object.keys(rows[0]).filter(k => !ignoredKeys.includes(k));

    // Monta a tabela HTML para ser convertida em Word e manter as colunas alinhadas
    // Added specific MSO Office XML settings to force landscape mode and adjust table layout
    let htmlContent = `
    <html xmlns:v="urn:schemas-microsoft-com:vml"
    xmlns:o="urn:schemas-microsoft-com:office:office"
    xmlns:w="urn:schemas-microsoft-com:office:word"
    xmlns:m="http://schemas.microsoft.com/office/2004/12/omml"
    xmlns="http://www.w3.org/TR/REC-html40">
    <head>
        <meta charset='utf-8'>
        <title>Faro de Ouro Export</title>
        <!--[if gte mso 9]>
        <xml>
            <w:WordDocument>
                <w:View>Print</w:View>
                <w:Zoom>100</w:Zoom>
                <w:DoNotOptimizeForBrowser/>
            </w:WordDocument>
        </xml>
        <![endif]-->
        <style>
            @page WordSection1 {
                size: 841.9pt 595.3pt; /* A4 Landscape */
                mso-page-orientation: landscape;
                margin: 36.0pt 36.0pt 36.0pt 36.0pt;
                mso-header-margin: 35.4pt;
                mso-footer-margin: 35.4pt;
                mso-paper-source: 0;
            }
            div.WordSection1 { page: WordSection1; }
            body { font-family: Arial, sans-serif; font-size: 10pt; }
            table {
                border-collapse: collapse;
                width: 100%;
                mso-table-layout-alt: fixed;
                word-wrap: break-word;
            }
            th, td {
                border: 1px solid #999999;
                text-align: left;
                padding: 4px;
                vertical-align: top;
            }
            th {
                background-color: #f2f2f2;
                font-weight: bold;
                font-size: 11pt;
            }
            /* Reduce font size for potentially long content columns */
            td { font-size: 9pt; }
            h2 { text-align: center; font-size: 16pt; color: #333; }
        </style>
    </head>
    <body>
        <div class="WordSection1">
            <h2>Relatório de Estoque e Produtos</h2>
            <table>
            <thead>
                <tr>
                    ${keys.map(k => `<th>${k}</th>`).join('')}
                </tr>
            </thead>
            <tbody>
                ${rows.map(row => `
                    <tr>
                        ${keys.map(k => {
                            let val = row[k] !== null && row[k] !== undefined ? row[k] : '';
                            return `<td>${val}</td>`;
                        }).join('')}
                    </tr>
                `).join('')}
            </tbody>
            </table>
        </div>
    </body>
    </html>
    `;

    // Exporta o HTML como .doc (Word)
    const blob = new Blob(['\ufeff', htmlContent], { type: 'application/msword' });
    const link = document.createElement('a');
    if (link.download !== undefined) {
        const url = URL.createObjectURL(blob);
        link.setAttribute('href', url);
        link.setAttribute('download', filename);
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
};
