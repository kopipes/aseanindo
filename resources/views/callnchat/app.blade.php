<!DOCTYPE html>
<html class="h-full" lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Kontakami.com</title>
	<link rel="shortcut icon" href="{{asset('favicon.ico')}}">

	<link rel="stylesheet" href="{{ asset('css/app.css') }}">
	<link rel="stylesheet" href="https://muhammadlailil.github.io/iconsax/style/iconsax.css"/>
	
    <meta http-equiv='cache-control' content='no-cache'>
    <meta http-equiv='expires' content='0'>
    <meta http-equiv='pragma' content='no-cache'>
	@vite('resources/css/app.css')
</head>
<body  class="h-full bg-body font-krub-medium overflow-y-hidden">
	<div id="app" data-page="{{json_encode($page)}}" class="h-full flex justify-center"></div>
	@vite('resources/js/app.js')
</body>
</html>