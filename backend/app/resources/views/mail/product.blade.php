<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Новый продукт создан</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .container {
            background-color: #f9f9f9;
            border-radius: 8px;
            padding: 20px;
            border: 1px solid #ddd;
        }
        h1 {
            color: #2c3e50;
            margin-top: 0;
        }
        .product-info {
            background-color: #fff;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .product-field {
            margin-bottom: 10px;
        }
        .field-label {
            font-weight: bold;
            color: #555;
        }
        .field-value {
            margin-left: 10px;
            color: #333;
        }
        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-available {
            background-color: #d4edda;
            color: #155724;
        }
        .status-unavailable {
            background-color: #f8d7da;
            color: #721c24;
        }
        .data-section {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 3px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Новый продукт успешно создан!</h1>

        <p>Добрый день!</p>
        <p>Был создан новый продукт со следующими параметрами:</p>

        <div class="product-info">
            <div class="product-field">
                <span class="field-label">Артикул:</span>
                <span class="field-value">{{ $product->article }}</span>
            </div>

            <div class="product-field">
                <span class="field-label">Название:</span>
                <span class="field-value">{{ $product->name }}</span>
            </div>

            <div class="product-field">
                <span class="field-label">Статус:</span>
                <span class="status-badge {{ $product->status === 'available' ? 'status-available' : 'status-unavailable' }}">
                    {{ ucfirst($product->status) }}
                </span>
            </div>

            @if($product->data && is_array($product->data) && count($product->data) > 0)
            <div class="product-field">
                <span class="field-label">Дополнительные данные:</span>
                <div class="data-section">
                    @foreach($product->data as $key => $value)
                        <div>
                            <strong>{{ ucfirst($key) }}:</strong>
                            @if(is_array($value))
                                {{ json_encode($value, JSON_UNESCAPED_UNICODE) }}
                            @else
                                {{ $value }}
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="product-field">
                <span class="field-label">Дата создания:</span>
                <span class="field-value">{{ $product->created_at->format('d.m.Y H:i') }}</span>
            </div>
        </div>

        <p>С уважением,<br>Ваша команда</p>
    </div>
</body>
</html>
