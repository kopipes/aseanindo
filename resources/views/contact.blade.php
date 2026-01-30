<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form Submission</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 20px; border-radius: 5px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
        <img src="https://kontakami.com/img/rebranding/logokontakami.svg" style="display: block; margin: 0 auto;" alt="Kontakami Logo">
        <h2 style="color: #333333; text-align: center;">Anda memiliki pesan baru dari form Hubungi Kami:</h2>
        <div style="margin-top: 20px;">
            <p style="color: #333333; font-size: 16px;"><strong>Nama:</strong> {{ $name }}</p>
            <p style="color: #333333; font-size: 16px;"><strong>Email:</strong> {{ $email }}</p>
            <p style="color: #333333; font-size: 16px;"><strong>Kode Negara:</strong> {{ $country_code }}</p>
            <p style="color: #333333; font-size: 16px;"><strong>Nomor Telepon:</strong> {{ $number }}</p>
            <p style="color: #333333; font-size: 16px;"><strong>Perusahaan:</strong> {{ $company ?? 'Tidak ada' }}</p>
            <p style="color: #333333; font-size: 16px;"><strong>Pesan:</strong> {{ $userMessage }}</p>
        </div>
        <div style="margin-top: 30px; text-align: center;">
            <p style="color: #999999; font-size: 14px;">© {{ date('Y') }} Kontakami. All Rights Reserved.</p>
        </div>
    </div>
</body>
</html>
