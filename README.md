# 📃 PHP SDK for Image Resizer

This is a lightweight PHP SDK for interacting with the [Image Resizer Service](https://github.com/m1n64/image-resizing-shared-service).
It provides a simple, typed API for uploading and retrieving images via the REST interface.

---

## ✨ Features

- Upload images via file, binary, or blob
- Retrieve image metadata and links
- Strict types using DTOs and Enums
- Guzzle-based HTTP client
- Supports optional API key authentication
- Fully unit-tested

---

## ⚙️ Installation

```bash
composer require m1n64/image-resizer-sdk
```

---

## 🚀 Quick Start

```php
use M1n64\ImageResizer\Client;

$client = new Client(
    xApiKey: null, // or 'your-api-key', if required
    baseUrl: 'http://localhost:5689'
);

$image = $client->upload('/path/to/image.jpg');
echo $image->status->value; // 'pending', 'ready', etc.
```

---

## 🔧 Available Methods

### ✅ Health Check

```php
$ping = $client->ping();
echo $ping->message; // "pong"
echo $ping->timestamp->format(DATE_ATOM);
```

### 📎 Upload via multipart
```php
$image = $client->upload('/path/to/image.jpg');
```

### 🔢 Upload via binary stream
```php
$image = $client->uploadBinary('/path/to/image.jpg');
```

### 📁 Upload from blob in memory
```php
$imageData = file_get_contents('/path/to/image.jpg');
$image = $client->uploadFromBlob($imageData, 'my.jpg');
```

### 🔍 Get image by ID
```php
$image = $client->get('uuid-here');
echo $image->originalUrl;
echo $image->status->isReady() ? 'Ready' : 'Processing';
```

---

## 📊 Response Entity: `Image`

```php
Image {
  UuidInterface $id
  string $originalUrl
  ?string $compressedUrl
  int $size
  string $mime
  ImageStatus $status
  ?Thumbnail[] $thumbnails
}
```

Enum `ImageStatus`: `pending`, `processing`, `ready`, `error`

You can call:
```php
$image->status->isReady();
$image->status->isPending();
```

---

## 📖 Related

This SDK is made to work with the [Image Resizer Shared Service](https://github.com/m1n64/image-resizing-shared-service), which provides:

- A local HTTP/gRPC server
- Image compression to WebP
- Thumbnail generation
- Configurable via JSON or ENV
- Distributed as a standalone binary or Docker image

---

## 🎓 Tests

```bash
composer test
```

Tests use full mocking of the HTTP layer (Guzzle), no external service required.

---

## 🧙‍♂️ Author

Made with ❤️ by the **[Kirill Sakharov](https://github.com/m1n64) ([LinkedIn](https://www.linkedin.com/in/kirill-sakharov-862072227/))**

