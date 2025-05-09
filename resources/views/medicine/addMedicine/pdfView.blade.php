<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Side-by-Side PDF Preview</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 95%;
            margin: 40px auto;
            background-color: #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
            padding: 20px;
        }

        .header {
            padding: 16px;
            background-color: #2c3e50;
            color: #fff;
            font-size: 1.5rem;
            border-radius: 6px 6px 0 0;
            margin-bottom: 20px;
        }

        .pdf-wrapper {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .pdf-box {
            flex: 1 1 45%;
            min-width: 300px;
        }

        .pdf-title {
            margin-bottom: 8px;
            font-size: 1.1rem;
            color: #34495e;
            text-align: center;
        }

        .pdf-viewer {
            width: 100%;
            height: 400px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .footer {
            margin-top: 20px;
            text-align: right;
        }

        .footer a {
            text-decoration: none;
            color: #2980b9;
            margin-left: 10px;
        }

        @media (max-width: 900px) {
            .pdf-box {
                flex: 1 1 100%;
            }
            .pdf-viewer {
                height: 500px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">Side-by-Side PDF Preview</div>

    <div class="pdf-wrapper">
        <div class="pdf-box">
            <div class="pdf-title">Document 1</div>
            <iframe class="pdf-viewer" src="Adani Gas Payment Receipt.pdf">
                This browser does not support PDFs. <a href="pdfs/file1.pdf">Download PDF</a>.
            </iframe>
        </div>

        <div class="pdf-box">
            <div class="pdf-title">Document 2</div>
            <iframe class="pdf-viewer" src="bookings.pdf">
                This browser does not support PDFs. <a href="pdfs/file2.pdf">Download PDF</a>.
            </iframe>
        </div>
    </div>

    <div class="footer">
        <a href="pdfs/file1.pdf" download>Download File 1</a>
        <a href="pdfs/file2.pdf" download>Download File 2</a>
    </div>
</div>

</body>
</html>
